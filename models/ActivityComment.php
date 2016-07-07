<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_activity_comment".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property string $open_id
 * @property string $content
 * @property integer $add_time
 * @property integer $status
 */
class ActivityComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_activity_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'add_time'], 'integer'],
            [['open_id'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID',
            'activity_id' => '活动id',
            'open_id' => '用户id',
            'content' => '评论内容',
            'add_time' => '评论时间',
        ];
    }
}
