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
            
            // Process save image to FTP Server
            $this->_processImage($fileName);

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

    private function _processImage($fileName)
    {
        // Check old picture, then remove it
        if (!empty($this->_user->profile_picture) && $this->ftp->size("/" . $this->_user->profile_picture) > 0)
            $this->ftp->delete($this->_user->profile_picture);
        
        // Save New Profile Picture and save to database
        $this->picture->saveAs(Yii::getAlias('@app') . '/web/images/uploads/profile/' . $fileName);
        if ($file_on_ftp = $this->ftp->put("/" . $fileName, Yii::getAlias('@app') . '/web/images/uploads/profile/' . $fileName, FTP_BINARY)) 
            unlink(Yii::getAlias('@app') . '/web/images/uploads/profile/' . $fileName);

        return $file_on_ftp;
    }
}