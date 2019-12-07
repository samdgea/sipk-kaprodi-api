<?php

namespace app\models\Forms\Profile;

use Yii;
use yii\base\Model;

use app\models\User;

/**
 * RegisterForm is model behind validation for registration account
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class ChangePasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $new_password_repeat;

    private $_user = false;

    public function __construct()
    {
        $this->_user = User::findOne(Yii::$app->user->id);
    }

    /**
     * @return array the validation rules.
     */
    public function rules() 
    {
        return [
            [['old_password', 'new_password', 'new_password_repeat'], 'required'],
            [['old_password', 'new_password', 'new_password_repeat'], 'string', 'max' => 50]
        ];
    }

    /**
     * 
     * 
     */
    public function executeChangePassword()
    {
        if ($this->validate())
        {
            if ($this->_user->validatePassword($this->old_password))
            {
                if ($this->new_password === $this->new_password_repeat) 
                {
                    $this->_user->makePassword($this->new_password);
                    
                    return $this->_user->save();
                }

                $this->addError('new_password', 'New password did not match');
            } else {
                $this->addError('old_password','Invalid old password');
            }
        }

        return false;
    }
}