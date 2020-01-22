<?php

namespace app\models\Forms\Profile;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * EditAccountPictureForm is model behind validation of edit account profile picture
 * 
 * @property User|null $_user This prop is read-only
 * @property FileUpload $picture This prop is public
 * 
 */
class EditAccountPictureForm extends Model
{
    public $picture;
    
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
            ['picture', 'file', 'extensions' => ['jpg', 'png'], 'mimeTypes' => ['image/jpeg', 'image/png'], 'skipOnEmpty' => false, 'maxSize' => 1048576]
        ];
    }

    public function processUpload()
    {
        if ($this->validate()) 
        {
            $fileName = uniqid(rand(), false) . '.' . $this->picture->extension;
            
            // Remove / Delete Old Profile Picture
            $this->_removeOldPicture();

            // Save New Profile Picture and save to database
            $this->picture->saveAs(Yii::getAlias('@app') . '/web/images/uploads/profile/' . $fileName);
            $this->_user->profile_picture = $fileName;

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

    private function _removeOldPicture()
    {
        if (file_exists(Yii::getAlias('@app') . '/web/images/uploads/profile/' . $this->_user->profile_picture)) 
            unlink(Yii::getAlias('@app') . '/web/images/uploads/profile/' . $this->_user->profile_picture);
    }
}