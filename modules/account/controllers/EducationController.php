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
use app\modules\account\models\EducationModel;

class EducationController extends Controller
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
                        'roles'   => ['Student'],
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
        $data['t_title']    = Yii::t('app', 'Educational Information');

        $data['model'] = $this->findModel(Yii::$app->user->getId());

        Yii::$app->view->title = $data['t_title'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Account'), 'url' => ['/account']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('index', $data);
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
        if (($model = EducationModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}