<h1>角色<?=$model->scenario==\backend\models\RoleForm::SCENARIO_ADD?'添加':'修改'?></h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo $form->field($model,'permissions',['inline'=>1])->checkboxList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description')
    );
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
