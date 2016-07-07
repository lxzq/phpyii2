<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ss_activity_images".
 *
 * @property integer $activity_id
 * @property string $activity_image
 */
class ActivityImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ss_activity_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id'], 'integer'],
            [['activity_image'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => '活动id',
            'activity_image' => '活动相册图片',
        ];
    }
}
