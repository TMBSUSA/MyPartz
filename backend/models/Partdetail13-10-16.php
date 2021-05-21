<?php
namespace backend\models;
use Yii;
/**
 * This is the model class for table "part_detail".
 *
 * @property integer $PartId
 * @property string $PartName
 * @property string $PartDetails
 * @property string $PartCondition
 * @property string $PartMfgYear
 * @property integer $UserId
 * @property integer $PartTypeId
 * @property integer $VehiclemakeId
 * @property integer $VehicleModelId
 * @property string $Lat
 * @property string $Lng
 * @property string $Location
 * @property string $Status
 * @property string $CreatedOn
 * @property string $UpdatedOn
 *
 * @property VehicleMake $vehiclemake
 * @property PartType $partType
 * @property VehicleModel $vehicleModel
 * @property Seller $user
 */
class Partdetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_detail';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PartName', 'PartDetails', 'PartCondition', 'PartMfgYear', 'PartPrice', 'UserId', 'PartTypeId', 'VehiclemakeId', 'VehicleModelId', 'Location', 'Status'], 'required'],
            [['PartName', 'PartDetails', 'PartCondition', 'Status'], 'string'],
            [['UserId', 'PartTypeId', 'VehiclemakeId', 'VehicleModelId'], 'integer'],
            [['CreatedOn', 'UpdatedOn'], 'safe'],
            [['PartMfgYear'], 'string', 'max' => 4],
            [['Lat', 'Lng'], 'string', 'max' => 100],
            [['Location'], 'string', 'max' => 255],
            [['VehiclemakeId'], 'exist', 'skipOnError' => true, 'targetClass' => Vehiclemake::className(), 'targetAttribute' => ['VehiclemakeId' => 'MakeId']],
            [['PartTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => Parttype::className(), 'targetAttribute' => ['PartTypeId' => 'part_type_id']],
            [['VehicleModelId'], 'exist', 'skipOnError' => true, 'targetClass' => Vehiclemodel::className(), 'targetAttribute' => ['VehicleModelId' => 'ModelId']],
            [['UserId'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::className(), 'targetAttribute' => ['UserId' => 'UserId']],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PartId' => 'Part ID',
            'PartName' => 'Part Name',
            'PartDetails' => 'Part Details',
            'PartCondition' => 'Part Condition',
            'PartMfgYear' => 'Part Mfg Year',
			'PartPrice' => 'Part Price',
            'UserId' => 'User Name',
            'PartTypeId' => 'Part Type',
            'VehiclemakeId' => 'Vehicle Make',
            'VehicleModelId' => 'Vehicle Model',
            'Lat' => 'Lat',
            'Lng' => 'Lng',
            'Location' => 'Location',
            'Status' => 'Status',
            'CreatedOn' => 'Created On',
            'UpdatedOn' => 'Updated On',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehiclemake()
    {
        return $this->hasOne(Vehiclemake::className(), ['MakeId' => 'VehiclemakeId']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartType()
    {
        return $this->hasOne(Parttype::className(), ['part_type_id' => 'PartTypeId']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleModel()
    {
        return $this->hasOne(Vehiclemodel::className(), ['ModelId' => 'VehicleModelId']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Seller::className(), ['UserId' => 'UserId']);
    }
	public function upload($id,$url)
    {
        $connection->createCommand()
				   ->insert('part_photo', [
											'PartId' => $id,
											'PhotoUrl' => $url,
										] )->execute();
    }
}
