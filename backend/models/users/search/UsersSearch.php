<?php

namespace backend\models\users\search;

use backend\models\users\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * ManageUsersSearch represents the model behind the search form about `backend\models\users\search\UsersSearch`.
 */
class UsersSearch extends User
{
    public $create_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['create_date'], 'date', 'format'=>'dd.MM.yyyy'],
            [['username', 'email'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

       /* if(($createDate = \DateTime::createFromFormat('d.m.Y',$this->create_date, new \DateTimeZone('UTC')))!==false)
        {
            $query->andOnCondition("FROM_UNIXTIME(".static::tableName().".created_at,'%d.%m.%Y') = :create_date",[':create_date'=>$createDate->format('d.m.Y')]);
        }*/

        $createDate = \DateTime::createFromFormat('d.m.Y',$this->create_date);
        $errors = \DateTime::getLastErrors();

        //If NO DateTime Errors
        if (empty($errors['warning_count']) && $createDate!==false) {
            $createDate->setTime(0,0,0);
            // set lowest date value
            $unixDateStart = $createDate->getTimeStamp();

            // add 1 day and subtract 1 second
            $createDate->add(new \DateInterval('P1D'));
            $createDate->sub(new \DateInterval('PT1S'));

            // set highest date value
            $unixDateEnd = $createDate->getTimeStamp();

            $query->andFilterWhere(['between', static::tableName().'.created_at', $unixDateStart, $unixDateEnd]);
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
           // ->andFilterWhere(['like', 'auth_key', $this->auth_key])
           // ->andFilterWhere(['like', 'password_hash', $this->password_hash])
           // ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
