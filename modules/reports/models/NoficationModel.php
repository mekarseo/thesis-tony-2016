<?php
/**
 * @Final File
 */
namespace app\modules\reports\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\modules\reports\models\ProfileModel;
use app\modules\reports\models\ActivityHistory;

class NoficationModel extends ActiveRecord
{
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
            [['student_id', 'talent', 'activity', 'section', 'create_at'], 'required'],
            [['student_id'], 'integer'],
            [['activity'], 'string'],
            [['create_at'], 'safe'],
            [['talent'], 'string', 'max' => 150],
            [['section'], 'string', 'max' => 9]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'talent' => Yii::t('app', 'Talent'),
            'activity' => Yii::t('app', 'Activity'),
            'section' => Yii::t('app', 'Section'),
            'create_at' => Yii::t('app', 'Create At'),
        ];
    }

    /**
     * @return ActiveDataProvidor
     */
    public function nofication()
    {
        $query = $this->find();
        $query->alias('a');
        $query->joinWith('history');
        $query->where(['like', 'a.talent', $this->ownTalent]);
        
        return $query->count();
    }

    /**
     * @return \yii\db\Command
     */
    public function getOwnTalent()
    {
        $model = ProfileModel::findOne(Yii::$app->user->getId());
        $bio = json_decode($model->bio, true);
        
        return $bio['talent_sub'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasOne(ActivityHistory::className(), ['activity_id' => 'id'])->alias('b')->where(['in', 'b.id', $this->lastHistories])->andWhere(['b.status' => 'Pending']);
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
