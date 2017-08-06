<?php
namespace backend\controllers;

use frontend\models\Order;
use yii\web\Controller;

class OrderController extends Controller
{
    //处理超时订单
    public function actionClean()
    {
        //设置订单的支付的时间
        //$spTime = 60;
        //查询出状态为待支付的订单 ，订单创建时间超过订单的支付时间 全部商品
        /*$goods = Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-3600])->all();
        //循环修改商品的状态
        foreach ($goods as $good){
            $good->status=0;
            $good->save();
        }*/
        //利用死循环，让此功能一直执行
        while (true){
            Order::updateAll(['status'=>0],'status=1 AND create_time <'.(time()-3600) );
            //休息1秒
            sleep(1);
           // echo '清理完成'.date('Y-m-d H:i:s');
        }

    }
}