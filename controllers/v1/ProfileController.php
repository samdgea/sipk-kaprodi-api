<?php

namespace app\controllers\v1;

use Yii;
use yii\rest\Controller;
use app\models\User;
use app\models\AccessToken;

use app\models\Forms\Profile\EditAccountForm;
use app\models\Forms\Profile\ChangePasswordForm;
use app\models\Forms\Profile\EditAccountPictureForm;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;

class ProfileController extends Controller 
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass = User::class;

    protected $_response;
    protected $_code = 500;

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

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    public function verbs() 
    {
        return [
            'logout' => ['POST'],
            'me' => ['GET', 'POST'],
            'change-profile-picture' => ['POST']
        ];
    }

    /**
     * Profile Details
     * @method GET = Retrieve user profile details
     * @method POST = Modify user profile details
     * 
     * @return \yii\BaseYii|object
     */
    public function actionMe() 
    {
        if (Yii::$app->request->isGet) {
            $user = Yii::$app->user->identity;

            unset($user['password_hashed']);

            $this->_response = [
                'success' => true,
                'desc' => 'Detail Profil',
                'data' => $user
            ];

            $this->_code = 200;
        } else {
            $model = new EditAccountForm();

            if ($model->load(Yii::$app->request->post(), '') && $model->executeEditAccount())
            {
                $this->_response = [
                    'success' => true,
                    'desc' => 'Berhasil mengubah detail profil',
                    'data' => $model->getUser()
                ];

                $this->_code = 200;
            } else {
                $this->_response = [
                    'success' => false,
                    'desc' => 'Permintaan anda tidak sesuai dengan validasi',
                    'data' => $model->getErrorSummary($model->getErrors())
                ];

                $this->_code = 400;
            }
        }

        return $this->_sendResponse($this->_response, $this->_code);
    }

    public function actionChangeProfilePicture()
    {
        $model = new EditAccountPictureForm();
        $model->picture = UploadedFile::getInstanceByName('picture');

        if (!empty($model->picture)) {
            if ($model->processUpload()) {
                $this->_response = [
                    'success' => true,
                    'desc' => 'Ubah gambar profil berhasil',
                    'data' => $model->getUser()
                ];
    
                $this->_code = 200;
            } else {
                $this->_response = [
                    'success' => false,
                    'desc' => 'Permintaan anda tidak sesuai dengan validasi',
                    'data' => $model->getErrorSummary($model->getErrors())
                ];
    
                $this->_code = 400;
            }
        } else {
            $this->_response = [
                'success' => false,
                'desc' => 'Harap pilih gambar yang ingin di gunakan',
                'data' => null
            ];

            $this->_code = 400;
        }

        return $this->_sendResponse($this->_response, $this->_code);
    }

    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->executeChangePassword())
        {
            $this->_response = [
                'success' => true,
                'desc' => 'Permintaan ubah password berhasil',
                'data' => null
            ];

            $this->_code = 200;
        } else {
            $this->_response = [
                'success' => false,
                'desc' => 'Permintaan anda tidak sesuai dengan validasi',
                'data' => $model->getErrorSummary($model->getErrors())
            ];

            $this->_code = 400;
        }

        return $this->_sendResponse($this->_response, $this->_code);
    }

    /**
     * Logout session
     * Ends user session
     * @method POST
     *
     */
    public function actionLogout() 
    {
        $headers = Yii::$app->request->getHeaders();
        $headers = explode(" ", $headers['authorization']);

        $accessToken = AccessToken::find()->where(['access_token' => $headers[1]])->one();
        $accessToken->token_valid = false;
        $accessToken->save();

        $this->_response = [
            'success' => true,
            'desc' => 'Berhasil keluar',
            'data' => null
        ];
        $this->_code = 200;

        return $this->_sendResponse($this->_response, $this->_code);
    }

    private function _sendResponse($data, $status_code)
    {
        return Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => $data,
            'statusCode' => $status_code
        ]);  
    }
}