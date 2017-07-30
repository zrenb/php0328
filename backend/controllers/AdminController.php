<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\ChpwForm;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class AdminController extends Controller
{
    ///管理员列表
    public function actionIndex()
    {
        $admins = Admin::find()->all();
        return $this->render('index',['admins'=>$admins]);
    }

    ////定义验证码
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>4,
                'maxLength'=>4,
            ]

        ];
    }

    //管理员添加
    public function actionAdd()
    {
        $model=new Admin(['scenario'=>Admin::SCENARIO_ADD]);
        ////接收表单数据并保存
       $request = new Request();
       if($request->isPost)
       {
           //var_dump($request->post());exit;
           $model->load($request->post());
           if($model->validate())
           {

               //var_dump($model->role);exit;
               ////用户名加密
               $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
               $model->status = 1;
               $model->auth_key = \Yii::$app->security->generateRandomString();
               $model->save(false);
                //添加角色
               $authManager = \Yii::$app->authManager;
               if(is_array($model->roles))
               {
                   foreach ($model->roles as $role){
                       $role = $authManager->getRole($role);
                       $authManager->assign($role,$model->id);
                   }
               }


               return $this->redirect(['admin/index']);
           }
       }
        return $this->render('add',['model'=>$model]);
    }


    //修改管理员
    public function actionEidt($id)
    {
        $model = Admin::findOne(['id'=>$id]);
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);

       $model->roles=ArrayHelper::map($roles,'name','name');

        if($model == null )
        {
            throw new NotFoundHttpException('你需修改的数据不存在');
        }else{
            ////接收修改后的表单数据并保存
            $request = new Request();
            if($request->isPost)
            {
                //var_dump($model);exit;
                $model->load($request->post());
                if($model->validate())
                {
                    ////用户名加密
                    $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                    $model->save(false);
                    //修改管理员的角色
                    if(is_array($model->roles))
                    {
                        $authManager->revokeAll($id);
                        foreach ($model->roles as $roleName){
                            $role = $authManager->getRole($roleName);
                            $authManager->assign($role,$model->id);
                        }
                    }
                    return $this->redirect(['admin/index']);
                }
            }
            return $this->render('add',['model'=>$model]);
        }

    }



    //管理员删除
    public function actionDel($id)
    {
        ////接收需要删除的管理员数据
        $admin = Admin::findOne(['id'=>$id]);

        if($admin == null)
        {
            throw new NotFoundHttpException('你需删除的数据不存在');
        }else{
            $admin->delete();
            $authManager = \Yii::$app->authManager;
            $authManager->revokeAll($id);
            return $this->redirect(['admin/index']);
        }

    }


    //管理员登录
    public function actionLogin()
    {
        $login = new LoginForm();
        //接收表单数据
        $request = new Request();
       //var_dump($request->userIP);exit;
        //Yii::$app->formatter->asRelativeTime
        //var_dump(\Yii::$app->formatter->asRelativeTime());exit;
       // var_dump($request);exit;
        if($request->isPost)
        {
            $login->load($request->post());
            if($login->validate() && $login->login())
            {
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['admin/index']);
            }else{

            }
        }
        return $this->render('login',['login'=>$login]);
    }


    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        $this->redirect(['admin/login']);

    }


    //////检查是否登录成功
    public function actionUser(){


        //可以通过 Yii::$app->user 获得一个 User实例，
        //$user = Yii::$app->user;

        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;

        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;

        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($id);
        exit;
    }


    //修改密码
    public function actionChpw()
    {
        if($admin=\Yii::$app->user->identity)
        {
            $model = new ChpwForm();

            //实例化request
            $request = new Request();
            if($model->load($request->post()) && $model->validate())
            {
                //var_dump($request->post());exit;

               // var_dump($model);exit;
                if(\Yii::$app->security->validatePassword($model->oldPassword,$admin->password_hash))
                {
                    if(!\Yii::$app->security->validatePassword($model->newPassword,$admin->password_hash))
                    {
                        if($model->newPassword === $model->okPassword)
                        {
                            $admin->password_hash = \Yii::$app->security->generatePasswordHash($model->newPassword);

                            $admin->save();
                            \Yii::$app->session->setFlash('success','修改成功，请重新登录');
                           return $this->redirect(['admin/login']);
                        }else{
                             $model->addError('newPassword','新密码和确认密码不一致');
                        }
                    }else{
                         $model->addError('newPassword','新密码不能和旧密码一样');
                    }
                }else{
                     $model->addError('oldPassword','旧密码不正确');
                }
            }


            return $this->render('chpw',['model'=>$model,'admin'=>\Yii::$app->user->identity]);
        }
        return $this->redirect(['admin/login']);
    }


    //过滤器
       public function behaviors()
       {
           return [
               'rbac'=>[
                   'class'=>RbacFilter::className(),
                   'except'=>['login','logout','captcha'],
               ]
           ];
       }
}