<?php
namespace backend\models;

use backend\components\SphinxClient;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

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

    public function search(ActiveQuery $query)
    {
        $this->load(\Yii::$app->request->get());
        if($this->name)
        {
            //$cl = new SphinxClient ();
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
            //$cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);   //匹配模式
            $cl->SetLimits(0, 1000);
            $info = $this->name;
            $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
            if(isset($res['matches'])){
                $ids = ArrayHelper::getColumn($res['matches'],'id');
                $query->where(['in','id',$ids]);
            }else{
                $query->where(['id'=>0]);
                return ;
            }

        }
        if($this->sn)
        {
            $query->andWhere(['like','sn',$this->sn]);
        }
        if($this->status)
        {
            $query->andWhere(['like','status',$this->status]);
        }
        if($this->is_on_sale)
        {
            $query->andWhere(['like','is_on_sale',$this->is_on_sale]);
        }
    }
}