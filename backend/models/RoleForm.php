<?php
/**
 *角色
 */
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model
{
    public $name; //角色名称
    public $description;    //角色描述
    public $permissions=[]; //权限
    const SCENARIO_ADD = 'add';
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions'=>'权限'
        ];
    }
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
            ['permissions','safe'],
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
