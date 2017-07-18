<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $code;
    public $imageFile;////保存处理图片对象
    public static function statusOption($hidden_del=true)
    {
        $status=[-1=>'删除',0=>'隐藏',1=>'正常'];
        if($hidden_del){
            unset($status[-1]);
        }
        return $status;
    }

    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            ['imageFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'imageFile' => '品牌',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
