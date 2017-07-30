<h1>菜单列表</h1>
<?=\yii\bootstrap\Html::a('添加',['menu/add-menu'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>父id</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($menus as $menu):?>
    <tr>
        <td><?=$menu->id?></td>
        <td><?=$menu->label?></td>
        <td><?=$menu->parent_id?></td>
        <td><?=$menu->url?></td>
        <td><?=$menu->sort?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['menu/edit-menu','id'=>$menu->id],['class'=>'btn btn-primary'])?>
            <?=\yii\bootstrap\Html::a('删除',['menu/del-menu','id'=>$menu->id],['class'=>'btn btn-warning'])?></td>
    </tr>
        <?php foreach ($menu->children as $child):?>
            <tr>
                <td><?=$child->id?></td>
                <td>————<?=$child->label?></td>
                <td><?=$child->parent_id?></td>
                <td><?=$child->url?></td>
                <td><?=$child->sort?></td>
                <td><?=\yii\bootstrap\Html::a('修改',['menu/edit-menu','id'=>$child->id],['class'=>'btn btn-primary'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/del-menu','id'=>$child->id],['class'=>'btn btn-warning'])?></td>
            </tr>
        <?php endforeach;?>
    <?php endforeach;?>
</table>