<?php

use yii\db\Migration;

/**
 * Class m200818_034417_create_table_sap_mhs
 */
class m200818_034417_create_table_sap_mhs extends Migration
{
    /**
     * {@inheritdoc}
     */
    /*public function safeUp()
    {

    }*/

    /**
     * {@inheritdoc}
     */
    /*public function safeDown()
    {
        echo "m200818_034417_create_table_sap_mhs cannot be reverted.\n";

        return false;
    }*/

    /*Use up()/down() to run migration code without a transaction. */
    public function up()
    {
        $this->createTable("sap_mhs_list", [
            'id' => $this->primaryKey(),
            'major_id' => $this->integer(),
            'periode_smt' => $this->string(6),
            'total_mhs' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('now()'),
            'updated_at' => $this->timestamp()->defaultExpression('now()')
        ]);
    }

    public function down()
    {
        $this->dropTable("sap_mhs_list");
    }
    // */
}
