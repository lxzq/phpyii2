<?php
namespace common\tool;
use yii;
use yii\caching;

class WeiXinTool
{

/*****************************************************
 *      生成随机字符串 - 最长为32位字符串
 *****************************************************/
public function wxNonceStr($length = 16, $type = FALSE) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    if($type == TRUE){
        return strtoupper(md5(time() . $str));
    }
    else {
        return $str;
    }
}


    /**
     * 获取输入参数 支持过滤和默认值
     * 使用方法:
     * <code>
     * I('id',0); 获取id参数 自动判断get或者post
     * I('post.name','','htmlspecialchars'); 获取$_POST['name']
     * I('get.'); 获取$_GET
     * </code>
     * @param string $name 变量的名称 支持指定类型
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $datas 要获取的额外数据源
     * @return mixed
     */
    function I($name,$default='',$filter=null,$datas=null) {
        if(strpos($name,'.')) { // 指定参数来源
            list($method,$name) =   explode('.',$name,2);
        }else{ // 默认为自动判断
            $method =   'param';
        }
        switch(strtolower($method)) {
            case 'get'     :   $input =& $_GET;break;
            case 'post'    :   $input =& $_POST;break;
            case 'put'     :   parse_str(file_get_contents('php://input'), $input);break;
            case 'param'   :
                switch($_SERVER['REQUEST_METHOD']) {
                    case 'POST':
                        $input  =  $_POST;
                        break;
                    case 'PUT':
                        parse_str(file_get_contents('php://input'), $input);
                        break;
                    default:
                        $input  =  $_GET;
                }
                break;
            case 'path'    :
                $input  =   array();
                if(!empty($_SERVER['PATH_INFO'])){
                    $depr   =   C('URL_PATHINFO_DEPR');
                    $input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
                }
                break;
            case 'request' :   $input =& $_REQUEST;   break;
            case 'session' :   $input =& $_SESSION;   break;
            case 'cookie'  :   $input =& $_COOKIE;    break;
            case 'server'  :   $input =& $_SERVER;    break;
            case 'globals' :   $input =& $GLOBALS;    break;
            case 'data'    :   $input =& $datas;      break;
            default:
                return NULL;
        }
        if(''==$name) { // 获取全部变量
            $data       =   $input;
            array_walk_recursive($data,'filter_exp');
            $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
            if($filters) {
                if(is_string($filters)){
                    $filters    =   explode(',',$filters);
                }
                foreach($filters as $filter){
                    $data   = $this->  array_map_recursive($filter,$data); // 参数过滤
                }
            }
        }elseif(isset($input[$name])) { // 取值操作
            $data       =   $input[$name];
            is_array($data) && array_walk_recursive($data,'filter_exp');
            $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
            if($filters) {
                if(is_string($filters)){
                    $filters    =   explode(',',$filters);
                }elseif(is_int($filters)){
                    $filters    =   array($filters);
                }

                foreach($filters as $filter){
                    if(function_exists($filter)) {
                        $data   =   is_array($data)?$this->array_map_recursive($filter,$data):$filter($data); // 参数过滤
                    }else{
                        $data   =   filter_var($data,is_int($filter)?$filter:filter_id($filter));
                        if(false === $data) {
                            return   isset($default)?$default:NULL;
                        }
                    }
                }
            }
        }else{ // 变量默认值
            $data       =    isset($default)?$default:NULL;
        }
        return $data;
    }



    function array_map_recursive($filter, $data) {
        $result = array();
        foreach ($data as $key => $val) {
            $result[$key] = is_array($val)
                ? $this->array_map_recursive($filter, $val)
                : call_user_func($filter, $val);
        }
        return $result;
    }
/*******************************************************
 *   微信格式化数组变成参数格式 - 支持url加密
 *******************************************************/

public function wxFormatArray($parameters = NULL, $urlencode = FALSE){
    if(is_null($parameters)){
        $parameters = $this->parameters;
    }
    $restr = "";//初始化空
    ksort($parameters);//排序参数
    foreach ($parameters as $k => $v){//循环定制参数
        if (null != $v && "null" != $v && "sign" != $k) {
            if($urlencode){//如果参数需要增加URL加密就增加，不需要则不需要
                $v = urlencode($v);
            }
            $restr .= $k . "=" . $v . "&";//返回完整字符串
        }
    }
    if (strlen($restr) > 0) {//如果存在数据则将最后“&”删除
        $restr = substr($restr, 0, strlen($restr)-1);
    }
    return $restr;//返回字符串
}

/*******************************************************
 *   微信MD5签名生成器 - 需要将参数数组转化成为字符串[wxFormatArray方法]
 *******************************************************/
public function wxMd5Sign($content, $key){
    try {
        if (is_null($key)) {
            throw new Exception("签名key不能为空！");
        }
        if (is_null($content)) {
            throw new Exception("内容不能为空");
        }
        $signStr = $content . "&key=" . $key;
        return strtoupper(md5($signStr));
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
}
}
?>