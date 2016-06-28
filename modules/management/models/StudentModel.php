<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\management\models\StudentMap;
use app\modules\management\models\StudentPersonal;
use app\modules\management\models\StudentEducation;
use app\modules\management\models\StudentTalent;

class StudentModel extends ActiveRecord
{
    /**
     * @property
     */
    public $std_id;
    public $fullname;
    public $firstname;
    public $lastname;
    public $password;
    public $csv_id;
    public $bio = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'firstname', 'lastname', 'std_id'], 'required'],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['password'], 'string', 'length' => [8, 16]],
            [['email'], 'email'],
            [['firstname', 'lastname'], 'string', 'length' => [1, 255]],
            [['std_id'], 'string', 'length' => 13],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'      => Yii::t('app', 'Username'),
            'email'         => Yii::t('app', 'Email'),
            'password'      => Yii::t('app', 'Password'),
            'firstname'    => Yii::t('app', 'Firstname'),
            'lastname'     => Yii::t('app', 'Lastname'),
            'std_id'        => Yii::t('app', 'Student ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function search()
    {
        $query = $this->find();
        $query->where(['id' => $this->getStudents()]);

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
    public function create()
    {
        $query = $this->find()->where(['username' => $this->username, 'email' => $this->email])->one();
        if ($query == null) {
            $password = Yii::$app->security->generatePasswordHash($this->password, Yii::$app->getModule('user')->cost);
            $auth_key = Yii::$app->security->generateRandomString();
            $confirmed_at = Yii::$app->getModule('user')->enableConfirmation ? null : time();
            $registration_ip = Yii::$app->request->userIP;
            $created_at = time();
            $updated_at = time();
            $bio['std_id'] = $this->std_id;
            $this->fullname = trim($this->firstname) . ' ' . trim($this->lastname);

            Yii::$app->db->createCommand("INSERT INTO {{%user}} SET `username` = '" . $this->username. "', `email` = '" . $this->email . "', `password_hash` = '" . $password . "', `auth_key` = '" . $auth_key . "', `confirmed_at` = '" . $confirmed_at . "', `registration_ip` = '" . $registration_ip . "', `created_at` = '" . $created_at . "', `updated_at` = '" . $updated_at . "';")->execute();

            $user_id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand("INSERT INTO {{%profile}} SET `user_id` = " . (int)$user_id . ", `name` = '" . $this->fullname . "', `public_email` = '" . $this->email . "', `bio` = '" . json_encode($bio, JSON_UNESCAPED_UNICODE) . "';")->execute();
            Yii::$app->db->createCommand("INSERT INTO {{%auth_assignment}} SET `item_name` = 'Student', `user_id` = " . (int)$user_id . ", `created_at` = '" . time() . "';")->execute();
            Yii::$app->db->createCommand("INSERT INTO {{%user_student_map}} SET `user_id` = " . (int)$user_id . ", `csv_id` = " . (int)$this->csv_id . ";")->execute();
            Yii::$app->db->createCommand("UPDATE {{%user_personal_info}} SET `std_id` = '" . $this->std_id . "' WHERE `id` = " . (int)$this->csv_id . ";")->execute();

            return true;
        }
    }

    /**
     * @return \yii\db\Command
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        $password = Yii::$app->security->generatePasswordHash($this->password, Yii::$app->getModule('user')->cost);
        $auth_key = Yii::$app->security->generateRandomString();
        $registration_ip = Yii::$app->request->userIP;
        $updated_at = time();
        $bio['std_id'] = $this->std_id;
        $this->fullname = trim($this->firstname) . ' ' . trim($this->lastname);

        Yii::$app->db->createCommand("UPDATE {{%user}} SET `username` = '" . $this->username. "', `email` = '" . $this->email . "', `password_hash` = '" . $password . "', `auth_key` = '" . $auth_key . "', `registration_ip` = '" . $registration_ip . "', `updated_at` = '" . $updated_at . "' WHERE `id` = " . (int)$this->id . ";")->execute();
        Yii::$app->db->createCommand("UPDATE {{%profile}} SET `name` = '" . $this->fullname . "', `public_email` = '" . $this->email . "', `bio` = '" . json_encode($bio, JSON_UNESCAPED_UNICODE) . "' WHERE `user_id` = " . (int)$this->id . ";")->execute();
        Yii::$app->db->createCommand("UPDATE {{%user_personal_info}} SET `std_id` = '" . $this->std_id . "' WHERE `id` = " . (int)$this->map->csv_id . ";")->execute();

        return true;
    }

    /**
     * @return \yii\db\Command
     */
    public function delete()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%user_student_map}} WHERE `user_id` = '" . (int)$this->id. "';")->queryOne();

        $csv_id = $query['csv_id'];

        Yii::$app->db->createCommand("DELETE FROM {{%user}} WHERE `id` = '" . (int)$this->id. "';")->execute();
        Yii::$app->db->createCommand("DELETE FROM {{%profile}} WHERE `user_id` = '" . (int)$this->id. "';")->execute();
        Yii::$app->db->createCommand("DELETE FROM {{%auth_assignment}} WHERE `user_id` = '" . (int)$this->id. "';")->execute();
        Yii::$app->db->createCommand("DELETE FROM {{%user_student_map}} WHERE `user_id` = '" . (int)$this->id. "';")->execute();
        Yii::$app->db->createCommand("UPDATE {{%user_personal_info}} SET `std_id` = NULL WHERE `id` = '" . (int)$csv_id. "';")->execute();
    }

    /**
     * @return \yii\db\Command
     */
    public function getStudents()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%auth_assignment}} WHERE `item_name` = 'Student';")->queryAll();
        
        return ArrayHelper::getColumn($query, 'user_id');
    }

    /**
     * @return \yii\db\Command
     */
    public function setProfile()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%profile}} WHERE `user_id` = '" . (int)$this->id. "';")->queryOne();
        
        $tmpName = explode(' ', $query['name']);
        $this->firstname = trim($tmpName[0]);
        $this->lastname  = trim($tmpName[1]);

        $tmpBio = json_decode($query['bio'], true);
        $this->std_id = $tmpBio['std_id'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMap()
    {   
        return StudentMap::findOne($this->id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonal()
    {   
        if($this->id) {
            $this->csv_id = $this->map->csv_id;
        }
        
        return StudentPersonal::findOne($this->csv_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {   
        return StudentEducation::findOne($this->csv_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTalent()
    {   
        return StudentTalent::findOne($this->csv_id);
    }
}
