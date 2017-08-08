<?php

namespace frontend\controllers;

use backend\components\SphinxClient;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodSearchForm;
use backend\models\GoodsPic;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use frontend\models\Cart;
use frontend\models\GoodsSearchForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;
use backend\models\GoodsGallery;

class GoodsController extends Controller
{
    public $enableCsrfValidation=false;
    public $layout=false;
    //首页
    public function actionIndex()
    {
        $categorys = GoodsCategory::find()->where(['=','parent_id',0])->all();
        return $this->render('index',['categorys'=>$categorys]);
    }

    public function actionSearch()
    {
        //接收搜索传过来的数据
        $keywords = \Yii::$app->request->get('keyword');
        $query = Goods::find();
        if($keywords)
        {
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetArrayResult ( true );
            $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);   //匹配模式
            $cl->SetLimits(0, 1000);    //查询条数
            $info = $keywords;
            $res = $cl->Query($info, 'goods');
            if(isset($res['matches'])){
                $ids = ArrayHelper::getColumn($res['matches'],'id');
                $query->where(['in','id',$ids]);
            }else{
                $query->where(['id'=>0]);
                return ;
            }
            $goods=$query->all();
            return $this->render('goods-list',['goods'=>$goods]);
        }

    }





    public function actionGoodsList()
    {
        $category_id=\Yii::$app->request->get('category_id');
        $cate = GoodsCategory::findOne(['id'=>$category_id]);

        if($cate->depth == 0)
        {
            /*////分页
            $total = $query->count();
            $pageSize = 5;
            ////分页工具类
            $pager = new Pagination([
                'totalCount'=>$total,
                'defaultPageSize'=>$pageSize
            ]);
            $goods = $query->limit($pager->limit)->offset($pager->offset)->all();*/
            //一级分类
            $ids = $cate->leaves()->asArray()->column();
            $goods = Goods::find()->where(['in','goods_category_id',$ids])->all();
           // var_dump($goods);exit;
            return $this->render('goods-list',['goods'=>$goods]);

        }elseif($cate->depth == 1)
        {
            //二级分类
            $categorys = GoodsCategory::find()->select('id')->where(['=','parent_id',$category_id])->asArray()->column();
            $goods = Goods::find()->where(['in','goods_category_id',$categorys])->all();
            //var_dump($goods);exit;
            return $this->render('goods-list',['goods'=>$goods]);
        }elseif($cate->depth == 2){
            //三级分类

            $goods = Goods::find()->where(['=','goods_category_id',$category_id])->all();
            //var_dump($goods);exit;
            return $this->render('goods-list',['goods'=>$goods]);
        }
    }





        //商品详情
    public function actionGood($id)
    {
        $goodPices = GoodsGallery::find()->where(['=','goods_id',$id])->all();
        $good = Goods::findOne(['id'=>$id]);
        //var_dump($good);
        return $this->render('good',['goodPices'=>$goodPices,'good'=>$good]);
    }


    //添加购物车
    public function actionAddCart($goods_id,$amount)
    {
        //$cats = [$goods_id=>$amount];   //数据已此格式保存
        //在添加购物车是要先判断购物车是否有该商品
        if(\Yii::$app->user->isGuest)
        {
            //先获取到购物车的商品数据
            $cookies = \Yii::$app->request->cookies;
            $cart = $cookies->get('cat');

            //var_dump($cart);exit;
            if($cart == null)
            {
                $cats = [$goods_id=>$amount];   //购物车，没有商品数据
            }else{
                $cats=unserialize($cart->value); //获取到购物车的存在的数据
                if(isset($cats[$goods_id]))
                {
                    $cats[$goods_id]+=$amount;      //购物车已有该商品 商品累加
                }else{
                    $cats[$goods_id] = $amount;         //购物车没有该商品 商品添加
                }
            }

            //用户在没有登录的情况下  保存在cookie中
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cat',      //名称
                'value'=>serialize($cats),     //值
                'expire'=>7*24*3600+time(),
            ]);
            $cookies->add($cookie);
            return $this->redirect('show-cart');
        }else{
 //////////////////////////////////////////////////////////用户登录的情况下///////////////////////////////////
            //判断是否存在此商品
            $member_id = \Yii::$app->user->getId();
            $model = Cart::find()->andWhere(['=','member_id',$member_id])->andWhere(['=','goods_id',$goods_id])->asArray()->all();
            //var_dump($model);exit;
            //不存在
            if($model == null)
            {
                $model = new Cart();
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->member_id = \Yii::$app->user->getId();
                $model->save();
                return $this->redirect(['goods/show-cart']);
             //存在
            }else{
                    $model=Cart::findOne(['goods_id'=>$goods_id]);
                    $model->amount+=$amount;
                    $model->save();
                    return $this->redirect(['goods/show-cart']);
            }
        }

    }



    //显示购物车页面
    public function actionShowCart()
    {
        if(\Yii::$app->user->isGuest){

            $cookies = \Yii::$app->request->cookies;
            $cart = $cookies->get('cat');
            //var_dump($cart);exit;
            if($cart == null)
            {
                \Yii::$app->session->setFlash(['warning','还没有选好的商品哦！']);
                return $this->redirect(['goods/index']);
                exit;
            }else{
                $carts = unserialize($cart->value);
                //var_dump($carts);exit;
                $goods = Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();

            }
            return $this->render('cart-list',['goods'=>$goods,'carts'=>$carts]);

        }else{

            //////////////////////////////////////////登录数据表////////////////////////////////////////////////////////
            $member_id = \Yii::$app->user->id;
            //var_dump($member_id);exit;
            $goods_ids = Cart::find()->select('goods_id')->where(['=','member_id',$member_id])->asArray()->column();
            $carts = Cart::find()->asArray()->all();

            //var_dump($goods_ids);exit;
            $goods = Goods::find()->where(['in','id',$goods_ids])->all();
            $carts = ArrayHelper::map($carts,'goods_id','amount');
            return $this->render('cart-list',['goods'=>$goods,'carts'=>$carts]);
        }




    }
    //修改购物车数量
    public function actionChangeNum()
    {
        //接收需要修改的数据 注：此数据是通过AJAX发送过来的
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if($goods_id != null && $amount != null)            //验证数据的真实性
        {
            if(\Yii::$app->user->isGuest)                   //验证证用户是否处在登录状态
            {
                //先获取到购物车的商品数据
                $cookies = \Yii::$app->request->cookies;
                $cart = $cookies->get('cat');
                //var_dump($cart);exit;
                if($cart == null)
                {
                    $cats = [$goods_id=>$amount];   //购物车，没有商品数据
                }else{
                    $cats=unserialize($cart->value); //获取到购物车的存在的数据
                    if(isset($cats[$goods_id]))
                    {
                        $cats[$goods_id] = $amount;      //购物车已有该商品
                    }else{
                        $cats[$goods_id] = $amount;         //购物车没有该商品 商品添加
                    }
                }
                //修改好还是保存到cookie中
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie([
                    'name'=>'cat',      //名称
                    'value'=>serialize($cats),     //值
                    'expire'=>7*24*3600+time(),
                ]);
                $cookies->add($cookie);
                return '我是cookie修改成功';
 //////////////////////////////////////////////性感的分界线////////////////////////////////////////////////////////////////////////////
            }else{
                $model = Cart::findOne(['goods_id'=>$goods_id]);
               if($model == null)
               {
                   $model = new Cart();
                   $model->goods_id = $goods_id;
                   $model->member_id = \Yii::$app->user->getId();
                   $model->amount = $amount;
                   $model->save();
               }else{
                   $model->amount = $amount;
                   $model->save();
               }
                return '我是登录修改成功';
            }
        }else{
            \Yii::$app->session->setFlash('warning','修改数据不出在');
            return $this->redirect(['goods/cart-list']);
        }
    }



    //删除购物车数据
    public function actionGoodsDel()
    {
        //接收需要删除的数据
        $goods_id = \Yii::$app->request->get('goods_id');
        if($goods_id != null)
        {
            if(\Yii::$app->user->isGuest)
            {
                unset($goods_id);
            }else{
                //登录状态
                $good = Cart::findOne(['goods_id'=>$goods_id]);
                $good->delete();
            }
            return "删除成功";
        }else{
            \Yii::$app->session->setFlash(['warning','删除的数据不出在']);
            return $this->redirect(['goods/show-cart']);
        }

    }




    public function actionUser()
    {
     var_dump(\Yii::$app->user->getId());
    }
    public function actionCook(){
        $cook = \Yii::$app->request->cookies;
        var_dump(unserialize($cook->getValue('cat')));
    }


    public function actionRedis()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $a = $redis->set(1,'00');
        var_dump($a);

    }
    //微信支付
    public function actionPay($order=null)
    {
        $options=\Yii::$app->params['wechat'];
        $app = new Application($options);
        $payment = $app->payment;

        //生成一个微信支付订单
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => 'iPad mini 16G 白色',
            'detail'           => 'iPad mini 16G 白色',
            'out_trade_no'     => '1217752501201407033233368018',
            'total_fee'        => 5388, // 单位：分
            'notify_url'       => 'http://www.yii2shop.com/site/notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            //'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);


        //调统一下单api
        //返回一个code_url链接
        //将返回的code_url生成有个二维码
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
        }
        var_dump($result);

    }

    //微信返回提示信息
    public function actionBcak(){

    }
}