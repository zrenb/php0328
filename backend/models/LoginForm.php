<?php
 namespace backend\models;

 use yii\base\Model;

 class LoginForm extends Model
 {
     public $code;
     public $username;
     public $password;
     public $save_login;

     public function attributeLabels()
     {
         return [
             'username'=>'用户名',
             'password'=>'密码',
         ];
     }
     public function rules()
     {
         return [
             [['username','password'],'required'],
             ['save_login','boolean']
         ];
     }

     public function login()
     {
         $admin=Admin::findOne(['username'=>$this->username]);

         if($admin)             //用户存在
         {
             if(\Yii::$app->security->validatePassword($this->password,$admin->password))       //验证密码
             {
                 //\Yii::$app->user->login($admin);     //登录
                 \Yii::$app->user->login($admin, $this->save_login ? 3600 * 24 * 30 : 0);
                 $admin->last_login_ip = \Yii::$app->request->userIP;
                 $admin->last_login_time = time();
                 //var_dump($admin->last_login_time);
                 //var_dump($admin->last_login_ip);exit;
                 $admin->save();
               // var_dump($admin->getErrors());exit;

                 return true;
             }else
                 {
                 \Yii::$app->session->setFlash('warning','密码不正确');

             }
         }else{
             \Yii::$app->session->setFlash('warning','用户名不存在');

         }
         return false;
     }
 }