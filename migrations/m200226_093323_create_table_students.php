<?php

use yii\db\Migration;

/**
 * Class m200226_093323_create_table_students
 */
class m200226_093323_create_table_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('tb_student', [
            'id' => $this->primaryKey(),
            'student_id' => $this->string(50),
            'first_name' => $this->string(50),
            'last_name' => $this->string(50)->null(),
            'join_date' => $this->date(),
            'is_active' => $this->boolean()->defaultValue(false),
            'id_major' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultExpression('now()')//->append('ON UPDATE now()')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('tb_student');
    }
}
