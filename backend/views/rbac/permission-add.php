<h1>权限<?=$model->scenario == \backend\models\PermissionForm::SCENARIO_ADD?'添加':'修改'?></h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
//var_dump($model);exit;
echo $form->field($model,'name')->textInput(['readonly'=>$model->scenario != \backend\models\PermissionForm::SCENARIO_ADD]);
echo $form->field($model,'description')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();