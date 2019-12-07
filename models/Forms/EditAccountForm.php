<?php

namespace app\models\Forms;

use Yii;
use yii\base\Model;

use app\models\User;
use Carbon\Carbon;

/**
 * EditAccountForm is model behind validation of edit account
 * 
 * @property User|null $_user This prop is read-only
 * 
 */
class EditAccountForm extends Model
{
    public $first_name;
    public $last_name;
    public $user_name;
    public $password;

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
            [['first_name'], 'required'],
            [['first_name', 'last_name', 'user_name', 'password'], 'string', 'max' => 50],
            // ['user_name', 'unique']
        ];
    }

    /**
     * 
     */
    public function executeEditAccount()
    {
        if ($this->validate()) 
        {
            $this->_user->first_name = $this->first_name;
            $this->_user->last_name = $this->last_name;
            if (!empty($this->user_name)) $this->_user->user_name = $this->user_name;
            if (!empty($this->password)) $this->_user->makePassword($this->password);
            $this->_user->updated_at = Carbon::now();
            return $this->_user->save();
        } 
        
        return false;
    }

    public function getUser()
    {
        $res = $this->_user;
        unset($res['password_hashed']);
        unset($res['email_verification_hash']);

        return $res;
    }
}