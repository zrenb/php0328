<h1>管理员列表</h1>
<?=\yii\bootstrap\Html::a('添加',['admin/add'],['class'=>'btn btn-primary'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>
    </tr>
    <?php foreach($admins as $admin):?>
    <tr>
        <td><?=$admin->id?></td>
        <td><?=$admin->username?></td>
        <td><?=$admin->email?></td>
        <td><?=\backend\models\Admin::statusOption()[$admin->status]?></td>
        <td><?=date('Ymd',$admin->created_at)?></td>
        <td><?=date('Ymd',$admin->updated_at)?></td>
        <td><?=date('Ymd H:i:s',$admin->last_login_time)?></td>
        <td><?=$admin->last_login_ip?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['admin/eidt','id'=>$admin->id],['class'=>'btn btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['admin/del','id'=>$admin->id],['class'=>'btn btn-warning'])?></td>
    </tr>
    <?php endforeach;?>
</table>