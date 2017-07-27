<?php
namespace backend\models;


use yii\base\Model;

class ChpwForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $okPassword;
    public $code;
  public function attributeLabels()
  {
      return [
          'oldPassword'=>'旧密码',
          'newPassword'=>'新密码',
          'okPassword'=>'确认密码',
      ];
  }
  public function rules()
  {
      return [
          [['oldPassword','newPassword','okPassword'],'required'],
          ///旧密码是是否正确
      ];
  }
}