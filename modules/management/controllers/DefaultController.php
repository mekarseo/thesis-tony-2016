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
                        'roles'   => ['Admin', 'Staff'],
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
        $data['t_title']        = Yii::t('app', 'Management');

        $data['t_csv_upload']   = Yii::t('app', 'CSV Upload'); 

        $data['t_teacher']      = Yii::t('app', 'Teachers');
        $data['t_student']      = Yii::t('app', 'Students');
        $data['t_viewer']       = Yii::t('app', 'Viewer');

        $data['t_serial']       = Yii::t('app', 'Serial Generator');

        // Link
        $data['l_csv_upload']   = Url::to(['/management/csv']);

        $data['l_teacher']      = Url::to(['/management/teacher']);
        $data['l_student']      = Url::to(['/management/student']);
        $data['l_viewer']       = Url::to(['/management/viewer']);

        $data['l_serial']       = Url::to(['/management/serial']);

        // Class
        $data['c_csv_upload']   = 'fa fa-upload';
        
        $data['c_teacher']      = 'fa fa-mortar-board';
        $data['c_student']      = 'fa fa-group';
        $data['c_viewer']       = 'fa fa-eye';

        $data['c_serial']       = 'fa fa-barcode';

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }
}
