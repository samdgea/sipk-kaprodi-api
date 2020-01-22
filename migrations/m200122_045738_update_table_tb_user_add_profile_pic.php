<?php

use yii\db\Migration;

/**
 * Class m200122_045738_update_table_tb_user_add_profile_pic
 */
class m200122_045738_update_table_tb_user_add_profile_pic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('user_account', 'profile_picture', $this->string(125));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('user_account', 'profile_picture');
    }
}
