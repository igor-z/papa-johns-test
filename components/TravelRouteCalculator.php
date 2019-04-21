<?php
namespace app\components;

use Yandex\Geo\Api;

class TravelRouteCalculator
{
    private $yandexGeo;

    public function __construct(Api $yandexGeo)
    {
        $this->yandexGeo = $yandexGeo;
    }

    private function distance(array $from, array $to) : float
    {
        $pi80 = M_PI / 180;

        $latitude1 = $from['latitude'] * $pi80;
        $longitude1 = $from['longitude'] * $pi80;
        $latitude2 = $to['latitude'] * $pi80;
        $longitude2 = $to['longitude'] * $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dLatitude = $latitude2 - $latitude1;
        $dLongitude = $longitude2 - $longitude1;
        $a = sin($dLatitude / 2) * sin($dLatitude / 2) + cos($latitude1) * cos($latitude2) * sin($dLongitude / 2) * sin($dLongitude / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $r * $c;
    }

    public function calculateDistance(string $fromLocation, string $toLocation)
    {
        $response = $this->yandexGeo
            ->setQuery($fromLocation)
            ->setLimit(1)
            ->setLang(Api::LANG_RU)
            ->load()
            ->getResponse();

        $fromGeoObject = $response->getFirst();

        $from = [
            'latitude' => $fromGeoObject->getLatitude(),
            'longitude' => $fromGeoObject->getLongitude(),
        ];

        $response = $this->yandexGeo
            ->setQuery($toLocation)
            ->setLimit(1)
            ->setLang(Api::LANG_RU)
            ->load()
            ->getResponse();

        $toGeoObject = $response->getFirst();
        $to = [
            'latitude' => $toGeoObject->getLatitude(),
            'longitude' => $toGeoObject->getLongitude(),
        ];

        return $this->distance($from, $to);
    }
}