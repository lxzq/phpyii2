<?php

namespace app\models;

use Yii;
use app\models\PublicList;
/**
 * This is the model class for table "user_group".
 *
 * @property string $id
 * @property string $weixin_group_id
 * @property string $token
 * @property string $group_name
 * @property string $description
 * @property integer $user_count
 * @property integer $is_del
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name'], 'required','message'=>"请输入分组名称",'on'=>'add,change'],
            [['id','user_count','sort','add_time','update_time'],'integer'],
            [['description'], 'string'],
            [['group_name'], 'string','max'=>30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_name' => '分组名称',
            'description' => '分组描述',
            'sort'=>'分组排序',
            'user_count'=>'用户数'
        ];
    }
    /**
     * @param $type
     * 获取分组列表
     */
    public function get_group_list($type=1){
        $token=(new PublicList())->get_token();
        $group=UserGroup::find()->select(['weixin_group_id','group_name','id'])->asArray()->where(['token'=>$token])->andWhere(['not','weixin_group_id'=>''])->orderBy('sort')->all();
        if($type==1)
        {
            if(!empty($group))
            {
                foreach($group as $key=>$value){
                    $list[$value['weixin_group_id']]=$value['group_name'];
                }
            }else{
                return null;
            }
            
        }elseif($type==2){
            if(!empty($group))
            {
               foreach($group as $key=>$value){
                    $list[$value['weixin_group_id']]=$value;
                } 
            }else{
                return null;
            }
            
        }else{
            $list=$group;

        }
        return $list;
    }

}
