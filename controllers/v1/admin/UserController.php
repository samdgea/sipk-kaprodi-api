<?php

namespace app\controllers\v1\admin;

use Yii;
use yii\rest\Controller;

use yii\filters\auth\HttpBearerAuth;

use app\models\Forms\Admin\UserManagement\CreateForm;
use app\models\Forms\Admin\UserManagement\UpdateForm;
use app\models\Forms\Admin\UserManagement\DeleteForm;

use app\models\User;

class UserController extends Controller
{
    private $_response = [];
    private $_code = 500;

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
            'create' => ['POST'],
            'update' => ['POST'],
            'delete' => ['POST'],
            'view' => ['GET']
        ];
    }

    public function actionView($id = null)
    {
        $user = (!empty($id)) ? User::findOne($id) : User::find()->all();

        $this->_response = [
            'success' => true,
            'desc' => 'List user',
            'data' => $user
        ];

        $this->_code = 200;

        return $this->_sendResponse($this->_response, $this->_code);
    }

    public function actionCreate()
    {
        $model = new CreateForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->executeCreateUser())
        {
            $this->_response = [
                'success' => true,
                'desc' => 'Akun berhasi dibuat',
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

    public function actionUpdate($id)
    {
        $model = new UpdateForm($id);
        
        if ($model->load(Yii::$app->request->post(), '') && $model->executeUpdateUser())
        {
            $this->_response = [
                'success' => true,
                'desc' => 'Akun berhasi diubah',
                'data' => $model->getUserDetail()
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

    public function actionDelete($id)
    {
        $model = new DeleteForm($id);
        
        if ($model->executeDeleteUser())
        {
            $this->_response = [
                'success' => true,
                'desc' => 'Akun berhasi dihapus',
                'data' => $model->getUserDetail()
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