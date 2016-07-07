<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_activity_share".
 *
 * @property integer $activity_id
 * @property string $title
 * @property string $link
 * @property string $image_url
 * @property string $description
 */
class ActivityShare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_activity_share';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['link', 'image_url'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => '活动id',
            'title' => '分享的标题',
            'link' => '分享的链接',
            'image_url' => '分享的图片',
            'description' => '分享描述',
        ];
    }
}
