<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 14:46
 */
namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //////文章列表
    public function actionIndex()
    {

        ////分页
        $query = Article::find()->where(['!=','status',-1])->orderBy(['sort'=>'desc']);
        $total=$query->count();
        $pageSize=4;

        ////分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pageSize
        ]);
        //$articles = $query->limit($pager->limit)->offset($pager->offset)->all();
        $articles = $query->limit($pager->limit)->offset($pager->offset)->all();
        /////获取所有文章数据
        //$articles = Article::find()->all();

        ///显示数据列表
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);
    }



    ////文章添加
    public function actionAdd()
    {
        /////实例化个表单模型
        $model = new Article();
        $model1 = new ArticleDetail();
        ////实例化一个request
        $request = new Request();
        if($request->isPost)
        {
            $model->load($request->post());
            $model1->load($request->post());
            if($model->validate() && $model1->validate()){
                $model->create_time=time();
                $model->save();
                $model1->article_id=$model->id;
                $model1->save();
                return $this->redirect(['article/index']);
            }
        }
        ////视图表单添加页面
        return $this->render('add',['model'=>$model,'model1'=>$model1]);
    }




    ////文章修改
    public function actionEidt($id)
    {
        ////获取到需要修改的数据
        $model = Article::findOne(['id'=>$id]);
        $model1 = ArticleDetail::findOne(['article_id'=>$model->id]);
        ////实例化一个request
        $request = new Request();
        if($request->isPost)
        {
            $model->load($request->post());
            $model1->load($request->post());
            if($model->validate() && $model1->validate()){
                $model->save();
                $model1->save();
                return $this->redirect(['article/index']);
            }
        }
        ////视图修改页面
        return $this->render('add',['model'=>$model,'model1'=>$model1]);
    }




    ////文章删除
    public function actionDel($id)
    {
        ////获取需要删除的数据
        $article = Article::findOne(['id'=>$id]);
        $article->status=-1;
        $article->save();
        return $this->redirect(['article/index']);
    }


    ///文章详情
    public function actionDetail($id)
    {
        ////获取需要的文章详情
        $model = Article::findOne(['id'=>$id]);
        $model1 = ArticleDetail::findOne(['article_id'=>$model->id]);
        ////视图显示页面
        return $this->render('detail',['model'=>$model,'model1'=>$model1]);
    }


    ////编辑器
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }


    ////回收站
    public function actionTrash()
    {
        ///查询出来状态为删除的数据
        $articles = Article::find()->where(['=','status','-1'])->all();
        /////视图显示
        return $this->render('trash',['articles'=>$articles]);

    }



    ///恢复隐藏
    public function actionHide($id)
    {
        $article = Article::findOne(['id'=>$id]);
        $article->status=0;
        $article->save();
        return $this->redirect(['article/index']);
    }


    public function actionReg($id)
    {
        $article = Article::findOne(['id'=>$id]);
        $article->status=1;
        $article->save();
        return $this->redirect(['article/index']);
    }


    public function actionDelete($id)
    {
        $article = Article::findOne(['id'=>$id]);
         $article->delete();
        return $this->redirect(['article/trash']);
    }
}