<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\HttpException;
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
        $model=new Admin();
        ////接收表单数据并保存
       $request = new Request();
       if($request->isPost)
       {
           $model->load($request->post());
           if($model->validate())
           {
               ////用户名加密
               $model->password = \Yii::$app->security->generatePasswordHash($model->password);
               $model->save(false);
               return $this->redirect(['admin/index']);
           }
       }
        return $this->render('add',['model'=>$model]);
    }


    //修改管理员
    public function actionEidt($id)
    {
        $model = Admin::findOne(['id'=>$id]);
        ////接收修改后的表单数据并保存
        $request = new Request();
        if($request->isPost)
        {
            $model->load($request->post());
            if($model->validate())
            {
                ////用户名加密
                $model->password = \Yii::$app->security->generatePasswordHash($model->password);
                $model->save(false);
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }



    //管理员删除
    public function actionDel($id)
    {
        ////接收需要删除的管理员数据
        $admin = Admin::findOne(['id'=>$id]);
        $admin->delete();
        return $this->redirect(['admin/index']);
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


    //自动登录


}