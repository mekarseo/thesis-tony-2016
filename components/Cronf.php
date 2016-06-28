<?php
/**
 * @Final File
 */
namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\management\models\SerialCode;

class Cronf extends Component {
	public function autoUpdate() {
		$models = SerialCode::find()->where(['<','expire_time', time()])->all();
		if (!empty($models)) {
			foreach($models as $model) {
				$model->delete();
			}
		}
	}

	public function validate($serial) {
		$model = SerialCode::find()
			->where(['>=','expire_time', time()])
			->andWhere(['serial' => $serial])
			->andWhere(['status' => 'available'])
			->one();
		
		if ($model != null) {
			$model->status = 'activate';
			$model->save();

			return true;
		} else {
			return false;
		}
	}
}