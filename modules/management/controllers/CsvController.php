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
use yii\web\UploadedFile;
use app\modules\management\models\CsvModel;
use app\modules\management\models\CsvList;

class CsvController extends Controller
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
                'only'  => ['index', 'list'],
                'rules' => [
                    [
                        'actions' => ['index', 'list'],
                        'allow'   => true,
                        'roles'   => ['Staff'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'list'  => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new CsvModel;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');
            if ($model->upload()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'CSV has been uploaded'));
                
                return $this->redirect(['/management/csv']);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'Csv can not uploaded'));
        }

        return $this->getForm();
    }
    
    public function actionList()
    {
        $model = new CsvList;
        $data['model']        = $model;
        $data['dataProvider'] = $model->search(Yii::$app->request->queryParams);

        // Text
        $data['t_title']    = Yii::t('app', 'CSV Data');
        $data['t_cancel']   = Yii::t('app', 'Cancel');
        // Link
        $data['l_cancel']   = Url::to(['/management/student']);

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List') . ' ' . $data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_cancel}}', $data['l_cancel'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_cancel}}', $data['t_cancel'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Management'), 'url' => ['/management']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Students'), 'url' => ['/management/student']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }

    public function getForm()
    {
        // Text
        $data['t_title']    = Yii::t('app', 'CSV Upload');
        $data['l_cancel']   = Url::to(['/management']);

        $data['model'] = new CsvModel;

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

        if (Yii::$app->session->getFlash('duplicate')) {
            $data['duplicate'] = Yii::$app->session->getFlash('duplicate');

            Yii::$app->session->removeFlash('duplicate');
        } else {
            $data['duplicate'] = '';
        }

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Management'), 'url' => ['/management']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('form', $data);
    }
}
