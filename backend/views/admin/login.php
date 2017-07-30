<h3>用户登录</h3>
<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($login,'username');
echo $form->field($login,'password')->passwordInput();
echo $form->field($login,'save_login')->checkbox();
echo $form->field($login,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'admin/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>'])->label('验证码');
//echo \yii\bootstrap\Html::radio('是否自动登录');
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();