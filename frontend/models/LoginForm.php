<?php
namespace frontend\models;

use yii\base\Model;
use yii\helpers\Json;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $safe_login;
    public $code;

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'safe_login'=>'自动登录',
            'code' => '验证码',
        ];
    }

    public function rules()
    {
        return [
            [['username','password','code'],'required'],
             ['safe_login','boolean'],
            ['username','validateName'],
            ['password','validatePassword'],
            ['code','captcha','captchaAction'=>'member/captcha'],
        ];
    }
    public function validateName()
    {
        $member = Member::findOne(['username' => $this->username]);
        if (!$member)         //验证用户是否存在
        {
          return $this->addError('username','用户不存在');
        }
    }
    public function validatePassword()
    {
        $member = Member::findOne(['username' => $this->username]);
        if (!\Yii::$app->security->validatePassword($this->password, $member->password_hash))
        {
            return $this->addError('password','密码不正确');
        }
    }



    //登录的时候讲cookie的数据同步到数据表中
  /*  public function cart()
    {
        $cookie = \Yii::$app->request->cookies;
        $cart = $cookie->get('cat');         //获取到cookie的数据
        if($cart) {
            $carts = unserialize($cart->value());
            foreach ($carts as $goods_id=>$amount){
                if(){

                }
            }
        }
    }*/
}