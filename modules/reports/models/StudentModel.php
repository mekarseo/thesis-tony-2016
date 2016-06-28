<?php

namespace app\modules\reports\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\management\models\CsvList;
use app\modules\management\models\UserTalent;
use app\modules\reports\models\StudentMap;
use app\modules\account\models\GradeModel;
use app\modules\reports\models\ActivityModel;
use app\modules\reports\models\ActivityHistory;

class StudentModel extends CsvList
{
    /**
     * @property
     */
    public $talent;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term'], 'string', 'max' => 6],
            [['mobile'], 'string', 'max' => 10],
            [['email'], 'string'],
            [['std_id', 'first_name', 'last_name', 'faculty', 'major', 'level', 'mobile', 'email', 'talent'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'std_id'      => Yii::t('app', 'Student ID'),
            'personal_id' => Yii::t('app', 'Personal ID'),
            'first_name'  => Yii::t('app', 'First Name'),
            'last_name'   => Yii::t('app', 'Last Name'),
            'faculty'     => Yii::t('app', 'Faculty'),
            'major'       => Yii::t('app', 'Major'),
            'level'       => Yii::t('app', 'Level'),
            'term'        => Yii::t('app', 'Term'),
            'mobile'      => Yii::t('app', 'Mobile'),
            'email'       => Yii::t('app', 'Email'),
            'talent'      => Yii::t('app', 'Talent'),
            'birth_date'  => Yii::t('app', 'Birth Date'),
            'father'      => Yii::t('app', 'Father'),
            'father_mobile' => Yii::t('app', 'Father Mobile'),
            'mother'      => Yii::t('app', 'Mother'),
            'mother_mobile' => Yii::t('app', 'Mother Mobile'),
            'parent'      => Yii::t('app', 'Parent'),
            'parent_mobile' => Yii::t('app', 'Parent Mobile'),
            'parent_address' => Yii::t('app', 'Parent Address'),
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
            $query->joinwith(['education', 'level', 'talent0']);
            $query->andFilterWhere(['a.term'   => $this->term]);
            $query->andWhere(['!=', 'a.std_id', 'NULL']);

            $query->andFilterWhere(['like', 'a.std_id', $this->std_id])
            ->andFilterWhere(['like', 'a.first_name', $this->first_name])
            ->andFilterWhere(['like', 'a.last_name', $this->last_name])
            ->andFilterWhere(['like', 'b.faculty', $this->faculty])
            ->andFilterWhere(['like', 'b.major', $this->major])
            ->andFilterWhere(['like', 'c.name', $this->level])
            ->andFilterWhere(['like', 'a.mobile', $this->mobile])
            ->andFilterWhere(['like', 'c.email', $this->email])
            ->andFilterWhere(['or', ['like', 'd.talent_type', $this->talent], ['like', 'd.talent_sub', $this->talent], ['like', 'd.talent_detail', $this->talent]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['std_id', 'first_name', 'last_name', 'faculty', 'major', 'level'],
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
    public function getTalent0()
    {
        return $this->hasOne(UserTalent::className(), ['id' => 'id'])->alias('d');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTalent1()
    {
        return $this->hasOne(UserTalent::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMap()
    {
        return $this->hasOne(StudentMap::className(), ['csv_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrade()
    {
        $query = $this->hasMany(GradeModel::className(), ['student_id' => 'user_id'])->via('map');

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
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        $query = $this->hasMany(ActivityModel::className(), ['student_id' => 'user_id'])->via('map');
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
    public function getTerms()
    {
        $query = Yii::$app->db->createCommand("SELECT term FROM {{%user_personal_info}} WHERE `std_id` IS NOT NULL GROUP BY `term`;")->queryAll();

        return ArrayHelper::map($query, 'term', 'term');
    }

    /**
     * @return \yii\db\Command
     */
    public function getTalentSubs()
    {
        $query = Yii::$app->db->createCommand("SELECT b.`talent_sub` as talent FROM {{%user_personal_info}} as a LEFT JOIN {{%user_talent_info}} as b ON b.`id` = a.`id` WHERE a.`std_id` IS NOT NULL GROUP BY b.`talent_sub`;")->queryAll();

        return ArrayHelper::map($query, 'talent', 'talent');
    }
}
