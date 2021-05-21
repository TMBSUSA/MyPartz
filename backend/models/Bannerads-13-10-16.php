<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;
/**
 * This is the model class for table "banner_ads".
 *
 * @property integer $BannerID
 * @property string $ImageURL
 * @property string $ExternalURL
 * @property string $Screen
 */
class Bannerads extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner_ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ExternalURL', 'Screen'], 'required'],
			['ExternalURL', 'url', 'defaultScheme' => 'http'],
            [['ExternalURL', 'Screen'], 'string'],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'BannerID' => 'Banner ID',
            'ImageURL' => 'Image',
            'ExternalURL' => 'External Url',
            'Screen' => 'Screen',
        ];
    }
	public function upload()
    {	
		$imgname = uniqid().".".$this->ImageURL->extension;
		if ($this->validate()) {
            $this->ImageURL->saveAs('uploads/' . $imgname);
            return $imgname;
        } else {
            return false;
        }
    }
}
