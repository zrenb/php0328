<h1>管理员<?=$model->scenario==\backend\models\Admin::SCENARIO_ADD?'添加':'修改'?></h1>
<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');

echo $form->field($model,'password')->passwordInput()->label('密码');
echo $form->field($model,'email');
echo $form->field($model,'roles',['inline'=>true])->checkboxList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','name'));
if(!$model->isNewRecord)
{
    echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Admin::statusOption());
}
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'admin/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();