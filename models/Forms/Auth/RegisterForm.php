<?php

namespace app\models\Forms\Auth;

use Yii;
use yii\base\Model;

use app\models\User;

/**
 * RegisterForm is model behind validation for registration account
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class RegisterForm extends Model
{
    public $first_name;
    public $last_name;

    public $email_address;
    public $user_name;
    public $password;

    private $_user = false;

    public function __construct()
    {
        $this->_user = new User;    
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['first_name', 'user_name', 'password', 'email_address'], 'required'],
            [['first_name', 'last_name', 'user_name', 'password', 'email_address'], 'string', 'max' => 50],
            ['email_address', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email_address'],
            ['user_name', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'user_name'],
        ];
    }

    public function executeCreateAccount()
    {
        if ($this->validate())
        {
            $this->_user->first_name = $this->first_name;
            $this->_user->last_name = $this->last_name;
            $this->_user->user_name = $this->user_name;
            $this->_user->email_address = $this->email_address;
            $this->_user->makePassword($this->password);
            $this->_user->makeEmailVerifyHash();
            $this->_user->account_status = User::ACCOUNT_INACTIVE;

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