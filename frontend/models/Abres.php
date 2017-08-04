<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "abres".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $tel
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $detail
 * @property string $status
 */
class Abres extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const EVENT_ADRES = 'adres';
    public static function tableName()
    {
        return 'abres';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'tel'], 'integer'],
            [['name', 'area'], 'string', 'max' => 50],
            [['province', 'city'], 'string', 'max' => 20],
            [['detail'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 255],
            [['tel','name','province','city','area','detail'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => '收货人',
            'tel' => '电话',
            'province' => '省',
            'city' => '市',
            'area' => '区县',
            'detail' => '详情地址',
            'status' => '默认地址',
        ];
    }
}
