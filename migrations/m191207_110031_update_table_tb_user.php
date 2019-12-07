<?php

use yii\db\Migration;

/**
 * Class m191207_110031_update_table_tb_user
 */
class m191207_110031_update_table_tb_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_account', 'email_verification_hash', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_account', 'email_verification_hash');
    }
}
