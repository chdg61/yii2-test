<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['email', 'password', 'create'], 'safe'],
            [['confirmed'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'email'     => 'Email',
            'password'  => 'Пароль',
            'create'    => 'Дата регистрации',
            'confirmed' => 'Подтверждён',
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
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->create){
            $date = \DateTime::createFromFormat('d.m.Y',$this->create);

            $date->setTime(0,0,0);
            $query->andFilterWhere([
                '>=', 'create', $date->format('d-m-Y H:i:s'),
            ]);

            $date->setTime(23,59,59);
            $query->andFilterWhere([
                '<=', 'create', $date->format('d-m-Y H:i:s'),
            ]);
        }

        $query->andFilterWhere([
            'id'        => $this->id,
            'confirmed' => $this->confirmed,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

    /**
     * @param string $email
     *
     * @return null|User
     */
    public static function findUserByEmail($email)
    {
        return User::findOne(['email' => $email]);
    }
}
