<?php

use yii\db\Migration;

/**
 * Handles the creation of table `abres`.
 */
class m170730_065017_create_abres_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('abres', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer()->comment('用户id'),
            'name'=>$this->string(50)->comment('收 货 人'),
            'tel'=>$this->integer(11)->comment('手机号码'),
            'province'=>$this->string(20)->comment('省'),
            'city'=>$this->string(20)->comment('市'),
            'area'=>$this->string(50)->comment('区县'),
            'detail'=>$this->string(100)->comment('详细地址'),
            'status'=>$this->integer()->defaultValue(0)->comment('默认收货地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('abres');
    }
}
