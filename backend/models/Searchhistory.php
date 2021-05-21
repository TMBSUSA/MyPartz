<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "search_history".
 *
 * @property integer $SearchId
 * @property string $SearchKey
 * @property integer $TotalCount
 * @property string $LastSearchTime
 */
class Searchhistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'search_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SearchKey', 'TotalCount', 'LastSearchTime'], 'required'],
            [['TotalCount'], 'integer'],
            [['LastSearchTime'], 'safe'],
            [['SearchKey'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SearchId' => 'Search ID',
            'SearchKey' => 'Search Key',
            'TotalCount' => 'Total Count',
            'LastSearchTime' => 'Last Search Time',
        ];
    }
}
