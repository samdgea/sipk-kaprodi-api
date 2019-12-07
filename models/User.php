<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use yii\base\NotSupportedException;

/**
 * This is the model class for table "user_account".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $user_name
 * @property string $email_address
 * @property string $password_hashed
 * @property integer $account_status
 * @property string $created_at
 * @property string $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ACCOUNT_ACTIVE = 10;
    const ACCOUNT_BLOCKED = 99;
    const ACCOUNT_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_status'], 'default', 'value' => null],
            [['account_status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name', 'email_address'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 25],
            [['password_hashed'], 'string', 'max' => 255],
            [['email_address'], 'unique'],
            [['user_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'user_name' => 'User Name',
            'email_address' => 'Email Address',
            'password_hashed' => 'Password Hashed',
            'account_status' => 'Account Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $access = AccessToken::find()->where([
            'access_token' => $token, 
            'token_valid' => true
        ])->one();

        return (!empty($access) && strtotime($access->expires_at) >= time()) ? $access->getUser()->one() : null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['user_name' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password_hashed === sha1($password);
    }

    /**
     * Generates password hash
     * Creates encrypted from plain text to SHA1 Hash
     * 
     * @param string $plain_text Plain password to be encrypted
     * @return void nothing to returned
     */
    public function makePassword($plain_text) 
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($plain_text);
    }

    /**
     * Generates Access Token
     * 
     * @return string Access Token
     */
    public function generateAccessToken()
    {
        $accessToken = new AccessToken();
        $accessToken->user_id = $this->getPrimaryKey();
        $accessToken->access_token = Yii::$app->getSecurity()->generateRandomString();
        $accessToken->expires_at = date('Y-m-d H:i:s', (time()+3600*24*7));
        $accessToken->token_valid = true;
        $accessToken->save();

        return $accessToken->access_token;
    }

    /**
     * List of Access Token
     * 
     * @return AccessToken[] list of AccessToken
     */
    public function getAccessToken()
    {
        return $this->hasMany(AccessToken::class, ['user_id' => 'id']);
    }

    public function getAuthKey()
    {
        throw new NotSupportedException("This method is not implemented yet");
    }

    public function validateAuthKey($auth_key)
    {
        throw new NotSupportedException("This method is not implemented yet");
    }
}
