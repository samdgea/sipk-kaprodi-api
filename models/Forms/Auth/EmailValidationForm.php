<?php

namespace app\models\Forms\Auth;

use Yii;
use yii\base\Model;

use app\models\User;

/**
 * EmailValidationForm is model behind validation for validate account
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class EmailValidationForm extends Model
{
    public $email_address;
    public $verification_code;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email_address', 'verification_code'], 'required'],
            [['email_address'], 'string', 'max' => 50],
            [['verification_code'], 'string', 'max' => 100],
            ['email_address', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email_address']
        ];
    }

    public function executeValidateAccount()
    {
        if ($this->validate()) 
        {
            $this->_user = User::findOne(['email_address' => $this->email_address]);
            if (!empty($this->_user) && $this->_user->email_verification_hash === $this->verification_code)
            {
                $this->_user->account_status = User::ACCOUNT_ACTIVE;
                $this->_user->email_verification_hash = null;
                
                return $this->_user->save();
            } else {
                $this->addError('verification_code', 'Invalid verification code');
            }
        }

        return false;
    }
}