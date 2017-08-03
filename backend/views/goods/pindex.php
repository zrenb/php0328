
<table>
    <?php foreach($pics as $pic):?>
    <tr>
        <td><?=$pic->id ?></td>
        <td><?=\yii\bootstrap\Html::img($pic->path,['width'=>200],['class'=>'auto'])?></td>
        <td><?=\yii\bootstrap\Html::a('删除',['goods/pdel','id'=>$pic->id],['class'=>'btn btn-warning'])?></td></tr>
    <?php endforeach;?>
</table>
<?=\yii\bootstrap\Html::a('返回',['goods/index'],['class'=>'btn btn-primary'])?>