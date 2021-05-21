<?php



namespace backend\models;



use Yii;



/**

 * This is the model class for table "vehicle_model".

 *

 * @property integer $ModelId

 * @property string $ModelName

 * @property integer $MakeId

 *

 * @property VehicleMake $make

 */

class Vehiclemodel extends \yii\db\ActiveRecord

{

    /**

     * @inheritdoc

     */

    public static function tableName()

    {

        return 'vehicle_model';

    }



    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            [['ModelName', 'MakeId'], 'required'],

            [['MakeId'], 'integer'],

            [['ModelName'], 'string', 'max' => 255],

            [['MakeId'], 'exist', 'skipOnError' => true, 'targetClass' => Vehiclemake::className(), 'targetAttribute' => ['MakeId' => 'MakeId']],

        ];

    }



    /**

     * @inheritdoc

     */

    public function attributeLabels()

    {

        return [

            'ModelId' => 'Model ID',

            'ModelName' => 'Model Name',

            'MakeId' => 'Vehicle Make Name',

        ];

    }



    /**

     * @return \yii\db\ActiveQuery

     */

    public function getMake()

    {

        return $this->hasOne(Vehiclemake::className(), ['MakeId' => 'MakeId']);

    }

}

