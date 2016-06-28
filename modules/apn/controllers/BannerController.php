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
use yii\web\UploadedFile;
use app\modules\apn\models\BannerModel;

class BannerController extends Controller
{
    public $enableCsrfValidation = false;

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['login', 'home'],
                'rules' => [
                    [
                        'actions' => ['login', 'home'],
                        'allow'   => true,
                        'roles'   => ['Admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'login' => ['get', 'post'],
                    'home'  => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        $model = new BannerModel;

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload('login-title.gif')) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Image has been uploaded'));
                return $this->redirect(['/apn/banner/login']);
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'Can not uploaded'));
        }

        return $this->getForm('Login');
    }

    public function actionHome()
    {
        $model = new BannerModel;

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload('header.png')) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Image has been uploaded'));
                return $this->redirect(['/apn/banner/home']);
            }

            Yii::$app->session->set('error', Yii::t('app', 'Can not uploaded'));
        }

        return $this->getForm('Home');
    }
    
    public function getForm($title)
    {
        $data['t_title']    = Yii::t('app', 'Upload Banner') . ' ' . Yii::t('app', $title);
        $data['l_cancel']   = Url::to(['/apn']);

        $data['model'] = new BannerModel;

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
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Panel'), 'url' => ['/apn']];
        Yii::$app->view->params['breadcrumbs'][] = $data['t_title'];

        return $this->render('form', $data);
    }
}
