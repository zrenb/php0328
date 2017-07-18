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
        <td><?=$brank->status?></td>
        <td><?=$brank->LOGO?></td>
        <td>//////</td>
    </tr>
    <?php endforeach;?>
</table>
