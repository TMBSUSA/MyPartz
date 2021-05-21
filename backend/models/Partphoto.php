<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "part_photo".
 *
 * @property integer $PhotoId
 * @property integer $PartId
 * @property string $PhotoUrl
 *
 * @property PartDetail $part
 */
class Partphoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PartId', 'PhotoUrl'], 'required'],
            [['PartId'], 'integer'],
            [['PhotoUrl'], 'string', 'max' => 100],
            [['PartId'], 'exist', 'skipOnError' => true, 'targetClass' => PartDetail::className(), 'targetAttribute' => ['PartId' => 'PartId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PhotoId' => 'Photo ID',
            'PartId' => 'Part ID',
            'PhotoUrl' => 'Photo Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPart()
    {
        return $this->hasOne(PartDetail::className(), ['PartId' => 'PartId']);
    }
}
