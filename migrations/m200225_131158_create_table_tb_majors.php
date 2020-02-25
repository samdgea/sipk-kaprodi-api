<?php

use yii\db\Migration;

/**
 * Class m200225_131158_create_table_tb_majors
 */
class m200225_131158_create_table_tb_majors extends Migration
{
    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('tb_major', [
            'id' => $this->primaryKey(),
            'faculty_id' => $this->integer(),
            'name' => $this->string('100'),
            'description' => $this->text()->null(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultExpression('now()')//->append('ON UPDATE now()')
        ]);
    }

    public function down()
    {
        $this->dropTable('tb_major');
    }
}
