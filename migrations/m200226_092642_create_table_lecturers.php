<?php

use yii\db\Migration;

/**
 * Class m200226_092642_create_table_lecturers
 */
class m200226_092642_create_table_lecturers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('tb_lecturer', [
            'id' => $this->primaryKey(),
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
        $this->dropTable('tb_lecturer');
    }
}
