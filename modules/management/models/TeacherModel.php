<?php
/**
 * @Final File
 */
namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class TeacherModel extends ActiveRecord
{
    /**
     * @property
     */
    public $fullname;
    public $firstname;
    public $lastname;
    public $bio = [];
    public $position;
    public $talent_type;
    public $talent_sub;
    public $password;
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
            [['username', 'email', 'password', 'firstname', 'lastname', 'position', 'talent_type', 'talent_sub'], 'required'],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['password'], 'string', 'length' => [8, 16]],
            [['email'], 'email'],
            [['firstname', 'lastname', 'position'], 'string', 'length' => [1, 255]],
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
            'firstname'     => Yii::t('app', 'Firstname'),
            'lastname'      => Yii::t('app', 'Lastname'),
            'position'      => Yii::t('app', 'Position'),
            'talent_type'   => Yii::t('app', 'Talent Type'),
            'talent_sub'    => Yii::t('app', 'Talent Sub'),
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function search($params = array())
    {
        $query = $this->find();
        $query->where(['id' => $this->getTeachers()]);

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
            
            $this->fullname = trim($this->firstname) . ' ' . trim($this->lastname);
            $bio = [
                'position'      => $this->position,
                'talent_type'   => $this->talent_type,
                'talent_sub'    => $this->talent_sub,
            ];


            Yii::$app->db->createCommand("INSERT INTO {{%user}} SET `username` = '" . $this->username. "', `email` = '" . $this->email . "', `password_hash` = '" . $password . "', `auth_key` = '" . $auth_key . "', `confirmed_at` = '" . $confirmed_at . "', `registration_ip` = '" . $registration_ip . "', `created_at` = '" . $created_at . "', `updated_at` = '" . $updated_at . "';")->execute();

            $user_id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand("INSERT INTO {{%profile}} SET `user_id` = " . (int)$user_id . ", `name` = '" . $this->fullname . "', `public_email` = '" . $this->email . "', `bio` = '" . json_encode($bio, JSON_UNESCAPED_UNICODE) . "';")->execute();

            Yii::$app->db->createCommand("INSERT INTO {{%auth_assignment}} SET `item_name` = 'Teacher', `user_id` = " . (int)$user_id . ", `created_at` = '" . time() . "';")->execute();

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
            
        $this->fullname = trim($this->firstname) . ' ' . trim($this->lastname);
        $bio = [
            'position'      => $this->position,
            'talent_type'   => $this->talent_type,
            'talent_sub'    => $this->talent_sub,
        ];

        Yii::$app->db->createCommand("UPDATE {{%user}} SET `username` = '" . $this->username. "', `email` = '" . $this->email . "', `password_hash` = '" . $password . "', `auth_key` = '" . $auth_key . "', `registration_ip` = '" . $registration_ip . "', `updated_at` = '" . $updated_at . "' WHERE `id` = " . (int)$this->id . ";")->execute();

        Yii::$app->db->createCommand("UPDATE {{%profile}} SET `name` = '" . $this->fullname . "', `public_email` = '" . $this->email . "', `bio` = '" . json_encode($bio, JSON_UNESCAPED_UNICODE) . "' WHERE `user_id` = " . (int)$this->id . ";")->execute();

        return true;
    }

    /**
     * @return \yii\db\Command
     */
    public function delete()
    {
        Yii::$app->db->createCommand("DELETE FROM {{%user}} WHERE `id` = '" . (int)$this->id. "';")->execute();
        Yii::$app->db->createCommand("DELETE FROM {{%profile}} WHERE `user_id` = '" . (int)$this->id. "';")->execute();
        Yii::$app->db->createCommand("DELETE FROM {{%auth_assignment}} WHERE `user_id` = '" . (int)$this->id. "';")->execute();
    }

    /**
     * @return \yii\db\Command
     */
    public function getTeachers()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%auth_assignment}} WHERE `item_name` = 'Teacher';")->queryAll();
        
        return ArrayHelper::getColumn($query, 'user_id');
    }

    /**
     * @return \yii\db\Command
     */
    public function getTalentType()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%talent_type}};")->queryAll();

        return ArrayHelper::map($query, 'name', 'name');
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
    public function setProfile()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%profile}} WHERE `user_id` = '" . (int)$this->id. "';")->queryOne();
        
        $tmpName = explode(' ', $query['name']);
        $this->firstname = trim($tmpName[0]);
        $this->lastname  = trim($tmpName[1]);

        $tmpBio = json_decode($query['bio'], true);
        $this->position    = $tmpBio['position'];
        $this->talent_type = $tmpBio['talent_type'];
        $this->talent_sub  = $tmpBio['talent_sub'];
    }
}
