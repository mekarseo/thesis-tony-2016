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
                        'roles'   => ['Teacher', 'Viewer'],
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
        $data['t_title']        = Yii::t('app', 'Reports');
        $data['t_nofication']   = Yii::t('app', 'Nofications');
        $data['t_teacher']      = Yii::t('app', 'Teachers');
        $data['t_student']      = Yii::t('app', 'Students');
        $data['t_activity']     = Yii::t('app', 'Activities');
        $data['t_stat_talent_type']  = Yii::t('app', 'Talent Type');
        $data['t_stat_talent_sub']   = Yii::t('app', 'Talent Sub');

        // Link
        $data['l_nofication']   = Url::to(['/reports/nofication']);
        $data['l_teacher']      = Url::to(['/reports/teacher']);
        $data['l_student']      = Url::to(['/reports/student']);
        $data['l_activity']     = Url::to(['/reports/activity']);
        $data['l_stat_talent_type']  = Url::to(['/reports/talent-type']);
        $data['l_stat_talent_sub']   = Url::to(['/reports/talent-sub']);

        // Class
        $data['c_nofication']   = 'fa fa-bell';
        $data['c_teacher']      = 'fa fa-mortar-board';
        $data['c_student']      = 'fa fa-group';
        $data['c_activity']     = 'fa fa-child';
        $data['c_stat_talent_type']  = 'fa fa-line-chart';
        $data['c_stat_talent_sub']   = 'fa fa-line-chart';

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }
}
