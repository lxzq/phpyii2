<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weixin_article_style".
 *
 * @property string $id
 * @property integer $group_id
 * @property string $style
 */
class ArticleStyle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_article_style';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id'], 'integer'],
            [['style'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'group_id' => '分组样式',
            'style' => '样式内容',
        ];
    }
}
