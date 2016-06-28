<?php

namespace app\modules\reports\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\modules\reports\models\ProfileModel;
use app\modules\reports\models\ActivityHistory;

class ActivityStudent extends ActiveRecord
{
    public $operator;
    public $status;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity_student}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section', 'talent', 'activity', 'status', 'operator'], 'safe'],
            [['activity', 'status', 'operator'], 'string'],
            [['talent'], 'string', 'max' => 150],
            [['section'], 'string', 'max' => 9],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'operator'  => Yii::t('app', 'Operator'),
            'talent'    => Yii::t('app', 'Talent'),
            'activity'  => Yii::t('app', 'Activity'),
            'section'   => Yii::t('app', 'Section'),
            'create_at' => Yii::t('app', 'Create At'),
            'status'    => Yii::t('app', 'Last Status'),
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->find();
        
        $this->load($params);
       
        if ($this->validate()) {
            $query->select(['a.*', 'b.name AS operator', 'c.status AS status']);
            $query->alias('a');
            $query->joinWith(['operate', 'histories']);
            $query->where(['in', 'c.id', $this->lastHistories]);

            $query->andFilterWhere(['like', 'a.section', $this->section])
            ->andFilterWhere(['like', 'operator', $this->operator])
            ->andFilterWhere(['like', 'a.talent', $this->talent])
            ->andFilterWhere(['like', 'a.activity', $this->activity])
            ->andFilterWhere(['like', 'status', $this->status]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['section', 'operator', 'talent', 'activity', 'status'],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
            'totalCount' => $query->count(),
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperate()
    {
        return $this->hasOne(ProfileModel::className(), ['user_id' => 'student_id'])->alias('b');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistories()
    {
        return $this->hasMany(ActivityHistory::className(), ['activity_id' => 'id'])->alias('c');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastHistories()
    {
        $result = ActivityHistory::find()->select(['max(`id`) AS id'])->groupBy('activity_id')->all();

        return ArrayHelper::getColumn($result, 'id');
    }
}
