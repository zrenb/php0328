<table class="table">
    <tr>
        <th>商品名称</th>
        <th>商品图片</th>
        <th>商品价格</th>
        <th>商品数量</th>
        <th>商品交易金额</th>
    </tr>
    <?php foreach ($goods as $good):?>
    <tr>
        <td><?=$good->goods_name?></td>
        <td><?=\yii\helpers\Html::img($good->LOGO,['height'=>40])?></td>
        <td><?=$good->price?></td>
        <td><?=$good->amount?></td>
        <td><?=$good->total?></td>
    </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('返回',['goods/index'])?>
