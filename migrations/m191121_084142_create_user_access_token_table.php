<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_access_token}}`.
 */
class m191121_084142_create_user_access_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_access_token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'access_token' => $this->string(50),
            'token_valid' => $this->boolean()->defaultValue(false),
            'expires_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ]);

        $this->createIndex('IDX_TOKEN_USER_ID', '{{%user_access_token}}', 'user_id');

        $this->addForeignKey('FK_USER_ID_TO_TOKEN', '{{%user_access_token}}', 'user_id', 'user_account', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_access_token}}');
    }
}
