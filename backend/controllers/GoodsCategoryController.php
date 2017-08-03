<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 13:01
 */
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends Controller
{

    /*public function actionIndex()
    {
        echo 'come on';
    }*/
    ////添加商品分类
    /*public function actionAdd()
    {
        $model = new GoodsCategory();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->parent_id){
                    ////非一级分类
                    $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    //var_dump($category->id);exit;
                    $model->prependTo($category);
                }else{
                    ///一级分类
                    $model->makeRoot();
                }
                return $this->redirect('goods-category/index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }*/
    /////测试嵌套集合
   /* public function actionTest()
    {
       $countries = new Menu(['name' => 'Countries']);
        $countries->makeRoot();
       /////添加一个根节点
         $category = new GoodsCategory();
        $category->name='汽车';
        $category->makeRoot();

   // var_dump($category->getErrors());
        echo '汽车';




        $category1 = new GoodsCategory(['name' => '梅赛德斯']);
        $category = GoodsCategory::findOne(['id'=>1]);
        //var_dump($category->id);exit;
        $category1->parent_id=$category->id;
        $category1->prependTo($category);

        //var_dump($category1->getErrors());
        echo '汽车';
    }*/
    /////测试zTree
   /* public function actionZtree()
    {
        return $this->renderPartial('ztree');
    }*/
    ////添加商品分类
   /* public function actionAdd1()
    {
        $model = new GoodsCategory();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                if($model->parent_id){
                    ////非一级分类
                    $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    //var_dump($category->id);exit;
                    $model->prependTo($category);
                }else{
                    ///一级分类
                    $model->makeRoot();
                }
                return $this->redirect('goods-category/index');
            }
        }
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categorys'=>$categories]);
    }*/
    ////商品分类列表
   public function actionIndex()
   {
       $categorys = GoodsCategory::find()->orderBy('tree,lft')->all();
       return $this->render('index',['categorys'=>$categorys]);
   }



   ////商品分类添加
   public function actionAdd()
   {
       $model = new GoodsCategory(['parent_id'=>0]);
       $model->scenario=GoodsCategory::EVENT_ADD;
       ///实例化一个request
       $request = new Request();
       if($request->isPost){
           $model->load($request->post());
           //var_dump($request->post());exit;
           $goodsCategorys = GoodsCategory::find()->select(['name'])->where(['=','parent_id',$model->parent_id])->all();    //判断同级是否有相同名称
           $name=[];
           foreach ($goodsCategorys as $goodsCategory) {
               $name[] = $goodsCategory->name;
           }
           /*echo "<pre/>";
           var_dump($model->name);
         var_dump($name);exit;*/
           ///判断同级是否有相同名称
           if(in_array($model->name,$name)){
                \Yii::$app->session->setFlash('warning','同级不能有相同的名称');
               return $this->redirect(['goods-category/add']);
               return false;
           }else{
               if($model->validate()){
                   ////验证成功后，再次判断是一级分类还是二级分类
                   if($model->parent_id){
                       ///非一级分类
                       $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                       $model->prependTo($category);
                   }else{
                       $model->makeRoot();
                   }
                   \Yii::$app->session->setFlash('success','添加成功');
                   return $this->redirect(['goods-category/index']);
              }
           }
       }

       $categorys = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
       return $this->render('eidt',['model'=>$model,'categorys'=>$categorys]);
   }



   ////商品分类修改
  /* public function actionEidt($id)
   {
        ///获取到需要修改的数据
       $model = GoodsCategory::findOne(['id'=>$id]);

        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->parent_id==0){
                \Yii::$app->session->setFlash('warning','此分类下有子分类不能修改');
                return $this->redirect(['goods-category/index','id'=>$model->id]);
                return false;
            }
            //var_dump($request->post());exit;
            $cats = GoodsCategory::find()->select(['name'])->where(['=','parent_id',$model->parent_id])->all();
            $name = [];
            foreach ($cats as $cat){
                $name[] = $cat->name;
            }
            //var_dump($name);exit;
            if(in_array($model->name,$name)){
                \Yii::$app->session->setFlash('warning','同级不能有相同的名称');
                return $this->redirect(['goods-category/eidt','id'=>$model->id]);
                return false;
            }else{
          if($model->validate()){
                    if($model->parent_id){
                        $goodCategory = Goods::findOne(['id'=>$model->parent_id]);
                        $model->prependTo( $goodCategory);
                    }else{
                        $model->makeRoot();
                        return $this->redirect(['goods_category/index']);
                    }
                }
            }

        }



       ///视图修改页面
       $categorys = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
       return $this->render('eidt',['model'=>$model,'categorys'=>$categorys]);
   }*/

    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //不能移动节点到自己节点下
            /*if($model->parent_id == $model->id){
                throw new HttpException(404,'不能移动节点到自己节点下');
            }*/
            try{
                //判断是否是添加一级分类
                if($model->parent_id){
                    //非一级分类


                    $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    if($category){
                        $model->appendTo($category);
                    }else{
                        throw new HttpException(404,'上级分类不存在');
                    }

                }else{
                    //一级分类
                    //bug fix:修复根节点修改为根节点的bug
                    if($model->oldAttributes['parent_id']==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }

                }
                \Yii::$app->session->setFlash('success','分类添加成功');
                return $this->redirect(['index']);
            }catch (Exception $e){
                $model->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
            }


        }

        ///视图修改页面
        $categorys = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('eidt',['model'=>$model,'categorys'=>$categorys]);
    }

   /////商品分类删除
    public function actionDel($id)
    {
       ///获取到需要删除的数据
        $category = Goods::findOne(['id'=>$id]);

        if($category == null){
            throw new HttpException(404,'分类不存在');
        }
        $categorys = Goods::find()->where(['=','parent_id',$category->id])->all();
        if($categorys != 0){
            \Yii::$app->session->setFlash('warning','此分类有子分类,不能被删除');
            return $this->redirect(['goods-category/index']);
            return false;
        }else{
            $category->delete();
        }

    }




    //过滤器
/*    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }*/
}