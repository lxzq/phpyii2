<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weixin_menu".
 *
 * @property string $id
 * @property integer $menu_group_id
 * @property integer $sort
 * @property integer $pid
 * @property string $title
 * @property string $url
 * @property string $keyword
 * @property string $type
 * @property integer $user_id
 * @property integer $add_time
 * @property integer $change_user_id
 * @property integer $change_time
 */
class WeixinMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_group_id', 'sort', 'pid', 'user_id', 'add_time', 'change_user_id', 'change_time'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 30],
            [['keyword'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'menu_group_id' => '菜单组id',
            'sort' => '排序号',
            'pid' => '父id',
            'title' => '菜单名',
            'keyword' => '关联关键词',
            'url' => '关联URL',
            'type' => '类型1、click：点击推事件2、view：跳转URL3、scancode_push：扫码推事件4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框5、pic_sysphoto：弹出系统拍照发图6、pic_photo_or_album：弹出拍照或者相册发图7、pic_weixin：弹出微信相册发图器8、location_select：弹出地理位置选择器',
            'user_id' => '用户id',
            'add_time' => '添加时间',
            'change_user_id' => '用户id',
            'change_time' => '修改时间',
        ];
    }
}
