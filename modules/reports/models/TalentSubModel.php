<?php
/**
 * @Final File
 */
namespace app\modules\reports\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use app\modules\management\models\UserTalent;
use app\modules\management\models\UserEducation;

class TalentSubModel extends ActiveRecord
{
    public $counter;
    public $faculty;
    public $talent_sub;
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'std_id' => Yii::t('app', 'Std ID'),
            'personal_id' => Yii::t('app', 'Personal ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'mobile' => Yii::t('app', 'Mobile'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'father' => Yii::t('app', 'Father'),
            'father_mobile' => Yii::t('app', 'Father Mobile'),
            'mother' => Yii::t('app', 'Mother'),
            'mother_mobile' => Yii::t('app', 'Mother Mobile'),
            'parent' => Yii::t('app', 'Parent'),
            'parent_mobile' => Yii::t('app', 'Parent Mobile'),
            'parent_address' => Yii::t('app', 'Parent Address'),
            'term' => Yii::t('app', 'Term'),
        ];
    }

    public function search($params)
    {
        $params = (object)$params;
        
        $query = $this->find();
        $query->alias('a');
        $query->select(['COUNT(*) as counter', 'b.faculty as faculty', 'c.talent_sub as talent_sub']);
        $query->joinWith(['education', 'talent']);
        $query->andFilterWhere(['like','a.term', $this->term]);
        $query->where(['IS NOT','a.std_id', NULL]);
        $query->groupBY(['c.talent_sub', 'b.faculty']);
        $query->orderBY(['c.talent_sub' => SORT_ASC, 'b.faculty' => SORT_ASC]);
        return $query;
    }

    public function getPagers($model)
    {
        $pages = new Pagination(['pageSize' => 20, 'totalCount' => $model->count()]);

        return $pages;
    }

    public function getHeadColumn($model)
    {
        $column = array();
        foreach ($model->all() as $tmp) {
            if (!in_array($tmp->faculty, $column)) {
                array_push($column, $tmp->faculty);
            }
        }

        return $column;
    }

    public function getRow($model)
    {
        $column = array();
        foreach ($model->all() as $tmp) {
            $head_tmp = array();
            foreach ($this->getHeadColumn($model) as $head_column) {
                $head_tmp[$head_column] = 0;
            }
            $column[$tmp->talent_sub] = $head_tmp;
        }

        foreach ($model->all() as $tmp) {
            $column[$tmp->talent_sub][$tmp->faculty] = $tmp->counter;
        }

        return (object)$column;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerms()
    {
        $query = Yii::$app->db->createCommand("SELECT term FROM {{%user_personal_info}} WHERE `std_id` IS NOT NULL GROUP BY `term`;")->queryAll();
        
        $data = array();
        
        foreach($query as $a) {
            $tmp = explode('/', $a['term']);
            $data[$tmp[0]] = $tmp[0] + 543;
        }

        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(UserEducation::className(), ['id' => 'id'])->alias('b')->where(['!=','b.faculty', '']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTalent()
    {
        return $this->hasOne(UserTalent::className(), ['id' => 'id'])->alias('c')->where(['!=','c.talent_type', ''])->orWhere(['!=', 'c.talent_sub', '']);
    }
}
