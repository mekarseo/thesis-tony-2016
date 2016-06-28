<?php
/**
 * @Final File
 */
namespace app\modules\management\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\management\models\StudentModel;

class StudentController extends Controller
{
    public $enableCsrfValidation = false;
    
    public $dataLayout = <<< HTML
    <div class="panel-heading">
        <div class="pull-left"><i class="fa fa-list"></i> {{list}}</div>
        <div class="pull-right">
            <a href="{{l_create}}" data-toggle="tooltip" title="{{t_create}}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></a>
            <button type="button" data-toggle="tooltip" title="{{t_delete}}" class="btn btn-danger btn-xs" onclick="confirm('{{t_confirm}}') ? $('#form-delete').submit() : false;">
                <i class="fa fa-trash-o"></i>
            </button>
        </div>
        <div class="clearfix"></div>
    </div>
    <form action="{{l_delete}}" method="post" enctype="multipart/form-data" id="form-delete">
    {items}
    </form>
    <div class="panel-footer">
        <div class="pull-right">{summary}</div>
        <div class="pull-left">{pager}</div>
        <div class="clearfix"></div>
    </div>
HTML;
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['Staff'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'     => ['get'],
                    'create'    => ['get', 'post'],
                    'update'    => ['get', 'post'],
                    'delete'    => ['post'],
                ],
            ],
        ];
    }

    public function actionList()
    {
        return $this->redirect(['/management/csv/list']);
    }

    public function actionIndex()
    {
    	return $this->getList();
    }

    public function actionCreate($id)
    {
        $model = new StudentModel;
        $model->csv_id = $id;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Student has been created'));
                
                return $this->redirect(['/management/student']);
            }

            Yii::$app->session->set('error', Yii::t('app', 'Student has been duplicated'));
        }

        return $this->getForm();
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Student has been updated'));
                
                return $this->redirect(['/management/student']);
            }

            Yii::$app->session->set('error', Yii::t('app', 'Student has not been updated'));
        }

        return $this->getForm();
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->post('selected') != null) {
            foreach (Yii::$app->request->post('selected') as $id) {
                $this->findModel($id)->delete();
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Student has been deleted'));
        }

        return $this->redirect(['/management/student']);
    }

    public function getList()
    {
        $model = new StudentModel;
        $data['dataProvider'] = $model->search();

        // Text
        $data['t_title']    = Yii::t('app', 'Students');
        $data['t_confirm']  = Yii::t('app', 'Are You Sure?');
        $data['t_create']   = Yii::t('app', 'Create');
        $data['t_delete']   = Yii::t('app', 'Delete');
        // Link
        $data['l_create']   = Url::to(['/management/student/list']);
        $data['l_delete']   = Url::to(['/management/student/delete']);

        if (Yii::$app->session->getFlash('success')) {
            $data['success'] = Yii::$app->session->getFlash('success');

            Yii::$app->session->removeFlash('success');
        } else {
            $data['success'] = '';
        }

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List') . ' ' . $data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_create}}', $data['l_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{l_delete}}', $data['l_delete'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_confirm}}', $data['t_confirm'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_create}}', $data['t_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_delete}}', $data['t_delete'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Management'), 'url' => ['/management']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }

    public function getForm()
    {
        $data['l_cancel']   = Url::to(['/management/student']);

        if (Yii::$app->controller->action->id == 'update') {
            $data['t_title'] = Yii::t('app', 'Update');
            $data['model']   = $this->findModel(Yii::$app->request->get('id'));
            $data['model']->setProfile();
        } else {
            $data['t_title'] = Yii::t('app', 'Create');
            $data['model']   = new StudentModel;
            $data['model']->csv_id = Yii::$app->request->get('id');
            $data['model']->firstname = $data['model']->personal->first_name;
            $data['model']->lastname  = $data['model']->personal->last_name;
            $data['model']->email     = $data['model']->personal->email;
        }

        if (Yii::$app->session->get('error')) {
            $data['error'] = Yii::$app->session->get('error');

            Yii::$app->session->remove('error');
        } else {
            $data['error'] = '';
        }

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Management'), 'url' => ['/management']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Students'), 'url' => ['/management/student']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('form', $data);
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
