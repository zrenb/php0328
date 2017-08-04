<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_pic`.
 */
class m170801_014202_create_goods_pic_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_pic', [
            'id' => $this->primaryKey(),

            // goods_id	int	商品id
            'goods_id'=>$this->integer(11)->comment('商品id'),
            // path	varchar(255)
            'path'=>$this->string(255)->comment('图片路径')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_pic');
    }
}
