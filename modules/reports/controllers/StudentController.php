<?php
/**
 * @Final File
 */
namespace app\modules\reports\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\reports\models\StudentModel;

class StudentController extends Controller
{
    public $enableCsrfValidation = false;

    public $dataLayout = <<< HTML
    <div class="panel-heading">
        <div class="pull-left"><i class="fa fa-list"></i> {{list}}</div>
        <div class="pull-right">
            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-search"><i class="fa fa-search"></i></button>
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
                'only'  => ['index', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow'   => true,
                        'roles'   => ['Admin', 'Staff', 'Teacher', 'Viewer'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view'  => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new StudentModel;
        $data['model']        = $model;
        $data['dataProvider'] = $model->search(Yii::$app->request->queryParams);

        // Text
        $data['t_title']    = Yii::t('app', 'Student');
        $data['t_cancel']   = Yii::t('app', 'Cancel');
        // Link
        $data['l_cancel']   = Url::to(['/reports/student']);

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List') . ' ' . $data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_cancel}}', $data['l_cancel'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_cancel}}', $data['t_cancel'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['/reports']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }

    public function actionView($id)
    {
        $data['model']      = StudentModel::findOne($id);
        // Text
        $data['t_title']    = Yii::t('app', 'Student Detail');

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['/reports']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student'), 'url' => ['/reports/student']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('view', $data);
    }
}
