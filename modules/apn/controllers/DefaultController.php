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
                        'actions'   => ['index'],
                        'allow'     => true,
                        'roles'     => ['Admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class'     => VerbFilter::className(),
                'actions'   => [
                    'index' => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
    	// Text
    	$data['t_title']		= Yii::t('app', 'Admin Panel');
    	
        $data['t_banner_login'] = Yii::t('app', 'Banner Login');
        $data['t_banner_home']  = Yii::t('app', 'Banner Home');

        $data['t_rbac']			= Yii::t('app', 'Rbac');
    	$data['t_user']			= Yii::t('app', 'User');

        $data['t_edu_campus']   = Yii::t('app', 'Campus');
        $data['t_edu_faculty']  = Yii::t('app', 'Faculty');
        $data['t_edu_division'] = Yii::t('app', 'Division');
        $data['t_edu_major']    = Yii::t('app', 'Major');

        $data['t_user_talent']  = Yii::t('app', 'Talent');

        $data['t_staff']        = Yii::t('app', 'Staff');

    	// Link
        $data['l_banner_login'] = Url::to(['/apn/banner/login']);
        $data['l_banner_home']  = Url::to(['/apn/banner/home']);

    	$data['l_rbac']			= Url::to(['/rbac']);
    	$data['l_user']			= Url::to(['/user/admin/index']);

        $data['l_edu_campus']   = Url::to(['/apn/edu/index', 'code' => 'Campus']);
        $data['l_edu_faculty']  = Url::to(['/apn/edu/index', 'code' => 'Faculty']);
        $data['l_edu_division'] = Url::to(['/apn/edu/index', 'code' => 'Division']);
        $data['l_edu_major']    = Url::to(['/apn/edu/index', 'code' => 'Major']);

        $data['l_user_talent']  = Url::to(['/apn/talent']);

        $data['l_staff']        = Url::to(['/apn/staff']);

    	// Class
    	$data['c_banner_login'] = 'fa fa-cogs';
        $data['c_banner_home']  = 'fa fa-cogs';

        $data['c_rbac']			= 'fa fa-user-secret';
    	$data['c_user']			= 'fa fa-user';

        $data['c_edu_campus']   = 'fa fa-graduation-cap';
        $data['c_edu_faculty']  = 'fa fa-graduation-cap';
        $data['c_edu_division'] = 'fa fa-graduation-cap';
        $data['c_edu_major']    = 'fa fa-graduation-cap';

        $data['c_user_talent']  = 'fa fa-star';

        $data['c_staff']        = 'fa fa-users';

    	Yii::$app->view->title = $data['t_title'];
    	Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];
        
        return $this->render('index', $data);
    }
}
