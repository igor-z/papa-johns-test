<?php
namespace app\services;

use app\components\TravelRouteCalculator;
use app\models\Driver;
use app\models\DriverSearch;
use app\models\DriverTravelSearch;
use yii\db\ActiveQuery;

class DriverService
{
    private $routeCalculator;

    public function __construct(TravelRouteCalculator $routeCalculator)
    {
        $this->routeCalculator = $routeCalculator;
    }

    public function getDrivers(int $page)
    {
        $search = new DriverSearch([
            'page' => $page,
        ]);
        $dataProvider = $search->search();

        $drivers = [];
        /** @var Driver $driver */
        foreach ($dataProvider->getModels() as $driver) {
            $drivers[] = $driver->toArray([], ['buses']);
        }

        return $drivers;
    }

    public function getDriver(int $id)
    {
        $driver = Driver::find()
            ->where(['id' => $id])
            ->one();

        return $driver->toArray([], ['buses']);
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $page
     * @return array
     */
    public function getDriversTravelTime(string $from, string $to, int $page) : array
    {
        $search = new DriverTravelSearch([
            'from' => $from,
            'to' => $to,
            'page' => $page,
        ]);
        $dataProvider = $search->search();

        $drivers = [];
        /** @var Driver $driver */
        foreach ($dataProvider->getModels() as $driver) {
            $driver = $driver->toArray([], ['avg_speed']);
            $driver['travel_distance'] = $this->routeCalculator->calculateDistance($from, $to);
            $driver['travel_time'] = $driver['travel_distance'] / $driver['avg_speed'];
            $drivers[] = $driver;
        }

        return $drivers;
    }

    /**
     * @param int $id
     * @param string $from
     * @param string $to
     * @return array|null
     */
    public function getDriverTravelTime(int $id, string $from, string $to)
    {
        $search = new DriverTravelSearch([
            'from' => $from,
            'to' => $to,
            'id' => $id,
        ]);
        $dataProvider = $search->search();
        $drivers = $dataProvider->getModels();
        if (!$drivers) {
            return null;
        }

        $driver = reset($drivers);

        $driver = $driver->toArray([], ['buses']);
        $driver['travel_distance'] = $this->routeCalculator->calculateDistance($from, $to);
        foreach ($driver['buses'] as $bus) {
            $driver['travel_time'] = $driver['travel_distance'] / $bus['avg_speed'];
        }

        return $driver;
    }
}