<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username')->textInput(['class'=>"form-control",'readonly'=>'readonly']);
echo $form->field($model,'oldPassword')->passwordInput();
echo $form->field($model,'newPassword')->passwordInput();
echo $form->field($model,'okPassword')->passwordInput();
/*echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'admin/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');*/
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();