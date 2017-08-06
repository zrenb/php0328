<?php
namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use yii\web\Controller;

class UpdatePageController extends Controller
{
    //更新首页静态化
    public function actionHomePage()
    {

        $categorys = GoodsCategory::find()->where(['=','parent_id',0])->all();
        $goods = Goods::find()->all();
        $homePage = $this->renderPartial('index',['categorys'=>$categorys,'goods'=>$goods]);
        file_put_contents(\Yii::getAlias('@frontend/web/update-page/index.html'),$homePage);

    }

    //商品详情
    public function actionGoodsPage()
    {

    }
}
