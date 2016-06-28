<?php
/**
 * @Final File
 */
namespace app\modules\apn\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class BannerModel extends Model
{
	public $imageFile;

	public function attributeLabels()
    {
        return [
            'imageFile'	=> Yii::t('app', 'Image File'),
        ];
    }

	public function rules()
	{
		return [
			[['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, gif'],
		];
	}

	public function upload($name)
	{
		if ($this->validate()) {
			$this->imageFile->saveAs(Yii::getAlias('@webroot') . '/image/' . $name);
			return true;
		}

		return false;
	}
}