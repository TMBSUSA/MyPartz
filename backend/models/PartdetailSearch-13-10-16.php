<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Partdetail;

/**
 * PartdetailSearch represents the model behind the search form about `backend\models\Partdetail`.
 */
class PartdetailSearch extends Partdetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PartId'], 'integer'],
            [['UserId', 'PartTypeId', 'VehiclemakeId', 'VehicleModelId', 'PartName', 'PartDetails', 'PartCondition', 'PartMfgYear', 'Lat', 'Lng', 'Location', 'Status', 'CreatedOn', 'UpdatedOn'], 'safe'],
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
        $query = Partdetail::find();

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
            'PartId' => $this->PartId,
            //'UserId' => $this->UserId,
            //'PartTypeId' => $this->PartTypeId,
            //'VehiclemakeId' => $this->VehiclemakeId,
            //'VehicleModelId' => $this->VehicleModelId,
            'CreatedOn' => $this->CreatedOn,
            'UpdatedOn' => $this->UpdatedOn,
        ]);
		
		$query->joinWith('partType');
		$query->joinWith('vehiclemake');
		$query->joinWith('vehicleModel');
		$query->joinWith('user');
		
		$query->andFilterWhere(['like', 'part_type_name', $this->PartTypeId]);
		$query->andFilterWhere(['like', 'MakeName', $this->VehiclemakeId]);
		$query->andFilterWhere(['like', 'ModelName', $this->VehicleModelId]);
		$query->andFilterWhere(['UserName' => $this->UserId]);

        $query->andFilterWhere(['like', 'PartName', $this->PartName])
            ->andFilterWhere(['like', 'PartDetails', $this->PartDetails])
            ->andFilterWhere(['like', 'PartCondition', $this->PartCondition])
            ->andFilterWhere(['like', 'PartMfgYear', $this->PartMfgYear])
           	->andFilterWhere(['like', 'Location', $this->Location])
            ->andFilterWhere(['like', 'Status', $this->Status]);

        return $dataProvider;
    }
}
