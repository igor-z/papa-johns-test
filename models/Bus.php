<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bus".
 *
 * @property int $id
 * @property string $name
 * @property int $avg_speed
 *
 * @property DriverBus[] $driverBuses
 */
class Bus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'avg_speed'], 'required'],
            [['avg_speed'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'avg_speed' => 'Avg Speed',
        ];
    }

    public function fields()
    {
        return [
            'name',
            'avg_speed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriverBuses()
    {
        return $this->hasMany(DriverBus::class, ['bus_id' => 'id']);
    }
}
