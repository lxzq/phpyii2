<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "material_text".
 *
 * @property string $id
 * @property string $content
 * @property string $token
 * @property integer $user_id
 * @property integer $is_use
 */
class MaterialText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['user_id', 'is_use'], 'integer'],
            [['token'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'content' => '文本内容',
            'token' => 'Token',
            'user_id' => 'uid',
            'is_use' => '可否使用',
        ];
    }
}
