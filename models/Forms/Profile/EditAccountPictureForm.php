<?php

namespace app\models\Forms\Profile;

use Yii;
use yii\base\Model;
use app\models\User;
use yii2mod\ftp\FtpClient;
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
    private $ftp;

    public function __construct()
    {
        $this->_user = User::findOne(Yii::$app->user->id);

        // FTP Client
        $this->ftp = new FtpClient();
        $this->ftp->connect("139.99.21.240");
        $this->ftp->login("sipk-upload@sammy.works", "MuX=o7_O7lVF");
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
            
            $status = $this->_processImage($fileName);

            if ($status) {
                $this->_user->profile_picture = $fileName;

                return $this->_user->save();
            } 
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

    private function _processImage($fileName)
    {
        $file_path = Yii::getAlias('@app') . '/web/images/uploads/profile/';

        // Check old picture, then remove it
        if (!empty($this->_user->profile_picture) && file_exists($file_path . $this->_user->profile_picture))
            unlink($file_path . $this->_user->profile_picture);
        
        // Save New Profile Picture and save to database
        return $this->picture->saveAs($file_path . $fileName);
    }
}