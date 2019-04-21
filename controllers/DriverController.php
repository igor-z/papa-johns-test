<?php
namespace app\controllers;

use app\models\RestDriverForm;
use app\services\DriverService;
use Yii;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class DriverController extends Controller
{
    public function actionIndex(int $page = 0)
    {
        /** @var DriverService $service */
        $service = Yii::$container->get(DriverService::class);

        return $service->getDrivers($page);
    }

    public function actionCreate()
    {
        $model = new RestDriverForm();

        /** @var DriverService $service */
        $service = Yii::$container->get(DriverService::class);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($saveResult = $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return [
            'status' => !$model->hasErrors(),
            'errors' => $model->getErrors(),
            'data' => $saveResult ? $service->getDriver($saveResult) : [],
        ];
    }

    public function actionUpdate($id)
    {
        $model = new RestDriverForm();
        $model->id = $id;

        /** @var DriverService $service */
        $service = Yii::$container->get(DriverService::class);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($saveResult = $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return [
            'status' => !$model->hasErrors(),
            'errors' => $model->getErrors(),
            'data' => $saveResult ? $service->getDriver($saveResult) : [],
        ];
    }

    public function actionView($id)
    {
        /** @var DriverService $service */
        $service = Yii::$container->get(DriverService::class);

        return $service->getDriver($id);
    }

    public function actionTravelTime(string $from, string $to, int $id = null, int $page = 0)
    {
        /** @var DriverService $service */
        $service = Yii::$container->get(DriverService::class);

        if (isset($id)) {
            return $service->getDriverTravelTime($id, $from, $to);
        } else {
            return $service->getDriversTravelTime($from, $to, $page);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'travelTime' => ['GET'],
        ];
    }
}