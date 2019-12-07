<?php

namespace app\controllers\v1;

use Yii;
use yii\rest\Controller;
use app\models\User;

use app\models\Forms\LoginForm;

class AuthController extends Controller 
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass = User::class;

    public function actionLogin()
    {
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'success' => true,
                    'desc' => 'Berhasil login',
                    'data' => [
                        'token' => $model->accessToken
                    ]
                ],
                'statusCode' => 200
            ]);
        } else {
            return Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'success' => false,
                    'desc' => 'Account disabled or invalid credentials',
                    'data' => null
                ],
                'statusCode' => 401
            ]);
        }
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    public function verbs() 
    {
        return [
            'login' => ['POST']
        ];
    }
}