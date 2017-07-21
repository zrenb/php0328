<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 17:37
 */

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
//echo $form->field($model,'intro')->textarea();
//echo $form->field($model,'sort');
//echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Article::statusOption());
//echo $form->field($model,'category_id')->dropDownList(\backend\models\Article::categorys());
echo $form->field($model1,'content')->textarea(['rows'=>30]);
\yii\bootstrap\ActiveForm::end();
echo \yii\bootstrap\Html::a('退出',['article/index'],['class'=>'btn btn-primary']);