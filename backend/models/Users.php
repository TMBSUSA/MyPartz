<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "seller".
 *
 * @property integer $UserId
 * @property string $UserName
 * @property string $Email
 * @property string $Password
 * @property string $Mobile
 * @property string $ProfilePic
 * @property string $FirstName
 * @property string $LastName
 * @property string $Gender
 * @property string $LogInStatus
 * @property string $LastLogin
 * @property string $ResetToken
 * @property string $AccessToken
 * @property string $Status
 * @property string $CreatedOn
 * @property string $UpdatedOn
 *
 * @property Devices[] $devices
 * @property PartDetail[] $partDetails
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seller';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserName', 'Email', 'Mobile', 'Status'], 'required'],
            [['Status'], 'string'],
            [['Email'], 'string', 'max' => 255],
            [['Mobile'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'UserId' => 'User ID',
            'UserName' => 'User Name',
            'Email' => 'Email',
            'Password' => 'Password',
            'Mobile' => 'Mobile',
            'ProfilePic' => 'Profile Pic',
            'FirstName' => 'First Name',
            'LastName' => 'Last Name',
            'Gender' => 'Gender',
            'LogInStatus' => 'Log In Status',
            'LastLogin' => 'Last Login',
            'ResetToken' => 'Reset Token',
            'AccessToken' => 'Access Token',
            'Status' => 'Status',
            'CreatedOn' => 'Created On',
            'UpdatedOn' => 'Updated On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Devices::className(), ['UserId' => 'UserId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartDetails()
    {
        return $this->hasMany(Partdetail::className(), ['UserId' => 'UserId']);
    }
}
