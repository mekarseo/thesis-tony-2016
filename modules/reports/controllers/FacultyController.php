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
use app\modules\reports\models\TalentModel;

class FacultyController extends Controller
{
    public $enableCsrfValidation = false;

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
                        'roles'   => ['Admin', 'Staff', 'Viewer', 'Teacher'],
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
        $data['model']         = new TalentModel;
        $data['dataProvider']  = $data['model']->search(Yii::$app->request->queryParams);      
        $data['t_title']    = Yii::t('app', 'Faculty Stat');

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['/reports']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
    }
}
