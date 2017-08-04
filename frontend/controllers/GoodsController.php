<?php

namespace frontend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsPic;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use backend\models\GoodsGallery;

class GoodsController extends Controller
{
    public $enableCsrfValidation=false;
    public $layout=false;
    public function actionIndex()
    {
        $brands = Brand::find()->all();
        $categorys = GoodsCategory::find()->where(['=','parent_id',0])->all();
        $goods = Goods::find()/*->where(['=','id','category_id'])*/->all();
        return $this->render('index',['brands'=>$brands,'categorys'=>$categorys,'goods'=>$goods]);
    }

    public function actionGoodsList($category_id)
    {
        //var_dump($category_id);exit;

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

            //////////////////////////////////////////登录数据表/////////////////////////////////////////////////////////
            $goods_ids = Cart::find()->select('goods_id')->asArray()->column();
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

            }else{
                //登录状态
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
}