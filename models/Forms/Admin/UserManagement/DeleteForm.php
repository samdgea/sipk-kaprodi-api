<?php

namespace app\models\Forms\Admin\UserManagement;

use Yii;
use yii\base\Model;

use app\models\User;

/**
 * DeleteForm is model behind validation for delete account on administrative side
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class DeleteForm extends Model
{
    private $_user = false;

    public function __construct($id)
    {
        $this->_user = User::findOne($id);
    }

    public function rules()
    {
        return [];
    }

    public function executeDeleteUser()
    {
        if (!empty($this->_user)) 
        {   
            return $this->_user->delete();
        } else {
            $this->addError('email_address', 'Invalid Account ID');
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