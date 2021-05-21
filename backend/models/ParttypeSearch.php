<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Parttype;

/**
 * ParttypeSearch represents the model behind the search form about `backend\models\Parttype`.
 */
class ParttypeSearch extends Parttype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_type_id'], 'integer'],
            [['part_type_name'], 'safe'],
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
        $query = Parttype::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'part_type_id' => $this->part_type_id,
        ]);

        $query->andFilterWhere(['like', 'part_type_name', $this->part_type_name]);

        return $dataProvider;
    }
}
