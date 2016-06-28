<?php
/**
 * @Final File
 */
namespace app\modules\account\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\AccessControl; 
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\modules\account\models\ActivityModel;
use app\modules\account\models\ActivityHistory;

class ActivityController extends Controller
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
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions'   => ['index', 'view', 'create', 'update'],
                        'allow'     => true,
                        'roles'     => ['Student'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'create'    => ['get', 'post'],
                    'update'    => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model  = new ActivityModel;
        $data['dataProvider'] = $model->search(Yii::$app->user->getId());

        if (Yii::$app->session->getFlash('success')) {
            $data['success'] = Yii::$app->session->getFlash('success');

            Yii::$app->session->removeFlash('success');
        } else {
            $data['success'] = '';
        }

        // Text
        $data['t_title']    = Yii::t('app', 'Activity Record');
        $data['t_create']   = Yii::t('app', 'Create');
        $data['t_cancel']   = Yii::t('app', 'Cancel');
        // Link
        $data['l_create']   = Url::to(['/account/activity/create']);
        $data['l_cancel']   = Url::to(['/account']);

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List').' '.$data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('{{l_create}}', $data['l_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{l_cancel}}', $data['l_cancel'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_create}}', $data['t_create'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_cancel}}', $data['t_cancel'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Account'), 'url' => ['/account']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }

    public function actionView($id)
    {
        $model = new ActivityHistory;
        $data['dataProvider'] = $model->search($id);

        $data['t_title']    = Yii::t('app', 'Activity Detail');
        $data['t_cancel']   = Yii::t('app', 'Cancel');
        $data['l_cancel']   = Url::to(['/account/activity']);

        $data['dataLayout'] = str_replace('{{list}}', Yii::t('app', 'List').' '.$data['t_title'], $this->dataLayout);
        $data['dataLayout'] = str_replace('<a href="{{l_create}}" data-toggle="tooltip" title="{{t_create}}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></a>', '', $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{l_cancel}}', $data['l_cancel'], $data['dataLayout']);
        $data['dataLayout'] = str_replace('{{t_cancel}}', $data['t_cancel'], $data['dataLayout']);

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Account'), 'url' => ['/account']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activity Record'), 'url' => ['/account/activity']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('view', $data);
    }

    public function actionCreate()
    {
        if(Yii::$app->request->referrer == null)
            return $this->goBack();
        
        $model = new ActivityModel;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Activity has been create.'));

                return $this->redirect(['/account/activity']);
            }
            
            Yii::$app->session->setFlash('error', Yii::t('app', 'Activity has not been create.'));
        }

        $data['model']      = $model;
        $data['model']->section = date('n/Y');
        $data['t_title']    = Yii::t('app', 'Create');
        $data['l_cancel']   = Url::to(['/account/activity']);

        if (Yii::$app->session->getFlash('error')) {
            $data['error'] = Yii::$app->session->getFlash('error');

            Yii::$app->session->removeFlash('error');
        } else {
            $data['error'] = '';
        }

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Account'), 'url' => ['/account']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activity Record'), 'url' => ['/account/activity']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('create', $data);
    }

    public function actionUpdate($id)
    {
        $model = ActivityModel::findOne($id);
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Activity has been updated.'));

                return $this->redirect(['/account/activity']);
            }
            
            Yii::$app->session->setFlash('error', Yii::t('app', 'Activity has not been updated.'));
        }

        $data['model']       = $model;
        $data['model']->type = $model->getTalentType($model->talent);

        $data['t_title']     = Yii::t('app', 'Update');
        $data['l_cancel']    = Url::to(['/account/activity']);

        if (Yii::$app->session->getFlash('error')) {
            $data['error'] = Yii::$app->session->getFlash('error');

            Yii::$app->session->removeFlash('error');
        } else {
            $data['error'] = '';
        }

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Account'), 'url' => ['/account']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activity Record'), 'url' => ['/account/activity']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('create', $data);
    }

    public function actionAjax()
    {
        if (Yii::$app->request->isAjax) {
            $model   = new ActivityModel;
            $results = $model->getTalentSub(Yii::$app->request->post('type'));
            
            $html = '<option value="">' . Yii::t('app', 'Select') . '</option>';
            foreach($results as $value => $text)
            {
                $html .= '<option value="' . $value . '">' . $text . '</div>';
            }

            return trim($html);
        }
    }

    public function actionSerial()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->cronf->validate(trim(Yii::$app->request->get('serial')))) {
                $html = 'true';
            } else {
                $html = 'false';
            }
            
            echo $html;
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
        if (($model = ActivityModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
