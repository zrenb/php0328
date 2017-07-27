

    <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>




<h1>角色列表</h1>
<?=\yii\bootstrap\Html::a('添加',['add-role'],['class'=>'btn btn-primary'])?>
<table class="table">
    <tr>
        <th>角色</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
   <?php foreach ($roles as $role):?>
       <tr>
           <td><?=$role->name?></td>
           <td><?=$role->description?></td>
           <td><?=\yii\bootstrap\Html::a('修改',['edit-role','name'=>$role->name],['class'=>'btn btn-info'])?>
               <?=\yii\bootstrap\Html::a('删除',['del-role','name'=>$role->name],['class'=>'btn btn-warning'])?></td>
       </tr>
    <?php endforeach;?>
</table>


<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>Column 1</th>
        <th>Column 2</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Row 1 Data 1</td>
        <td>Row 1 Data 2</td>
    </tr>
    <tr>
        <td>Row 2 Data 1</td>
        <td>Row 2 Data 2</td>
    </tr>
    </tbody>
</table>

<script>
    $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );
</script>
