<?php

namespace app\models;

use Yii;
use yii\web\Session;
/**
 * This is the model class for table "public_list".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $public_name
 * @property string $public_id
 * @property string $wechat
 * @property string $interface_url
 * @property string $headface_url
 * @property string $area
 * @property string $public_config
 * @property string $token
 * @property integer $is_use
 * @property string $type
 * @property string $appid
 * @property string $secret
 * @property string $encodingaeskey
 * @property string $tips_url
 * @property string $domain
 * @property integer $is_bind
 */
class PublicList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_use', 'is_bind'], 'integer'],
            [['public_config'], 'string'],
            [['public_name', 'area'], 'string', 'max' => 50],
            [['public_id', 'wechat', 'token', 'appid', 'secret', 'encodingaeskey'], 'string', 'max' => 100],
            [['interface_url', 'headface_url', 'tips_url'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 10],
            [['domain'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User_id',
            'public_name' => 'Public Name',
            'public_id' => 'Public ID',
            'wechat' => 'Wechat',
            'interface_url' => 'Interface Url',
            'headface_url' => 'Headface Url',
            'area' => 'Area',
            'public_config' => 'Public Config',
            'token' => 'Token',
            'is_use' => 'Is Use',
            'type' => 'Type',
            'appid' => 'Appid',
            'secret' => 'Secret',
            'encodingaeskey' => 'Encodingaeskey',
            'tips_url' => 'Tips Url',
            'domain' => 'Domain',
            'is_bind' => 'Is Bind',
        ];
    }
    /**
     * 获取公众号信息
     */
    // 获取当前用户的Token
   public function get_token($token = NULL) {
       $session=Yii::$app->session;
       $request=Yii::$app->request;
       $stoken = $session['token'];
        if ($token !== NULL) {
            $session['token']=$token;
        } elseif (empty ( $stoken ) ) {
            $public_id=$session['public_id'];
            $token=PublicList::find()->select("token")->where(['id'=>$public_id])->one();
            $token && $session['token']=$token['token'];

        } elseif (! empty ( $request->get('token') )) {
            $session['token']=$request->get('token');
        } elseif (! empty ($request->get('public_id') )) {
            $public_id = $request->get('public_id');
            $token=PublicList::find()->select("token")->where(['id'=>$public_id])->one();
            $token && $session['token']=$token['token'];
        }
        $token =$session['token'];
        return $token;
    }
}
