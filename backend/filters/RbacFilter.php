<?php
namespace backend\filters;


use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class RbacFilter extends ActionFilter
{
    //执行之前
    public function beforeAction($action)
    {
       //判断用户是否登录
        if(\Yii::$app->user->isGuest)
        {
            //return $action->controller->redirect(['admin/login']);
            //return $action->controller->redirect(['admin/login'])->send();
           return $action->controller->redirect(\Yii::$app->user->loginUrl);
        }
        //判断用户是否有权利
        if(!\Yii::$app->user->can($action->uniqueId))
        {
            throw  new ForbiddenHttpException('403','你没权限操作');
        }
        // parent::beforeAction($action); // 是指放行的意思 相当于return true   相反就是return false
        return parent::beforeAction($action);
    }
}