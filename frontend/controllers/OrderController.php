<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Abres;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    public $enableCsrfValidation=false;
    public $layout=false;
    public function actionGoodsList(){

        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login-member']);
        }
        $member_id = \Yii::$app->user->id;
        //var_dump($member_id);exit;
        $addresses = Abres::find()->where(['=','user_id',$member_id])->all();       //收货地址
        $deliveries = Order::$deliveries;                                           //配送方式
        $paymentes = Order::$paymentes;                                             //支付方式
        $carts = Cart::find()->where(['=','member_id',$member_id])->all();
        $goods_ids = Cart::find()->select('goods_id')->where(['=','member_id',$member_id])->column();          //购物车数据
        $goods = Goods::find()->where(['in','id',$goods_ids])->all();
        //var_dump($goods);exit;




        return $this->render('order-list',['addresses'=>$addresses,'deliveries'=>$deliveries,'paymentes'=>$paymentes,'carts'=>$carts,'goods'=>$goods]);



    }


    public function actionPlaceOrder()
    {
        $order = new Order();
        $tsanction = \Yii::$app->db->beginTransaction();//开启事物
        if($order->load(\Yii::$app->request->post()) && $order->validate()){
            try{

                $member_id = \Yii::$app->user->id;   //获取用户id
                $address_id=$order->address_id;       //收货地址
                //return Json::encode(['sss'=>$address_id]);
                //$delivery_id=$model->delivery_id;     //快递方式
                //$pay_id=$model['pay_id'];       //支付方式
                //根据用户购物车商品的ID查询出商品的商品的库存信息
                $goods_ids = Cart::find()->select('goods_id')->where(['=','member_id',$member_id])->column();
                $goods = Goods::find()->where(['in','id',$goods_ids])->all();
                foreach ($goods as $good){

                        $address=Abres::findOne(['id'=>$address_id]);
                        ////////收货地址

                        $order->member_id=$member_id;
                        $order->name=$address->name;
                        $order->province=$address->province;
                        $order->city=$address->city;
                        $order->area=$address->area;
                        $order->address=$address->detail;
                        $order->tel=$address->tel;
                        //快递方式
                        $delivery = Order::$deliveries[$order->delivery_id];
                        $order->delivery_name=$delivery['name'];
                        $order->delivery_price=$delivery['price'];
                        //支付方式
                        //var_dump(Order::$paymentes[$order->payment_id]['name']);
                        $order->payment_name=Order::$paymentes[$order->payment_id]['name'];
                        //订单总额
                        $order->total=$good->shop_price*$good->cart->amount;
                        $order->status='0';
                        $order->create_time=time();
                        $order->save();
                        //var_dump($order->getErrors());
                        if($good->stock>=$good->cart->amount){
                        //var_dump($order->getErrors());exit;
                        //保存商品订单详情
                        $order_goods = new OrderGoods();
                        $order_goods->order_id=$order->id;
                        $order_goods->goods_id=$good->id;
                        $order_goods->goods_name=$good->name;
                        $order_goods->LOGO=$good->LOGO;
                        $order_goods->price=$good->shop_price;
                        $order_goods->amount=$good->cart->amount;
                        $order_goods->total=$order->total;
                        $order_goods->save();
                        //商品库存
                        $good->stock=$good->stock-$good->cart->amount;
                        $good->save();
                    }else{
                        throw new Exception('抱歉！库存不足');
                    }
                }
                //提交事务
                $tsanction->commit();
               // return Json::encode('success','交易成功');

            }catch (Exception $e){
               $tsanction->rollBack(); //回滚
                return Json::encode(['status' => false, 'msg' => '交易失败']);
            }
           Cart::deleteAll(['member_id'=>$member_id]); //清空购物车数据
            return Json::encode(['status' => true, 'msg' => '交易成功']);
        }
    }
    public function actionPaySuccess(){
        return $this->render('success');
    }
    public function actionList()
    {
        $member_id = \Yii::$app->user->id;
        $model = new Order();
        $goods = Order::find()->where(['=','member_id',$member_id])->all();
        var_dump($goods);
    }
}