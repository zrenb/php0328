<?=\yii\bootstrap\Html::a('添加',['category/add'],['class'=>'btn btn-primary'])?>

    <table class="table table-bordered table-responsive">
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>简介</th>
            <th>排序</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach($categorys as $category):?>
            <tr>
                <td><?=$category->id?></td>
                <td><?=$category->name?></td>
                <td><?=$category->intro?></td>
                <td><?=$category->sort?></td>
                <td><?=\backend\models\Category::statusOption()[$category->status]?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['category/eidt','id'=>$category->id],['class'=>'glyphicon glyphicon-pencil'])?>
                    <?=\yii\bootstrap\Html::a('删除',['category/del','id'=>$category->id],['class'=>'glyphicon glyphicon-trash'])?></td>
            </tr>
        <?php endforeach;?>
    </table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);