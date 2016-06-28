<?php
/**
 * @Final File
 */
namespace app\modules\apn\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class StaffModel extends ActiveRecord
{
    /**
     * @property
     */
    public $fullname;
    public $firstname;
    public $lastname;
    public $bio = [];
    public $position;
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
            [['username', 'email', 'password', 'firstname', 'lastname', 'position'], 'required'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function search()
    {
        $query = $this->find();
        $query->where(['id' => $this->getStaffs()]);

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
            $bio['position'] = $this->position;

            Yii::$app->db->createCommand("INSERT INTO {{%user}} SET `username` = '" . $this->username. "', `email` = '" . $this->email . "', `password_hash` = '" . $password . "', `auth_key` = '" . $auth_key . "', `confirmed_at` = '" . $confirmed_at . "', `registration_ip` = '" . $registration_ip . "', `created_at` = '" . $created_at . "', `updated_at` = '" . $updated_at . "';")->execute();

            $user_id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand("INSERT INTO {{%profile}} SET `user_id` = " . (int)$user_id . ", `name` = '" . $this->fullname . "', `public_email` = '" . $this->email . "', `bio` = '" . json_encode($bio, JSON_UNESCAPED_UNICODE) . "';")->execute();

            Yii::$app->db->createCommand("INSERT INTO {{%auth_assignment}} SET `item_name` = 'Staff', `user_id` = " . (int)$user_id . ", `created_at` = '" . time() . "';")->execute();

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
        $bio['position'] = $this->position;

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
    public function getStaffs()
    {
        $query = Yii::$app->db->createCommand("SELECT * FROM {{%auth_assignment}} WHERE `item_name` = 'Staff';")->queryAll();
        
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
        $this->position = $tmpBio['position'];
    }
}
