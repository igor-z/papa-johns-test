<?php
namespace app\models;

use DateTime;
use Yii;
use yii\base\Model;
use yii\validators\InlineValidator;

class RestDriverForm extends Model
{
    public $id;
    public $birth_date;
    public $name;
    public $buses;

    public function rules()
    {
        return [
            ['birth_date', 'datetime', 'format' => 'php:m.d.Y'],
            ['name', 'string'],
            [['name', 'birth_date', 'buses'], 'required'],
            ['buses', 'validateBuses'],
        ];
    }

    public function validateBuses($attribute, $params, InlineValidator $validator)
    {
        if (!$this->$attribute)
            return;

        $busType = new BusType();
        foreach ($this->$attribute as $bus) {
             $busType->load($bus, '');
             if (!$busType->validate()) {
                 foreach ($busType->getErrors() as $errors) {
                     foreach ($errors as $error) {
                        $validator->addError($this, $attribute, $error);
                     }
                 }
             }

        }
    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $transaction = Yii::$app->db->beginTransaction();

        if ($this->id) {
            $driver = Driver::findOne($this->id);
        } else {
            $driver = new Driver();
        }
        $driver->name = $this->name;
        $driver->birth_date = DateTime::createFromFormat('m.d.Y', $this->birth_date)->format('Y-m-d');
        if (!$driver->save()) {
            $transaction->rollBack();
            return false;
        }

        foreach ($this->buses as $busData) {
            $bus = new Bus();
            $bus->load($busData, '');
            if (!$bus->save()) {
                $transaction->rollBack();
                return false;
            }

            $driverBus = new DriverBus();
            $driverBus->driver_id = $driver->id;
            $driverBus->bus_id = $bus->id;
            if (!$driverBus->save()) {
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();

        return $driver->id;
    }
}