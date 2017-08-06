<h1>菜单<?=$model->scenario == \backend\models\Menu::EVENT_ADD?'添加':'修改'?></h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
echo $form->field($model,'parent_id')->dropDownList($parent_ids,['prompt' => '*请选择*']);
echo $form->field($model,'url')->dropDownList($dires,['prompt' => '*请选择*']);
echo $form->field($model,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();

