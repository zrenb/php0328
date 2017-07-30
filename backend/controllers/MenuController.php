<?php


namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MenuController extends Controller
{
    //添加菜单
    public function actionAddMenu()
    {
        $model = new Menu();    //实例化表单模型
        $model->scenario = Menu::EVENT_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect('index-menu');
        }
        $url = ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','name'); //获取所有权限
        $parent_ids = ArrayHelper::map(Menu::find()->where(['=','parent_id','0'])->all(),'id','label');  //获取所有菜单父id

        //var_dump($parent_ids);exit;
        return $this->render('menu-add',['model'=>$model,'parent_ids'=>$parent_ids,'url'=>$url]);

    }


    //菜单列表
    public function actionIndexMenu()
    {
        //获取所有菜单
        $menus = Menu::find()->where(['=','parent_id',0])->all();
        return $this->render('menu-index',['menus'=>$menus]);
    }

    //菜单修改
    public function actionEditMenu($id)
    {
        $model = Menu::findOne(['id'=>$id]);
        if($model == null)
        {
            throw new NotFoundHttpException('warning','你修改的数据不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index-menu');
        }
        $dires = ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','name'); //获取所有权限
        $parent_ids = ArrayHelper::map(Menu::find()->where(['=','parent_id','0'])->all(),'id','name');  //获取所有菜单父id
        array_unshift($dires,'--请选择--');
        array_unshift($parent_ids,'--请选择--');
        //var_dump($parent_ids);exit;
        return $this->render('menu-add',['model'=>$model,'parent_ids'=>$parent_ids,'dires'=>$dires]);
    }

    //菜单删除
    public function actionDelMenu($id)
    {
        $menu = Menu::findOne(['id'=>$id]);
        if($menu == null)
        {
           //throw new NotFoundHttpException('你需删除的数据不存在');
           \Yii::$app->session->setFlash('warning','你需删除的数据不存在，或已经被删除');
            return $this->redirect('index-menu');
        }
        $menu->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect('index-menu');
    }



    //过滤器
   public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}