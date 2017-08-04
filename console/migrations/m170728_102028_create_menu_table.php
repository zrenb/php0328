<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_102028_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label'=>$this->string(50)->comment('菜单名称'),
            'url'=>$this->string(100)->comment('路由'),
            'parent_id'=>$this->integer(11)->comment('父id'),
            'sort'=>$this->integer(11)->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
