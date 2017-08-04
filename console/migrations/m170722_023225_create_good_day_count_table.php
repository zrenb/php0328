<?php

use yii\db\Migration;

/**
 * Handles the creation of table `good_day_count`.
 */
class m170722_023225_create_good_day_count_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('good_day_count', [
            // day	date	日期
            'day'=>$this->date()->comment('日期'),
            //count	int	商品数
            'count'=>$this->integer()->comment('商品数')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('good_day_count');
    }
}
