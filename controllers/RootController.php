<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;

class RootController extends Controller
{
    public function actionIndex()
    {
        return Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'success' => true,
                'desc' => 'SIPK Backend API',
                'data' => null
            ],
            'statusCode' => 200
        ]);
    }
}