<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weixin_article_style_group".
 *
 * @property string $id
 * @property string $group_name
 * @property string $desc
 */
class ArticleStyleGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_article_style_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['group_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'group_name' => '分组名称',
            'desc' => '说明',
        ];
    }
}
