<?php

use yii\db\Migration;

/**
 * Class m200823_120918_create_table_sap_dosen
 */
class m200823_120918_create_table_sap_dosen extends Migration
{
    /**
     * {@inheritdoc}
     */
    /**public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    /**public function safeDown()
    {
        echo "m200823_120918_create_table_sap_dosen cannot be reverted.\n";

        return false;
    }*/

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable("sap_dsn_list", [
            'id' => $this->primaryKey(),
            'major_id' => $this->integer(),
            'periode_smt' => $this->string(6),
            'total_dsn' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultExpression('now()')
        ]);
    }

    public function down()
    {
        $this->dropTable("sap_dsn_list");
    }
    
}
