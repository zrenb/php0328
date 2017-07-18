<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 21:44
 */
namespace backend\controllers;


use backend\models\Category;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class CategoryController extends Controller
{
    ////分类列表
    public function actionIndex()
    {

        ////分页
        $query=Category::find()->where(['!=','status','-1']);
        $total=$query->count();
        $pageSize=5;

        /////分页工具类
        $pager= new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pageSize
        ]);

        ////得到所有分类数据
        //$categorys = Category::find()->all();
        $categorys = $query->limit($pager->limit)->offset($pager->offset)->all();
        ////显示页面
        return $this->render('index',['categorys'=>$categorys,'pager'=>$pager]);
    }



    ////分类添加
    public function actionAdd()
    {
        ////实例化表单模型
        $model = new Category();
        ////实例化一个request
        $request = new Request();
        if($request->isPost)
        {
           $model->load($request->post());
           if($model->validate())
           {
               $model->save();
               return $this->redirect(['category/index']);
           }
        }
        ////视图表单页面
        return $this->render('add',['model'=>$model]);
    }



    ////分类修改
    public function actionEidt($id)
    {
        ////获取需要修改的数据
        $model = Category::findOne(['id'=>$id]);
        ////实例化一个request
        $request = new Request();
        if($request->isPost)
        {
            $model->load($request->post());
            if($model->validate())
            {
                $model->save();
                return $this->redirect(['category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }else{
            var_dump($model->getErrors());exit;
        }
        ////视图修改页面
        return $this->render('eidt',['model'=>$model]);
    }



    ////分类删除
    public function actionDel($id)
    {
        ////获取到需要删除的数据
        $category = Category::findOne(['id'=>$id]);
        $category->status=-1;
        $category->save();
        return $this->redirect(['category/index']);
    }
}