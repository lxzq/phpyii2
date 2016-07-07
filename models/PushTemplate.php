<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_push_template".
 *
 * @property integer $id
 * @property string $template_id
 * @property string $title
 * @property string $primary_industry
 * @property string $deputy_industry
 * @property string $content
 * @property string $url
 * @property string $template_data
 * @property string $example
 * @property integer $flag
 */
class PushTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_push_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'template_data', 'example','url'], 'string'],
            [['flag'], 'integer'],
            [['template_id', 'title'], 'string', 'max' => 50],
            [['primary_industry', 'deputy_industry'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'template_id' => '模板id',
            'title' => '标题',
            'primary_industry' => '一级行业',
            'deputy_industry' => '二级行业',
            'content' => '内容',
            'template_data' => '模板数据',
            'example' => '模板示例',
        ];
    }
}
