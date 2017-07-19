<?=\yii\bootstrap\Html::a('首页',['article/index'],['class'=>'btn btn-info'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>文章分类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($articles as $article):?>
    <tr>
        <td><?=$article->id?></td>
        <td><?=$article->name?></td>
        <td><?=$article->sort?></td>
        <td><?=\backend\models\Article::statusOption(false)[$article->status]?></td>
        <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
        <td><?=\backend\models\Article::categorys()[$article->category_id]?></td>
        <td><?=$article->intro?></td>
        <td><?=\yii\bootstrap\Html::a('隐藏',['article/hide','id'=>$article->id],['class'=>'glyphicon glyphicon-pencil'])?>
        <?=\yii\bootstrap\Html::a('正常',['article/reg','id'=>$article->id],['class'=>'glyphicon glyphicon-asterisk'])?>
        <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'glyphicon glyphicon-trash'])?></td>
    </tr>
    <?php endforeach;?>
</table>

<?php
/*//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);*/