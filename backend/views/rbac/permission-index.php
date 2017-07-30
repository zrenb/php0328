<h1>权限列表</h1>
<?=\yii\bootstrap\Html::a('添加',['add-permission'],['class'=>'btn btn-info'])?>
<table class="table">
    <thead>
        <tr>
            <th>权限名称</th>
            <th>权限描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($permissions as $permission):?>
     <tr>
         <td><?=$permission->name?></td>
         <td><?=$permission->description?></td>
         <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$permission->name],['class'=>'btn btn-primary glyphicon glyphicon-pencil '])?>
             <?=\yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$permission->name],['class'=>'btn btn-warning glyphicon glyphicon-trash '])?></td>
     </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php
/**
 * @var $this \yii\web\View
 */
//$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
language: {
        url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Chinese.json"
    }
});');