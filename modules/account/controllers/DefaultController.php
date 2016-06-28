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

class DefaultController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
    	// Text
    	$data['t_title']		= Yii::t('app', 'My Account');
    	$data['t_profile']		= Yii::t('app', 'Profile Information');
    	$data['t_education']	= Yii::t('app', 'Educational Information');
    	$data['t_account']		= Yii::t('app', 'Manage Account');
        $data['t_grade']        = Yii::t('app', 'Grade Record');
        $data['t_activity']     = Yii::t('app', 'Activity Record');

    	// Link
    	$data['l_profile']		= Url::to(['/account/profile']);
    	$data['l_education']	= Url::to(['/account/education']);
    	$data['l_account']		= Url::to(['/account/account']);
        $data['l_grade']        = Url::to(['/account/grade']);
        $data['l_activity']     = Url::to(['/account/activity']);

    	// Class
    	$data['c_profile']		= 'fa fa-user';
    	$data['c_education']	= 'fa fa-book';
    	$data['c_account']		= 'fa fa-cogs';
        $data['c_grade']        = 'fa fa-certificate';
        $data['c_activity']     = 'fa fa-child';

    	Yii::$app->view->title = $data['t_title'];
    	Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }
}