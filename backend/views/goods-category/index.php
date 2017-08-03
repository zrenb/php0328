<h1>商品分类</h1>
<?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info'])?>
<table class="table table-responsive table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($categorys as $category):?>
    <tr>
        <td><?=$category->id?></td>
        <td><?=str_repeat('———',$category->depth).$category->name?></td>
        <td><?=$category->intro?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$category->id],['class'=>'btn btn-primary'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$category->id],['class'=>'btn btn-warning'])?></td>
    </tr>
    <?php endforeach;?>
</table>
