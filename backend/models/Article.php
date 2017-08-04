<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    /////状态
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
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'category_id' => '文章分类',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }


    ////文章分类
    public static function categorys()
    {
       //return $categorys = Category::findAll('id','name');
        //return ArrayHelper::map(Category::find()->all(),'id','name');
        return ArrayHelper::map(Category::find()->all(),'id','name');
    }

}
