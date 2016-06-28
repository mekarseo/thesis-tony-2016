<?php
/**
 * @Final File
 */
namespace app\modules\reports\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\AccessControl; 
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\modules\reports\models\ActivityModel;
use app\modules\reports\models\ActivityHistory;

class NoficationController extends Controller
{
    public $enableCsrfValidation = false;

    public $dataLayout = <<< HTML
    <div class="panel-heading">
        <div class="pull-left"><i class="fa fa-list"></i> {{list}}</div>
        <div class="pull-right">
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
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow'   => true,
                        'roles'   => ['Teacher'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'view' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model  = new ActivityModel;
        $data['dataProvider'] = $model->search();

        // Text
        $data['t_title']    = Yii::t('app', 'Nofications');
        $data['t_cancel']   = Yii::t('app', 'Cancel');
        // Link
        $data['l_cancel']   = Url::to(['/reports']);

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List').' '.$data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_cancel}}', $data['l_cancel'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_cancel}}', $data['t_cancel'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['/reports']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }

    public function actionView($id)
    {
        $model = new ActivityHistory;
       
        if ($model->load(Yii::$app->request->post())) {
            $model->activity_id = $id;
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Activity has been create.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Activity has not been create.'));
            }
        }
        
        $model = new ActivityHistory;
        $data['model'] = $model;
        $data['dataProvider'] = $model->search($id);

        $data['t_title']    = Yii::t('app', 'Activity');
        $data['t_cancel']   = Yii::t('app', 'Cancel');
        $data['l_cancel']   = Url::to(['/reports/nofication']);

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List').' '.$data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_cancel}}', $data['l_cancel'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_cancel}}', $data['t_cancel'], $data['dataLayout']);

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

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['/reports']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nofications'), 'url' => ['/reports/nofication']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('view', $data);
    }
}
