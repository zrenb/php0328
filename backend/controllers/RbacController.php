<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class RbacController extends Controller
{
    //权限添加
    public function actionAddPermission()
    {
        $model = new PermissionForm();   //实例化表单模型
        $model->scenario = PermissionForm::SCENARIO_ADD;       //场景
        if($model->load(\Yii::$app->request->post()) && $model->validate())     //表单数据加载并验证成功
        {
            $authManager = \Yii::$app->authManager;
            //创建权限
            $permission = $authManager->createPermission($model->name);
            $permission->description = $model->description;
            //添加权限
            $authManager->add($permission);
            //添加成功
            \Yii::$app->session->setFlash('success','权限添加成功');
            return $this->redirect(['index-permission']);
        }
       return $this->render('permission-add',['model'=>$model]);         //视图显示
    }
    //权限修改
    public function actionEditPermission($name)
    {
        //获取需要修改的权限
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        //实例化表单模型 并把值付给模型
        $model = new PermissionForm();
        $model->name = $permission->name;
        $model->description = $permission->description;
        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            $permission->name = $model->name;
            $permission->description = $model->description;
            //修改权限
            $authManager->update($name,$permission);
            \Yii::$app->session->setFlash('success','修改权限成功');
            return $this->redirect(['index-permission']);
        }
        //视图修改页面
        return $this->render('permission-add',['model'=>$model]);

    }
    //权限列表
    public function actionIndexPermission()
    {
        //获取所有权限
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        //视图显示
        return $this->render('permission-index',['permissions'=>$permissions]);
    }
    //权限删除
    public function actionDelPermission($name)
    {
        //获取到需要删除的数据
        $authManaget = \Yii::$app->authManager;
        $permission = $authManaget->getPermission($name);
        $authManaget->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['index-permission']);

    }



    //角色添加
    public function actionAddRole()
    {
        //实例化表单模型
        $model = new RoleForm();
        $model->scenario = RoleForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate())     //表单数据加载并验证成功
        {
            //创建角色
            $authManager = \Yii::$app->authManager;
            $role = $authManager->createRole($model->name);
            $role->description = $model->description;
            //保存角色
            $authManager->add($role);
            //给角色赋予权利
            //var_dump($model->permissions);exit;
            if(is_array($model->permissions))
            {
                foreach ($model->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);
                }
            }
            \Yii::$app->session->setFlash('success','角色添加成功');
            return $this->redirect(['index-role']);
        }
        return $this->render('role-add',['model'=>$model]);
    }
    //角色修改
    public function actionEditRole($name)
    {
        //获取需要修改的数据
        $authManaget = \Yii::$app->authManager;
        $role = $authManaget->getRole($name);
        $permissions = $authManaget->getPermissionsByRole($name);
        //实例化表单
        $model = new RoleForm();

        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            ////全部取消关联
            $authManaget->removeChildren($role);
            $role->name = $model->name;
            $role->description = $model->description;
            $authManaget->update($name,$role);
            //重新关联
            if(is_array($model->permissions))
            {
                foreach ($model->permissions as $permissionName)
                {
                    $permission = $authManaget->getPermission($permissionName);
                    $authManaget->addChild($role,$permission);
                }
            }
          \Yii::$app->session->setFlash('success','角色修改成功');
            return $this->redirect(['index-role']);
        }


        $model->name = $role->name;
        $model->description = $role->description;
        $model->permissions = ArrayHelper::map($permissions,'name','name');
        //视图显示
        return $this->render('role-add',['model'=>$model]);
    }
    //角色列表
    public function actionIndexRole()
    {
        //获取所有角色数据
        $authManaget = \Yii::$app->authManager;
        $roles = $authManaget->getRoles();
        //var_dump($roles);exit;
        return $this->render('role-index',['roles'=>$roles]);
    }
    //角色删除
    public function actionDelRole($name)
    {
        //获取到需要删除的数据
        $autnManager = \Yii::$app->authManager;
        $role = $autnManager->getRole($name);
        //var_dump($role);exit;
        $autnManager->remove($role);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['index-role']);

    }


    //过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}