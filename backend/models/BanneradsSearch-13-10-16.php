<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bannerads;

/**
 * BanneradsSearch represents the model behind the search form about `backend\models\Bannerads`.
 */
class BanneradsSearch extends Bannerads
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BannerID'], 'integer'],
            [['ImageURL', 'ExternalURL', 'Screen'], 'safe'],
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
        $query = Bannerads::find();

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
            'BannerID' => $this->BannerID,
        ]);

        $query->andFilterWhere(['like', 'ImageURL', $this->ImageURL])
            ->andFilterWhere(['like', 'ExternalURL', $this->ExternalURL])
            ->andFilterWhere(['like', 'Screen', $this->Screen]);

        return $dataProvider;
    }
}
