<?php
/**
 * 权限
 */

namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model
{
    public $name;   //权限名称
    public $description;       //权限描述
    const SCENARIO_ADD = 'add';
    public function attributeLabels()   //属性
    {
        return [
            'name'=>'权限名称',
            'description'=>'权限描述',
        ];
    }

    public function rules()     //规则
    {
        return [
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD]
        ];
    }

    public function validateName()
    {
        $authManage = \Yii::$app->authManager;
        if($authManage->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }

    }
}
