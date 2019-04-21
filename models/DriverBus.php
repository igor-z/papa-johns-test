<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "driver_bus".
 *
 * @property int $id
 * @property int $driver_id
 * @property int $bus_id
 * @property string $name
 *
 * @property Bus $bus
 * @property Driver $driver
 */
class DriverBus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver_bus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'bus_id'], 'required'],
            [['driver_id', 'bus_id'], 'integer'],
            [['bus_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bus::class, 'targetAttribute' => ['bus_id' => 'id']],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::class, 'targetAttribute' => ['driver_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'driver_id' => 'Driver ID',
            'bus_id' => 'Bus ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(Bus::class, ['id' => 'bus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(Driver::class, ['id' => 'driver_id']);
    }
}
