<?php
/**
 * @Final File
 */
namespace app\modules\account\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl; 
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\account\models\GradeModel;

class GradeController extends Controller
{
    public $enableCsrfValidation = false;
    
    public $dataLayout = <<< HTML
    <div class="panel-heading">
        <div class="pull-left"><i class="fa fa-list"></i> {{list}}</div>
        <div class="pull-right">
            <a href="{{l_create}}" data-toggle="tooltip" title="{{t_create}}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></a>
            <a href="{{l_cancel}}" data-toggle="tooltip" title="{{t_cancel}}" class="btn btn-default btn-xs"><i class="fa fa-reply"></i></a>
        </div>
        <div class="clearfix"></div>
    </div>
    {items}
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
                'only'  => ['index', 'create'],
                'rules' => [
                    [
                        'actions' 	=> ['index', 'create'],
                        'allow' 	=> true,
                        'roles' 	=> ['Student'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'     => ['get'],
                    'create'    => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->getList();
    }

    public function actionCreate()
    {
        $model = new GradeModel;
        $model->student_id = Yii::$app->user->getId();
        
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->termCheck()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'This term has created.'));
                
                return $this->redirect(['/account/grade/create']);
            }

            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'This GPA has been create.'));
                
                return $this->redirect(['/account/grade']);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'This GPA has not been create.'));
        }

        return $this->getForm();
    }

    public function getList()
    {
        $model = new GradeModel;
        $data['dataProvider'] = $model->search(Yii::$app->user->getId());

        if (Yii::$app->session->getFlash('success')) {
            $data['success'] = Yii::$app->session->getFlash('success');

            Yii::$app->session->removeFlash('success');
        } else {
            $data['success'] = '';
        }

        // Text
        $data['t_title']    = Yii::t('app', 'Grade Record');
        $data['t_create']   = Yii::t('app', 'Create');
        $data['t_cancel']   = Yii::t('app', 'Cancel');
        // Link
        $data['l_create']   = Url::to(['/account/grade/create']);
        $data['l_cancel']   = Url::to(['/account']);

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List') . ' ' . $data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_create}}', $data['l_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{l_cancel}}', $data['l_cancel'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_create}}', $data['t_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_cancel}}', $data['t_cancel'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Account'), 'url' => ['/account']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }

    public function getForm()
    {
        // Text
        $data['t_title']    = Yii::t('app', 'Create');
        $data['l_cancel']   = Url::to(['/account/grade']);

        $data['model'] = new GradeModel;

        if (Yii::$app->session->getFlash('error')) {
            $data['error'] = Yii::$app->session->getFlash('error');

            Yii::$app->session->removeFlash('error');
        } else {
            $data['error'] = '';
        }

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Account'), 'url' => ['/account']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grade Record'), 'url' => ['/account/grade']];
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
        if (($model = GradeModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}