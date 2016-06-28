<?php
/**
 * @Final File
 */
namespace app\modules\account\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class GradeModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%grade_student}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'grade', 'term'], 'required'],
            [['student_id'], 'integer'],
            [['term'], 'string', 'max' => 6],
            [['grade'], 'number', 'min' => 0, 'max' => 4.00],
            [['create_at'], 'string'],
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
            'grade' => Yii::t('app', 'GPA'),
            'term' => Yii::t('app', 'Term'),
            'create_at' => Yii::t('app', 'Create At'),
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
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
     * @return array
     */
    public function getTerm()
    {
        if( date('n') < 6) {
            $year = date('Y', strtotime('-1 years'));
        } else {
            $year = date('Y');
        }

        $data = [$year.'/1' => $year.'/1', $year.'/2' => $year.'/2'];
        return $data;
    }

    /**
     * @return term duplicate
     */
    public function termCheck()
    {
        $result = Yii::$app->db->createCommand("SELECT * FROM {{%grade_student}} WHERE `student_id` = '" . (int)$this->student_id. "' AND `term` = '" . $this->term . "';")->queryOne();
        if (!$result)
            return true;
        
        return false;
    }

    /**
     * @return \yii\db\Command
     */
    public function create()
    {
        Yii::$app->db->createCommand("INSERT INTO {{%grade_student}} SET `student_id` = " . (int)$this->student_id . ", `grade` = '" . $this->grade . "', `term` = '" . $this->term . "', `create_at` = '" . date('Y-m-d H:i:s') . "';")->execute();
        
        return true;
    }
}
