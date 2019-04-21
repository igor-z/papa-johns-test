<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "driver".
 *
 * @property int $id
 * @property string $name
 * @property string $birth_date
 *
 * @property DriverBus[] $driverBuses
 * @property Bus[] $buses
 */
class Driver extends \yii\db\ActiveRecord
{
    public $avg_speed;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'birth_date'], 'required'],
            [['birth_date'], 'safe'],
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
            'birth_date' => 'Birth Date',
        ];
    }

    public function getBuses()
    {
        return $this->hasMany(Bus::class, ['id' => 'bus_id'])->via('driverBuses');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriverBuses()
    {
        return $this->hasMany(DriverBus::class, ['driver_id' => 'id']);
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'age' => function () {
                $birthDate = new DateTime($this->birth_date);

                return $birthDate->diff(new DateTime('now'))->format('%Y');
            },
            'birth_date' => function () {
                if ($this->birth_date) {
                    return (new DateTime($this->birth_date))
                        ->format('m.d.Y');
                }
                return null;
            },
        ];
    }

    public function extraFields()
    {
        return [
            'buses',
            'avg_speed'
        ];
    }
}
