<?php

namespace app\controllers\v1;

use Yii;
use yii\rest\Controller;
use app\models\User;
use app\models\AccessToken;

use yii\filters\auth\HttpBearerAuth;

class ProfileController extends Controller 
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass = User::class;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];

        return $behaviors;
    }

    public function actionMe() 
    {
        if (Yii::$app->request->isGet) {
            $user = Yii::$app->user->identity;

            unset($user['password_hashed']);

            return Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'success' => true,
                    'desc' => 'Detail Profil',
                    'data' => $user
                ],
                'statusCode' => 200
            ]);
        } else {
            return Yii::$app->user->identity->getAuthKey();
            // return Yii::createObject([
            //     'class' => 'yii\web\Response',
            //     'format' => \yii\web\Response::FORMAT_JSON,
            //     'data' => [
                    
            //     ],
            //     'statusCode' => 200
            // ]);
        }
    }

    public function actionLogout() 
    {
        $headers = Yii::$app->request->getHeaders();
        $headers = explode(" ", $headers['authorization']);

        $accessToken = AccessToken::find()->where(['access_token' => $headers[1]])->one();
        $accessToken->token_valid = false;
        $accessToken->save();

        return Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'success' => true,
                'desc' => 'Berhasil keluar',
                'data' => null
            ],
            'statusCode' => 200
        ]);
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    public function verbs() 
    {
        return [
            'logout' => ['POST'],
            'me' => ['GET', 'POST']
        ];
    }
}