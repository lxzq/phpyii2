<?php

namespace app\models;

use api\models\UserInfo;
use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $add_time
 * @property string $image
 * @property string $content
 * @property string $details
 * @property string $add_user
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_time'], 'safe'],
            [['details'], 'string'],
            [['title'], 'string', 'max' => 100],
            [['image', 'content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '新闻标题',
            'add_time' => '新闻发布时间',
            'image' => '新闻图片',
            'content' => '新闻简介',
            'details' => '新闻详情',
            'add_user'=>'新闻发布人'
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'add_user']);
    }
}
