<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "org_manager_file".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $add_time
 * @property string $file_path
 * @property string $notes
 * @property interger $org_id
 */
class OrgManagerFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_manager_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','org_id','file_path'], 'required'],
            [['user_id','org_id'], 'integer'],
            [['add_time'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['file_path', 'notes'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '文件上传人',
            'name' => '文档名称',
            'add_time' => '上传时间',
            'file_path' => '文件路径',
            'notes' => '文件说明',
            'org_id' => '机构ID',
        ];
    }

    public function getUser(){
        return $this->hasOne(UserInfo::className(),['id'=>'user_id']);
    }
    public function getShop(){
        return $this->hasOne(OrgShopInfo::className(),['org_id'=>'org_id']);
    }
    public function getOrg(){
        return $this->hasOne(OrgInfo::className(),['id'=>'org_id']);
    }
}
