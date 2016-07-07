<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 15/8/25
 * Time: 下午12:41
 */

namespace wxbackend\models;

use yii;
use yii\base\Model;
use yii\base\InvalidParamException;


class Error extends Model
{
    public $type;
    public $name;
    public $message;
    public $code;
    public $status;

    public function __construct($error_code,$error_msg=null)
    {
        if (empty($error_code) || !is_string($error_code)) {
            throw new InvalidParamException('error_code cannot be blank.');
        }
        $this->code = $error_code;
        $this->status=200;
        $this->name="system error";
        $this->type="system error";
        if($error_msg==null)
            $this->message=$this->_getError($error_code);
        else
            $this->message=$error_msg;
        parent::__construct();
    }




    private function _getError($error_code)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            500=>'数据库操作异常',
            10001 => 'System error',
            10002 => 'Service unavailable',
            10003 => 'Remote service error',
            10004 => 'IP limit',
            10005 => 'Permission denied, need a high level appkey',
            10008 => 'Param error, see doc for more info',
            10012 => 'Illegal request',
            10013 => 'Invalid user',
            10022 => 'IP requests out of rate limit',
            10023 => 'User requests out of rate limit',
            10024 => '用户请求特殊接口频次超过上限',

            20001 => '用户名不能为空',
            20002 => '密码不能为空',
            20003 => '密码不正确',
            20004 => '微博usid不能为空',
            20005 => '用户不存在',
            30001 => '经纬度不能为空',
            30010 => '经度不能为空',
            30011 => '纬度不能为空',
            30012 => '地理位置地址不能为空',
            30013 => '孩子的ID不能为空',
            30014 => '当前登录人ID不能为空',
            30015 => '缺少参数childId',
            30016 => '消息发送失败',
            30017 => '左上维度为空',
            30018 => '左上经度为空',
            30019 => '右下维度为空',
            30020 => '右下经度为空',
            30021 => '比例能为空',
            30022 => '孩子昵称不能为空',
            30023 => '孩子关系不能为空',
            30024 => '生日不能为空',
            30025 => '性别不能为空',
            30026 => 'QQ ID不能为空',
            30027 => 'QQ 昵称不能为空',
            30028 => 'QQ 性别不能为空',
            30029 => 'umtoken不能为空',

            40010 => '分享内容不能为空!',
            40011 => '记录时间不能为空!',
            40012 => '记录地址不能为空！',
            40013 => '记录标签不能为空！',

        );
        return (isset($codes[$error_code])) ? $codes[$error_code] : '';
    }

}