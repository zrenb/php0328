<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170722_024756_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            //goods_id	int	商品id
            'goods_id'=>$this->integer(11)->comment('商品id'),
            //content	text	商品描述
            'content'=>$this->text()->comment('商品描述'),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
