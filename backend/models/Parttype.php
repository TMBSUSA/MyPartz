<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "part_type".
 *
 * @property integer $part_type_id
 * @property string $part_type_name
 */
class Parttype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_type_name'], 'required'],
            [['part_type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'part_type_id' => 'Part Type ID',
            'part_type_name' => 'Part Type Name',
        ];
    }
}
