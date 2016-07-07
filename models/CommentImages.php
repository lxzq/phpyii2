<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_comment_images".
 *
 * @property integer $comment_id
 * @property string $image
 */
class CommentImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_comment_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id'], 'integer'],
            [['image'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => '评论id',
            'image' => '评论图片',
        ];
    }
}
