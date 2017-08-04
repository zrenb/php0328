<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property string $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    public $address_id;
    /**
     * @inheritdoc
     */

    //定义配送方式
    public static $deliveries=[
        1=>['name'=>'顺丰','id'=>1,'price'=>100,'detail'=>'速度快，服务好，价格贵'],
        2=>['name'=>'京东','id'=>2,'price'=>10,'detail'=>'速度快，服务好，价格贵，只配送自己的货'],
        3=>['name'=>'菜鸟','id'=>3,'price'=>10,'detail'=>'速度快，服务好，价格贵,只配送自己的货'],
        4=>['name'=>'韵达','id'=>4,'price'=>10,'detail'=>'速度快，服务好，价格贵，什么都干'],
    ];
    //定义支付方式
    public static $paymentes=[
        1=>['name'=>'线上支付','id'=>1,'detail'=>'	即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        2=>['name'=>'货到付款','id'=>2,'detail'=>'	送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        3=>['name'=>'上门自提','id'=>3,'detail'=>'	自提时付款，支持现金、POS刷卡、支票支付'],
        4=>['name'=>'邮局费款','id'=>4,'detail'=>'	通过快钱平台收款 汇款后1-3个工作日到账'],
    ];
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'delivery_id', 'payment_id', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name'], 'string', 'max' => 50],
            ['address_id','safe'],
            [['province', 'city'], 'string', 'max' => 20],
            [['area', 'address', 'delivery_name', 'payment_name', 'trade_no'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => 'Name',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'tel' => 'Tel',
            'delivery_id' => 'Delivery ID',
            'delivery_name' => 'Delivery Name',
            'delivery_price' => 'Delivery Price',
            'payment_id' => 'Payment ID',
            'payment_name' => 'Payment Name',
            'total' => 'Total',
            'status' => 'Status',
            'trade_no' => 'Trade No',
            'create_time' => 'Create Time',
        ];
    }
}
