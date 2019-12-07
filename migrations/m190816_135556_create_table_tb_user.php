<?php

use yii\db\Migration;

/**
 * Class m190816_135556_create_table_tb_user
 */
class m190816_135556_create_table_tb_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_account', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(50),
            'last_name' => $this->string(50)->null(),
            'user_name' => $this->string(25)->unique(),
            'email_address' => $this->string(50)->unique(),
            'password_hashed' => $this->string(255),
            'account_status' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultValue(null)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_account');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190816_135556_create_table_tb_user cannot be reverted.\n";

        return false;
    }
    */
}
