<?php
/**
 * @Final File
 */
namespace app\modules\account\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class ActivityHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'approve_id', 'status', 'comment', 'process_at'], 'required'],
            [['activity_id', 'approve_id'], 'integer'],
            [['comment'], 'string'],
            [['process_at'], 'safe'],
            [['status'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'approve_id' => Yii::t('app', 'Operator'),
            'status'     => Yii::t('app', 'Status'),
            'comment'    => Yii::t('app', 'Comment'),
            'process_at' => Yii::t('app', 'Process At'),
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function search($id)
    {
        $query = $this->find();
        $query->where(['activity_id' => $id]);
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
    public function getOperator()
    {
        $result = Yii::$app->db->createCommand("SELECT * FROM {{%profile}} WHERE `user_id` = " . (int)$this->approve_id . ";")->queryOne();

        return $result['name'];
    }
}
