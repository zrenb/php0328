<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 15:09
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->fileInput();
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList();
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();