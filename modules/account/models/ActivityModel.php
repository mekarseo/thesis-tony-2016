<?php
/**
 * @Final File
 */
namespace app\modules\account\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ActivityModel extends ActiveRecord
{
    /**
     * @property
     */
    public $type;
    public $comment;
    
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
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param integer $id
     *
     * @return ActiveDataProvider
     */
    public function search($id)
    {
        $query = $this->find();
        $query->where(['student_id' => $id]);

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
    public function getTalentType($sub = '')
    {
        if ($sub) {
            $query = Yii::$app->db->createCommand("SELECT * FROM {{%talent_type_sub}} WHERE `name` = '" . $sub . "';")->queryOne();
        
            return $query['type'];
        } else {
            $query = Yii::$app->db->createCommand("SELECT * FROM {{%talent_type}};")->queryAll();
        
            return ArrayHelper::map($query, 'name', 'name');
        }
    }

    /**
     * @return \yii\db\Command
     */
    public function getTalentSub($type)
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%talent_type_sub}} WHERE `type` = '" . $type . "';")->queryAll();

        return ArrayHelper::map($query, 'name', 'name');
    }

    /**
     * @return \yii\db\Command
     */
    public function create()
    {
        $this->student_id = Yii::$app->user->getId();
        $this->create_at  = date('Y-m-d H:i:s');
        $this->save();
        
        Yii::$app->db->createCommand("INSERT INTO {{%activity_history}} SET `activity_id` = " . (int)$this->id . ", `approve_id` = '" . $this->student_id . "', `status` = 'Pending', `comment` = '" . $this->comment . "', `process_at` = '" . date('Y-m-d H:i:s') . "';")->execute();
        
        return true;
    }

    /**
     * @return \yii\db\Command
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        Yii::$app->db->createCommand("UPDATE {{%activity_student}} SET `student_id` = '" . $this->student_id . "', `talent` = '" . $this->talent . "', `activity` = '" . $this->activity . "', `section` = '" . $this->section . "' WHERE `id` = " . (int)$this->id . ";")->execute();
        
        Yii::$app->db->createCommand("UPDATE {{%activity_history}} SET `approve_id` = '" . $this->student_id . "', `comment` = '" . $this->comment . "' WHERE `activity_id` = " . (int)$this->id . " AND `status` = 'Pending';")->execute();
        
        return true;
    }
}
