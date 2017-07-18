<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 14:47
 */
namespace backend\controllers;

use backend\models\Brand;
use yii\web\Controller;

class BrandController extends Controller
{
    /////品牌列表
    public function actionIndex()
    {
        ////获取所有数据
        $branks = Brand::find()->all();
        ////视图品牌列表
        return $this->render('index',['branks'=>$branks]);
    }




    /////品牌添加
    public function actionAdd()
    {
        ////实例化表单组件
        $model = new Brand();

        ////添加页面  视图
        return $this->render('add',['model',$model]);
    }

}