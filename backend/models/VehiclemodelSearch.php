<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Vehiclemodel;

/**
 * VehiclemodelSearch represents the model behind the search form about `backend\models\Vehiclemodel`.
 */
class VehiclemodelSearch extends Vehiclemodel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ModelId'], 'integer'],
            [['MakeId', 'ModelName'], 'safe'],
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
        $query = Vehiclemodel::find();

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

		$query->joinWith('make');
		
		$query->andFilterWhere(['like', 'MakeName', $this->MakeId]);
        
		// grid filtering conditions
        $query->andFilterWhere([
            'ModelId' => $this->ModelId,
        ]);

        $query->andFilterWhere(['like', 'ModelName', $this->ModelName]);

        return $dataProvider;
    }
}
