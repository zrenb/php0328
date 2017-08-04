<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    const EVENT_ADD = 'add';
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 100],
            ['label','required','on'=>self::EVENT_ADD]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名称',
            'url' => '路由',
            'parent_id' => '父id',
            'sort' => '排序',
        ];
    }
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
