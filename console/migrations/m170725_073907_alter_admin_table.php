<?php

use yii\db\Migration;

class m170725_073907_alter_admin_table extends Migration
{
    public function up()
    {

    }

    public function down()
    {
        $this->dropColumn('admin','password');
        //echo "m170725_073907_alter_admin_table cannot be reverted.\n";

        //return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
