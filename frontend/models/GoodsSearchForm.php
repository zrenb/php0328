<?php
namespace frontend\models;

use yii\base\Model;

class GoodsSearchForm extends Model
{
    public $keyword;

    public function rules()
    {
        return ['keyword','safe'];
    }
}