<?php
/**
 * @Final File
 */
namespace app\modules\reports\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\reports\models\ProfileModel;
use app\modules\management\models\TeacherModel;

class TeacherSearch extends TeacherModel
{
	/**
     * @property
     */
    public $name;
    public $talent_type;
    public $talent_sub;

	/**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['username', 'email', 'name', 'position', 'talent_type', 'talent_sub'], 'safe'],
		];
	}

	public function search($params = array())
	{
		$query = $this->find();

		$this->load($params);

		$query->alias('a');
		$query->select(['a.*', 'b.name AS name', 'b.bio AS position', 'b.bio AS talent_type', 'b.bio AS talent_sub']);
		$query->joinWith(['profile']);
		$query->where(['a.id' => $this->teachers]);
        $query->andFilterWhere(['like', 'b.name', $this->name])
        	->andFilterWhere(['like', 'a.username', $this->username])
        	->andFilterWhere(['like', 'b.bio', '"position":"'.$this->position])
        	->andFilterWhere(['like', 'b.bio', '"talent_type":"'.$this->talent_type])
        	->andFilterWhere(['like', 'b.bio', '"talent_sub":"'.$this->talent_sub])
        	->andFilterWhere(['like', 'a.email', $this->email]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
            	'attributes' => ['username', 'email', 'name', 'position', 'talent_type', 'talent_sub', 'created_at'],
            ],
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
    public function getProfile()
    {
        return $this->hasOne(ProfileModel::className(), ['user_id' => 'id'])->alias('b');
    }
}