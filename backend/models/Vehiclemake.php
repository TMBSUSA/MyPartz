<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "vehicle_make".
 *
 * @property integer $MakeId
 * @property string $MakeName
 */
class Vehiclemake extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_make';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MakeName'], 'required'],
            [['MakeName'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MakeId' => 'Make ID',
            'MakeName' => 'Make Name',
        ];
    }
}
