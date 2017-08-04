<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170724_060421_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            //`id`
            'username'=>$this->string(255)->comment('用户名'),
            //`username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            'auth_key'=>$this->string(32)->comment('auth_key'),
            //`auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
            //'password'=>$this->string(255)->comment('密码'),
            //`password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            'password_hash'=>$this->string(255)->comment('PASSWORD_HASH'),
            'password_reset_token'=>$this->string(255)->comment('PASSWORD_RESET_TOKEN'),
            //`password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            'email'=>$this->string(255)->comment('邮箱'),
            //`email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            'status'=>$this->smallInteger(6)->comment('状态'),
            //`status` smallint(6) NOT NULL DEFAULT '10',
            //`created_at` int(11) NOT NULL,
            'created_at'=>$this->integer(11)->comment('创建时间'),
            //`updated_at` int(11) NOT NULL,
            'updated_at'=>$this->integer(11)->comment('创建时间'),
            'last_login_time'=>$this->integer(11)->comment('最后登录时间'),
            'last_login_ip'=>$this->integer(11)->comment('最后登录ip')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
