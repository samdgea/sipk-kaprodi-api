<?php

namespace app\controllers\v1;

use Yii;
use yii\rest\Controller;
use app\models\User;

use app\models\Forms\Auth\LoginForm;
use app\models\Forms\Auth\RegisterForm;
use app\models\Forms\Auth\EmailValidationForm;
use app\models\Forms\Auth\ForgotPasswordForm;

class AuthController extends Controller 
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass = User::class;

    private $_response = [];
    private $_code = 400;

    public function actionLogin()
    {
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
           
            $this->_response = [
                'success' => true,
                'desc' => 'Berhasil login',
                'data' => [
                    'token' => $model->accessToken
                ]
            ];
            $this->_code = 200;
        } else {
            $this->_response = [
                'success' => false,
                'desc' => 'Account disabled or invalid credentials',
                'data' => null
            ];
            $this->_code = 401;
        }

        return $this->_sendResponse($this->_response, $this->_code);
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->executeCreateAccount())
        {
            $this->_sendVerificationEmail($model->getUserDetail());


            $this->_response = [
                'success' => true,
                'desc' => 'Berhasil registrasi akun, silahkan cek email anda untuk verifikasi akun',
                'data' => $model->getUserDetail()
            ];

            $this->_code = 201;
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

    public function actionVerify()
    {
        $model = new EmailValidationForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->executeValidateAccount())
        {
            $this->_response = [
                'success' => true,
                'desc' => 'Akun anda telah berhasil di verifikasi',
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

    public function actionForgot()
    {
        $model = new ForgotPasswordForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->executeValidateAccount())
        {
            $this->_sendVerificationEmail($model->getUserDetail(), 'Your forgot password verification code');

            $this->_response = [
                'success' => true,
                'desc' => 'Kode untuk verifikasi lupa password telah dikirimkan ke email anda.',
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

    public function actionForgotValidate()
    {
        $model = new ForgotPasswordForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->executeChangePassword())
        {
            $this->_response = [
                'success' => true,
                'desc' => 'Anda telah berhasil mengubah kata sandi anda, silahkan log-in menggunakan kredensial baru anda.',
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
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    public function verbs() 
    {
        return [
            'login' => ['POST'],
            'register' => ['POST']
        ];
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

    private function _sendVerificationEmail($user, $subject = "Please validate your account")
    {
        return Yii::$app->queue->push(new \app\jobs\SendRegistrationMailJob([
            'to_email' => $user->email_address,
            'subject_title' => $subject,
            'body' => "Hi, this is your verification code: " . $user->email_verification_hash . "\nPlease ignore if you did not request it"
        ]));
    }
}