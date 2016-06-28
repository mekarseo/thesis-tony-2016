<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\management\models\UserEducation;
use app\modules\management\models\EduLevel;

class CsvList extends ActiveRecord
{
    /**
     * @property
     */
    public $faculty;
    public $major;
    public $level;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_personal_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term'], 'string', 'max' => 6],
            [['personal_id', 'first_name', 'last_name', 'faculty', 'major', 'level'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'personal_id' => Yii::t('app', 'Personal ID'),
            'first_name'  => Yii::t('app', 'First Name'),
            'last_name'   => Yii::t('app', 'Last Name'),
            'faculty'     => Yii::t('app', 'Faculty'),
            'major'       => Yii::t('app', 'Major'),
            'level'       => Yii::t('app', 'Level'),
            'term'        => Yii::t('app', 'Term'),
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function search($params)
    {
        $query = $this->find();

        $this->load($params);
        
        if ($this->validate()) {
            $query->select(['a.*', 'b.major AS major', 'b.faculty AS faculty', 'c.name AS level']);
            $query->alias('a');
            $query->joinwith('education');
            $query->joinwith('level');
            $query->andFilterWhere(['a.term'   => $this->term]);
            $query->andWhere(['a.std_id' => null]);

            $query->andFilterWhere(['like', 'a.personal_id', str_replace('-', '', $this->personal_id)])
            ->andFilterWhere(['like', 'a.first_name', $this->first_name])
            ->andFilterWhere(['like', 'a.last_name', $this->last_name])
            ->andFilterWhere(['like', 'b.faculty', $this->faculty])
            ->andFilterWhere(['like', 'b.major', $this->major])
            ->andFilterWhere(['like', 'c.name', $this->level]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['term', 'personal_id' ,'first_name', 'last_name', 'faculty', 'major', 'level'],
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
     * @return \yii\db\Command
     */
    public function getTerms()
    {
        $query = Yii::$app->db->createCommand("SELECT term FROM {{%user_personal_info}} WHERE `std_id` IS NULL GROUP BY `term`;")->queryAll();

        return ArrayHelper::map($query, 'term', 'term');;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(UserEducation::className(), ['id' => 'id'])->alias('b');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(EduLevel::className(), ['id' => 'level'])->alias('c')->via('education');
    }
}
