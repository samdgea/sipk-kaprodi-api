<?php

namespace app\models\Forms\Admin\UserManagement;

use Yii;
use yii\base\Model;

use app\models\User;

/**
 * CreateForm is model behind validation for create account on administrative side
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class CreateForm extends Model
{
    public $first_name;
    public $last_name;
    public $user_name;
    public $password;
    public $email_address;

    private $_user = false;

    public function __construct()
    {
        $this->_user = new User;
    }

    public function rules()
    {
        return [
            [['first_name', 'user_name', 'email_address'], 'required'],
            [['first_name', 'last_name', 'user_name', 'password'], 'string', 'max' => 50],
            [['email_address'], 'string', 'max' => 100],
            [['email_address'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email_address'],
            [['user_name'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'user_name'],
        ];
    }

    public function executeCreateUser()
    {
        if ($this->validate())
        {
            $password = (!empty($this->password)) ? $this->password : Yii::$app->getSecurity()->generateRandomString(10);

            $this->_user->first_name = $this->first_name;
            $this->_user->last_name = $this->last_name;
            $this->_user->user_name = $this->user_name;
            $this->_user->email_address = $this->email_address;
            $this->_user->makePassword($password);
            $this->_user->account_status = User::ACCOUNT_ACTIVE;

            return $this->_user->save();
        }

        return false;
    }

    public function getUserDetail()
    {
        $ret = $this->_user;
        unset($ret['password_hashed']);
        unset($ret['email_verification_hash']);
        
        return $ret;
    }
}