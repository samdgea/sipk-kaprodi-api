<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_access_token".
 *
 * @property int $id
 * @property int $user_id
 * @property string $access_token
 * @property bool $token_valid
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property UserAccount $user
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_access_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['token_valid'], 'boolean'],
            [['expires_at', 'created_at', 'updated_at'], 'safe'],
            [['access_token'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'access_token' => 'Access Token',
            'token_valid' => 'Token Valid',
            'expires_at' => 'Expires At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\AccessTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AccessTokenQuery(get_called_class());
    }
}
