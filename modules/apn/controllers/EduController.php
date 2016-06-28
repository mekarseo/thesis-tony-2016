<?php
/**
 * @Final File
 */
namespace app\modules\apn\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\apn\models\EduModel;

class EduController extends Controller
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
                        'roles'   => ['Admin'],
                    ]
                ],
            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'index'     => ['get'],
                    'create'    => ['get', 'post'],
                    'update'    => ['get', 'post'],
                    'delete'    => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!Yii::$app->request->get('code'))
            return $this->redirect(['/apn']);

        return true;
    }

    public function actionIndex($code)
    {
    	return $this->getList($code);
    }

    public function actionCreate($code)
    {
        $model = new EduModel;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', $code.' has been created'));
                
                return $this->redirect(['/apn/edu/index', 'code' => $code]);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', $code.' has been duplicated'));
        }

        return $this->getForm($code);
    }

    public function actionUpdate($code, $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', $code.' has been updated'));
                
                return $this->redirect(['/apn/edu/index', 'code' => $code]);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', $code.' has not been updated'));
        }

        return $this->getForm($code);
    }

    public function actionDelete($code)
    {
        if (Yii::$app->request->isPost && Yii::$app->request->post('selected') != null) {
            foreach (Yii::$app->request->post('selected') as $id) {
                $this->findModel($id)->delete();
            }
        }

        return $this->redirect(['/apn/edu/index', 'code' => $code]);
    }

    public function getList($code)
    {
        $data['code'] = $code;
        $model = new EduModel;
        $data['dataProvider'] = $model->search($code);

        // Text
        $data['t_title']    = Yii::t('app', $code);
        $data['t_confirm']  = Yii::t('app', 'Are You Sure?');
        $data['t_create']   = Yii::t('app', 'Create');
        $data['t_delete']   = Yii::t('app', 'Delete');
        // Link
        $data['l_create']   = Url::to(['/apn/edu/create', 'code' => $code]);
        $data['l_delete']   = Url::to(['/apn/edu/delete', 'code' => $code]);

        if (Yii::$app->session->getFlash('success')) {
            $data['success'] = Yii::$app->session->getFlash('success');

            Yii::$app->session->removeFlash('success');
        } else {
            $data['success'] = '';
        }

        if (Yii::$app->session->getFlash('error')) {
            $data['error'] = Yii::$app->session->getFlash('error');

            Yii::$app->session->removeFlash('error');
        } else {
            $data['error'] = '';
        }

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List') . ' ' . $data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_create}}', $data['l_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{l_delete}}', $data['l_delete'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_confirm}}', $data['t_confirm'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_create}}', $data['t_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_delete}}', $data['t_delete'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Panel'), 'url' => ['/apn']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];
        
        return $this->render('index', $data);
    }

    public function getForm($code)
    {
        $data['code'] = $code;
        if (Yii::$app->request->get('id')) {
            $data['model']   = $this->findModel(Yii::$app->request->get('id'));
            $data['model']->setEducation();
            $data['t_title'] = Yii::t('app', 'Update');
        } else {
            $data['model']   = new EduModel;
            $data['model']->type = $code;
            $data['t_title'] = Yii::t('app', 'Create');
        }

        // Link
        $data['l_cancel']   = Url::to(['/apn/edu/index', 'code' => $code]);

        if (Yii::$app->session->getFlash('error')) {
            $data['error'] = Yii::$app->session->getFlash('error');

            Yii::$app->session->removeFlash('error');
        } else {
            $data['error'] = '';
        }

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Panel'), 'url' => ['/apn']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', $code), 'url' => ['/apn/edu/index', 'code' => $code]];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('form', $data);
    }

    public function actionAjax()
    {
        if (Yii::$app->request->isAjax) {
            $model = new EduModel;
            
            if (Yii::$app->request->post('type') == 'faculty') {
                $results = $model->facultyList(Yii::$app->request->post('parent'));
            }

            if (Yii::$app->request->post('type') == 'division') {
                $results = $model->divisionList(Yii::$app->request->post('parent'));
            }
            
            $html = '<option value="">' . Yii::t('app', 'Select') . '</option>';
            foreach($results as $value => $text)
            {
                $html .= '<option value="' . $value . '">' . $text . '</div>';
            }

            return trim($html);
        }
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
        if (($model = EduModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
