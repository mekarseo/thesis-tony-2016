<?php

namespace app\modules\management\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class SerialCode extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%serial_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial'], 'string', 'max' => 13],
            [['create_time', 'expire_time'], 'string', 'max' => 20],
            [['status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serial' => Yii::t('app', 'Serial'),
            'create_time' => Yii::t('app', 'Create Time'),
            'expire_time' => Yii::t('app', 'Expire Time'),
            'status' => Yii::t('app', 'Status'),
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
            $query->andFilterWhere(['status' => $this->status]);
            $query->andFilterWhere(['like', 'serial', $this->serial]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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

    public function generateSerial($require = 1)
    {   
        $length = 13;
        $create_time = (string)time();
        $expire_time = (string)strtotime('+7 days');
        $status = 'available';
        $str    = '123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#$%&*?';
        $chars  = str_split($str);
        $serial = [];
        
        while (count($serial) < $require) {
            $tmp = '';
            for ($j = 1; $j <= $length; $j++) { 
                $tmp .= $chars[rand(0, (count($chars)-1))];
            }

            if ($this->find()->where(['serial' => $tmp])->count() == 0) {
                $model = new SerialCode;
                $model->serial = $tmp;
                $model->create_time = $create_time;
                $model->expire_time = $expire_time;
                $model->status = $status;
                $model->insert();

                $serial[] = [
                    'serial' => $tmp,
                    'create_time' => $create_time,
                    'expire_time' => $expire_time,
                    'status' => $status,
                ];
            }
        }

        $provider = new ArrayDataProvider([
            'allModels' => $serial,
            'sort' => [
                'attributes' => ['serial', 'create_time', 'expire_time', 'status'],
            ],
            'pagination' => false
        ]);

        return $provider;
    }
}
