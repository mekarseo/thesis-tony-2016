<?php
/**
 * @Final File
 */
namespace app\modules\reports\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\reports\models\ProfileModel;
use app\modules\reports\models\ActivityHistory;

class ActivityModel extends ActiveRecord
{
    /**
     * @property
     */
    public $type;
    public $comment;
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
            [['talent', 'activity'], 'required'],
            [['student_id'], 'integer'],
            [['activity'], 'string'],
            [['create_at'], 'safe'],
            [['talent'], 'string', 'max' => 150],
            [['section'], 'string', 'max' => 9],
            [['comment'], 'string'],
            [['status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type'      => Yii::t('app', 'Talent Type'),
            'talent'    => Yii::t('app', 'Talent'),
            'activity'  => Yii::t('app', 'Activity'),
            'section'   => Yii::t('app', 'Section'),
            'create_at' => Yii::t('app', 'Create At'),
            'comment'   => Yii::t('app', 'Comment'),
            'operator'  => Yii::t('app', 'Operator'),
            'status'    => Yii::t('app', 'Last Status'),
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param integer $id
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = $this->find();
        $query->alias('a');
        $query->select(['a.*', 'b.name AS operator', 'c.status AS status']);
        $query->joinWith(['profile', 'history']);
        $query->where(['a.talent' => $this->ownTalent]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'attributes' => ['section', 'operator', 'talent', 'activity', 'status', 'create_at'],
            ],
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
    public function getOwnTalent()
    {
        $model = ProfileModel::findOne(Yii::$app->user->getId());
        $bio = json_decode($model->bio, true);
        
        return $bio['talent_sub'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(ProfileModel::className(), ['user_id' => 'student_id'])->alias('b');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasOne(ActivityHistory::className(), ['activity_id' => 'id'])->alias('c')->where(['in', 'c.id', $this->lastHistories])->andWhere(['c.status' => 'Pending']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastHistories()
    {
        $result = ActivityHistory::find()->select(['max(`id`) AS id'])->groupBy('activity_id')->all();

        return ArrayHelper::getColumn($result, 'id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastHistory()
    {
        $result = ActivityHistory::find()->where(['activity_id' => $this->id])->orderBy(['id' => SORT_DESC])->limit(1)->one();

        return $result->status;
    }
}
