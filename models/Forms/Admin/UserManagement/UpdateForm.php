<?php

namespace app\models\Forms\Admin\UserManagement;

use Yii;
use yii\base\Model;

use app\models\User;

/**
 * UpdateForm is model behind validation for update account on administrative side
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class UpdateForm extends Model
{
    public $first_name;
    public $last_name;
    public $user_name;
    public $password;
    public $email_address;
    public $account_status;

    private $_user = false;

    public function __construct($id)
    {
        $this->_user = User::findOne($id);
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'user_name', 'password'], 'string', 'max' => 50],
            [['email_address'], 'string', 'max' => 100],
            [['account_status'], 'integer']
        ];
    }

    public function executeUpdateUser()
    {
        if ($this->validate())
        {
            if (!empty($this->_user)) 
            {
                if (!empty($this->first_name)) $this->_user->first_name = $this->first_name;
                if (!empty($this->last_name)) $this->_user->last_name = $this->last_name;
                if (!empty($this->user_name)) $this->_user->user_name = $this->user_name;
                if (!empty($this->email_address)) $this->_user->email_address = $this->email_address;
                if (!empty($this->password)) $this->_user->makePassword($this->password);
                if (!empty($this->account_status)) $this->_user->account_status = $this->account_status;

                return $this->_user->save();
            } else {
                $this->addError('email_address', 'Invalid Account ID');
            }
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