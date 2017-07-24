<?php
namespace backend\models;

use yii\base\Model;

class GoodSearchForm extends Model
{
    public $name;
    public $sn;
    public $status;
    public $is_on_sale;
    public $shop_price;


    public function rules()
    {
        return [
            [['name','sn','status','is_on_sale'],'safe'],


        ];
    }
}