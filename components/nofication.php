<?php
/**
 * @Final File
 */
namespace app\components;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Url;
use app\modules\reports\models\NoficationModel;

class Nofication extends Widget {
	protected static $template = <<< HTML
	<a href="{{l_nofication}}" class="nofication">
        <div>
            <i class="fa fa-bell"></i>
            <span class="badge">{{sum_nofication}}</span>
        </div>
    </a>
HTML;

	public function run()
	{
		$model = new NoficationModel;

		/**
		 * Show Only Teacher Role
		 * @return NULL
		 */
		if (!isset(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['Teacher'])) {
			return null;
		}

		/**
		 * Stay on nofication Page
		 * @return NULL
		 */
		if (Yii::$app->controller->id == 'nofication') {
			return null;
		}

		/**
		 * Show when have nofication
		 * @return NULL
		 */
		if ($model->nofication() == 0) {
			return null;
		}

		$nofication = str_replace('{{sum_nofication}}', $model->nofication(), self::$template);
		$nofication = str_replace('{{l_nofication}}', Url::to(['/reports/nofication']), $nofication);
		echo $nofication;
	}
}