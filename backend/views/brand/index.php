
<?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-primary'])?>

<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>排序</th>
        <th>状态</th>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach($branks as $brank):?>
    <tr>
        <td><?=$brank->id?></td>
        <td><?=$brank->name?></td>
        <td><?=$brank->sort?></td>
        <td><?=\backend\models\Brand::statusOption()[$brank->status]?></td>
        <td><?=\yii\bootstrap\Html::img($brank->logo,['height'=>30])?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['brand/eidt','id'=>$brank->id],['class'=>'glyphicon glyphicon-pencil'])?>
        <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brank->id],['class'=>'glyphicon glyphicon-trash'])?></td>
    </tr>
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$page,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);