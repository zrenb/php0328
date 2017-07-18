<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 14:47
 */
namespace backend\controllers;

use backend\models\Brand;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    /////品牌列表
    public function actionIndex()
    {
        ////分页
        $query=Brand::find()->where(['<>','status',-1]);
        ///得出数据总条数
        $total=$query->count();
        $pageSize=5;

        ////分页工具类
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pageSize
        ]);

        $branks = $query->limit($page->limit)->offset($page->offset)->all();



        ////获取所有数据
       // $branks = Brand::find()->all();
        ////视图品牌列表
        return $this->render('index', ['branks' => $branks,'page'=>$page]);
    }


    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::className(),
                'minLength' => 4,
                'maxLength' => 4,
            ]
        ];
    }


    /////品牌添加
    public function actionAdd()
    {
        ////实例化表单组件
        $model = new Brand();
        ////实例化一个request
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());

            /////创建一个处理文件对象
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            //var_dump($model->imageFile);exit;
            if($model->imageFile)
            {
                ////判断数据是否验证成功
                if ($model->validate()) {
                    /////处理图片
                    $imagePath = \Yii::getAlias('@webroot') . '/upload/' . date('Ymd');
                    if (!is_dir($imagePath)) {
                        mkdir($imagePath);
                    }
                    $fileName = '/upload/' . date('Ymd') . '/' . 'brand' . uniqid() . '.' . $model->imageFile->extension;
                    ////保存图片
                    $model->imageFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    /// 保存图片路径到数据库
                    $model->logo = $fileName;
                    $model->save(false);
                    return $this->redirect(['brand/index']);
                }
            }elseif($model->validate())
            {
                $model->save(false);
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }

        }
        ////添加页面  视图
        return $this->render('add', ['model' => $model]);
    }


    /////品牌修改
    public function actionEidt($id)
    {
        /////获取需要修改的数据
        $model = Brand::findOne(['id' => $id]);

        ////实例化一个request
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            /////创建一个处理文件对象
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->imageFile)
            {
                ////判断数据是否验证成功
                if ($model->validate()) {
                    /////处理图片
                    $imagePath = \Yii::getAlias('@webroot') . '/upload/' . date('Ymd');
                    if (!is_dir($imagePath)) {
                        mkdir($imagePath);
                    }
                    $fileName = '/upload/' . date('Ymd') . '/' . 'upload' . uniqid() . '.' . $model->imageFile->extension;
                    ////保存图片
                    $model->imageFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    /// 保存图片路径到数据库
                    $model->logo = $fileName;
                    $model->save(false);
                    return $this->redirect(['brand/index']);
            }

            }elseif ($model->validate())
            {
                $model->save(false);
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        ////展示修改页面
        return $this->render('eidt', ['model' => $model]);
    }




    public function actionDel($id){
        /////获取需要删除的数据
        $brand=Brand::findOne(['id'=>$id]);
        $brand->status= -1;
        $brand->save();
        //var_dump($brand->getErrors());exit;
       return $this->redirect(['brand/index']);
    }
}