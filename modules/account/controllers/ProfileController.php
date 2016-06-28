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
use app\modules\account\models\ProfileModel;

class ProfileController extends Controller
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
                        'actions' 	=> ['index'],
                        'allow' 	=> true,
                        'roles' 	=> ['Student'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = $this->findModel(Yii::$app->user->getId());
        
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->passwordCheck()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Password not match.'));

                return $this->redirect(['/account/profile']);
            }

            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Profile has been updated.'));
                
                return $this->redirect(['/account/profile']);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'Profile has not been updated.'));
        }

        return $this->getForm();
    }

    public function getForm()
    {
        // Text
        $data['t_title']    = Yii::t('app', 'Profile Information');
        $data['l_cancel']   = Url::to(['/account']);

        $data['model'] = $this->findModel(Yii::$app->user->getId());
        $data['model']->mobile  = $data['model']->personal->mobile;
        $data['model']->address = $data['model']->personal->address;

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
        if (($model = ProfileModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}