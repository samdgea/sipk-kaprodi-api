<?php

use yii\db\Migration;

/**
 * Class m200225_131253_create_table_tb_faculty
 */
class m200225_131253_create_table_tb_faculty extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('tb_faculty', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'description' => $this->text()->null(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultExpression('now()')//->append('ON UPDATE now()')
        ]);
    }

    public function down()
    {
        $this->dropTable('tb_faculty');
    }
}
