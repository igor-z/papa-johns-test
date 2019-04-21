<?php
namespace app\models;

use yii\base\Model;

class BusType extends Model
{
    public $name;
    public $avg_speed;

    public function rules()
    {
        return [
            ['name', 'string'],
            ['avg_speed', 'integer'],
            [['name', 'avg_speed'], 'required'],
        ];
    }
}