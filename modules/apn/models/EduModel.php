<?php
/**
 * @Final File
 */
namespace app\modules\apn\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class EduModel extends ActiveRecord
{
    /**
     * @property
     */
    public $campus;
    public $faculty;
    public $division;
    public $level = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%edu_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['campus'], 'required', 'when' => function($model) { return $model->type != 'Campus';}],
            [['faculty'], 'required', 'when' => function($model) { return ($model->type == 'Division' || $model->type == 'Major');}],
            [['division'], 'required', 'when' => function($model) { return $model->type == 'Major';}],
            [['level'], 'required', 'when' => function($model) { return $model->type == 'Major';}],
            [['name'], 'trim'],
            [['type'], 'string', 'max' => 64],
            [['parent'], 'safe'],
            [['type', 'parent'], 'unique', 'targetAttribute' => ['name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'      => Yii::t('app', 'Name'),
            'type'      => Yii::t('app', 'Type'),
            'parent'    => Yii::t('app', 'Parent'),
            'campus'    => Yii::t('app', 'Campus'),
            'faculty'   => Yii::t('app', 'Faculty'),
            'division'  => Yii::t('app', 'Division'),
            'level'     => Yii::t('app', 'Level'),
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function search($code)
    {
        $query = $this->find();
        
        switch ($code) {
            case 'Faculty':
                $query->select(['a.*', 'b.name AS campus']);
                $query->alias('a');
                $query->joinwith('parent0');
                $query->andFilterWhere(['b.type' => 'Campus']);
                $query->andWhere(['a.type' => $code]);
                $sort = ['name', 'campus'];
                break;
            case 'Division':
                $query->select(['a.*', 'b.name AS faculty', 'c.name AS campus']);
                $query->alias('a');
                $query->joinwith('parent1');
                $query->andFilterWhere(['b.type' => 'Faculty']);
                $query->andWhere(['a.type' => $code]);
                $sort = ['name', 'faculty', 'campus'];
                break;
            case 'Major':
                $query->select(['a.*', 'b.name AS division', 'c.name AS faculty', 'd.name AS campus']);
                $query->alias('a');
                $query->joinwith('parent2');
                $query->andFilterWhere(['b.type' => 'Division']);
                $query->andWhere(['a.type' => $code]);
                $sort = ['name', 'division', 'faculty', 'campus'];
                break;
            default:
                $query->where(['type' => $code]);
                $sort = ['name'];
                break;
        }
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'attributes' => $sort,
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
    public function create()
    {
       switch ($this->type) {
            case 'Faculty': $this->parent = (int)$this->campus;
                break;
            case 'Division': $this->parent = (int)$this->faculty;
                break;
            case 'Major': $this->parent = (int)$this->division;
                break;
        }
        
        $this->save();

        if ($this->type == 'Major') 
            Yii::$app->db->createCommand("INSERT INTO {{%edu_assign}} SET `major_id` = " . (int)$this->id . ", `levels` = '" . json_encode($this->level) . "';")->execute();
            
        return true;
    }

    /**
     * @return \yii\db\Command
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        switch ($this->type) {
            case 'Faculty': $this->parent = (int)$this->campus;
                break;
            case 'Division': $this->parent = (int)$this->faculty;
                break;
            case 'Major': $this->parent = (int)$this->division;
                break;
            default: $this->parent = 'NULL';
                break;
        }

        Yii::$app->db->createCommand("UPDATE {{%edu_info}} SET `name` = '" . $this->name . "', `type` = '" . $this->type . "', `parent` = " . $this->parent . " WHERE `id` = " . (int)$this->id . ";")->execute();

        if ($this->type == 'Major') 
            Yii::$app->db->createCommand("UPDATE {{%edu_assign}} SET `levels` = '" . json_encode($this->level) . "' WHERE `major_id` = " . (int)$this->id . ";")->execute();
            
        return true;
    }

    /**
     * @return \yii\db\Command
     */
    public function delete()
    {
        $result = Yii::$app->db->createCommand("SELECT * FROM {{%edu_info}} WHERE `parent` = " . (int)$this->id . ";")->execute();

        if ($result == 0) {
            Yii::$app->db->createCommand("DELETE FROM {{%edu_info}} WHERE `id` = '" . (int)$this->id. "';")->execute();
            Yii::$app->session->setFlash('success', Yii::t('app', $this->type . ' has been deleted'));
        } else {
             Yii::$app->session->setFlash('error', Yii::t('app', 'Some ' . $this->type . ' has another used'));
        }
    }

    /**
     * @return \yii\db\Command
     */
    public function setEducation()
    {
        $query = EduModel::find();
        
        switch ($this->type) {
            case 'Faculty':
                $query->select(['b.id AS campus']);
                $query->alias('a');
                $query->joinwith('parent0');
                $query->andFilterWhere(['b.type' => 'Campus']);
                $query->andFilterWhere(['a.id' => $this->id]);
                $result = $query->one();
                break;
            case 'Division':
                $query->select(['b.id AS faculty', 'c.id AS campus']);
                $query->alias('a');
                $query->joinwith('parent1');
                $query->andFilterWhere(['b.type' => 'Faculty']);
                $query->andFilterWhere(['a.id' => $this->id]);
                $result = $query->one();
                break;
            case 'Major':
                $query->select(['b.id AS division', 'c.id AS faculty', 'd.id AS campus']);
                $query->alias('a');
                $query->joinwith('parent2');
                $query->andFilterWhere(['b.type' => 'Division']);
                $query->andFilterWhere(['a.id' => $this->id]);
                $result = $query->one();
                $this->level = $this->levels;
                break;
        }

        $this->campus   = isset($result->campus) ? $result->campus : '';
        $this->faculty  = isset($result->faculty) ? $result->faculty : '';
        $this->division = isset($result->division) ? $result->division : '';
        
    }

    /**
     * @return \yii\db\Command
     * @param campus array
     */
    public function campusList()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%edu_info}} WHERE `type` = 'Campus';")->queryAll();

        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * @return \yii\db\Command
     * @param faculty array
     */
    public function facultyList()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%edu_info}} WHERE `type` = 'Faculty';")->queryAll();

        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * @return \yii\db\Command
     * @param division array
     */
    public function divisionList()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%edu_info}} WHERE `type` = 'Division';")->queryAll();

        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * @return \yii\db\Command
     * @param level array
     */
    public function levelList()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%edu_level}};")->queryAll();

        return ArrayHelper::map($query, 'id', 'name');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     * @param campus
     */
    public function getParent0()
    {
        return $this->hasOne(EduModel::className(), ['id' => 'parent'])->alias('b');
    }

    /**
     * @return \yii\db\ActiveQuery
     * @param campus
     * @param faculty
     */
    public function getParent1()
    {
        return $this->hasOne(EduModel::className(), ['id' => 'parent'])->alias('c')->via('parent0');
    }

    /**
     * @return \yii\db\ActiveQuery
     * @param campus
     * @param faculty
     * @param division
     */
    public function getParent2()
    {
        return $this->hasOne(EduModel::className(), ['id' => 'parent'])->alias('d')->via('parent1');
    }

    /**
     * @return \yii\db\Command
     * @param level[]
     */
    public function getLevels()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%edu_assign}} WHERE `major_id` = " . (int)$this->id . ";")->queryOne();
        
        return json_decode($query['levels'], true);
    }
}
