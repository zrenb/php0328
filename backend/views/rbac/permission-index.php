<h1>权限列表</h1>
<?=\yii\bootstrap\Html::a('添加',['add-permission'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    <?php foreach($permissions as $permission):?>
     <tr>
         <td><?=$permission->name?></td>
         <td><?=$permission->description?></td>
         <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$permission->name],['class'=>'btn btn-primary glyphicon glyphicon-pencil '])?>
             <?=\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$permission->name],['class'=>'btn btn-warning glyphicon glyphicon-trash '])?></td>
     </tr>
    <?php endforeach;?>
</table>