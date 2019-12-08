<?php

namespace app\models\Forms\Auth;

use Yii;
use yii\base\Model;

use app\models\User;

/**
 * ForgotPasswordForm is model behind validation for forgot account
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class ForgotPasswordForm extends Model
{
    public $email_address;
    public $forgot_code;
    public $password;
    public $password_repeat;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email_address'], 'required'],
            [['email_address', 'password', 'password_repeat'], 'string', 'max' => 50],
            [['forgot_code'], 'string', 'max' => 100],
            ['email_address', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email_address']
        ];
    }

    public function executeChangePassword()
    {
        if ($this->validate()) 
        {   
            $this->_user = User::findOne(['email_address' => $this->email_address]);
            if ($this->_user->email_verification_hash === $this->forgot_code) 
            {
                if ($this->password === $this->password_repeat)
                {
                    $this->_user->email_verification_hash = null;
                    $this->_user->makePassword($this->password);
                    return $this->_user->save();
                } else {
                    $this->addError('password', 'Password did not match');
                }
            } else {
                $this->addError('forgot_code', 'Invalid Forgot Password Code');
            }
        }

        return false;
    }

    public function executeValidateAccount()
    {
        if ($this->validate()) 
        {
            $this->_user = User::findOne(['email_address' => $this->email_address]);
            $this->_user->makeEmailVerifyHash();
            
            return $this->_user->save();
        }

        return false;
    }

    public function getUserDetail()
    {
        return $this->_user;
    }
}