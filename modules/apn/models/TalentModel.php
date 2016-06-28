<?php
/**
 * @Final File
 */
namespace app\modules\apn\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class TalentModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%talent_type_sub}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'Talent Type'),
            'name' => Yii::t('app', 'Talent Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function search()
    {
        $query = $this->find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'totalCount' => $query->count(),
        ]);

        return $dataProvider;
    }

    /**
     * @return \yii\db\Command
     */
    public function getTalentType()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%talent_type}};")->queryAll();
        
        return ArrayHelper::map($query, 'name', 'name');
    }
}
