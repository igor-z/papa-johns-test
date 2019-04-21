<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression as DbExpression;
use yii\db\Query;

class DriverTravelSearch extends Model
{
    public $page = 0;
    public $id;
    public $from;
    public $to;

    public function rules()
    {
        return [
            [['page', 'id'], 'integer'],
            [['from', 'to'], 'string'],
            [['from', 'to'], 'required'],
        ];
    }

    public function search()
    {
        $query = Driver::find()
            ->joinWith([
                'driverBuses driverBuses',
            ], false)
            ->joinWith([
                'driverBuses.bus fastestBus' => function (ActiveQuery $query) {
                    $maxAverageSpeedQuery = (new Query())
                        ->select(new DbExpression('MAX(bus.avg_speed)'))
                        ->from('bus')
                        ->innerJoin('driver_bus', 'driver_bus.bus_id=bus.id')
                        ->andWhere('driver_bus.driver_id=driverBuses.driver_id')
                        ->groupBy('driver_bus.driver_id');

                    $query->andOnCondition([
                        'fastestBus.avg_speed' => $maxAverageSpeedQuery
                    ]);
                },
            ], false, 'INNER JOIN')
            ->orderBy(['fastestBus.avg_speed' => \SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $this->page,
            ]
        ]);

        if (!$this->validate()) {
            $query->where('false');
            return $dataProvider;
        }

        $query->andFilterWhere(['driver.id' => $this->id]);

        return $dataProvider;
    }
}