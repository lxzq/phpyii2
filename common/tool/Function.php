<?php


/**全局的安全过滤函数
 * @param $text
 * @param string $type
 * @return mixed|string
 */
function safe($text, $type = 'html') {
    // 无标签格式
    $text_tags = '';
    // 只保留链接
    $link_tags = '<a>';
    // 只保留图片
    $image_tags = '<img>';
    // 只存在字体样式
    $font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
    // 标题摘要基本格式
    $base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike><section><header><footer><article><nav><audio><video>';
    // 兼容Form格式
    $form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
    // 内容等允许HTML的格式
    $html_tags = $base_tags . '<meta><ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';
    // 全HTML格式
    $all_tags = $form_tags . $html_tags . '<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
    // 过滤标签
    $text = html_entity_decode ( $text, ENT_QUOTES, 'UTF-8' );
    $text = strip_tags ( $text, ${$type . '_tags'} );

    // 过滤攻击代码
    if ($type != 'all') {
        // 过滤危险的属性，如：过滤on事件lang js
        while ( preg_match ( '/(<[^><]+)(ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat ) ) {
            $text = str_ireplace ( $mat [0], $mat [1] . $mat [3], $text );
        }
        while ( preg_match ( '/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat ) ) {
            $text = str_ireplace ( $mat [0], $mat [1] . $mat [3], $text );
        }
    }
    return $text;
}
// 防超时的file_get_contents改造函数
function file_get_contents_time($url) {
    $context = stream_context_create ( array (
        'http' => array (
            'timeout' => 30
        )
    ) ); // 超时时间，单位为秒

    return file_get_contents ( $url, 0, $context );
}
/**
 * 发送HTTP请求方法，目前只支持CURL发送请求
 * @param  string $url    请求URL
 * @param  array  $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @return array  $data   响应数据
 */
function http($url, $params, $method = 'GET', $header = array(), $multi = false){
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => $header
    );

    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method)){
        case 'GET':
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
            //$params = $multi ? $params : http_build_query($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            throw new Exception('不支持的请求方式！');
    }

    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if($error) throw new Exception('请求发生错误：' . $error);
    return  $data;
}
/**
 * ************************************************************
 *
 * 使用特定function对数组中所有元素做处理
 *
 * @param
 *          string &$array 要处理的字符串
 * @param string $function
 *          要执行的函数
 * @return boolean $apply_to_keys_also 是否也应用到key上
 * @access public
 *        
 *         ***********************************************************
 */
function arrayRecursive(&$array, $function, $apply_to_keys_also = false) {
    static $recursive_counter = 0;
    if (++ $recursive_counter > 1000) {
        die ( 'possible deep recursion attack' );
    }
    foreach ( $array as $key => $value ) {
        if (is_array ( $value )) {
            arrayRecursive ( $array [$key], $function, $apply_to_keys_also );
        } else {
            $array [$key] = $function ( $value );
        }
        
        if ($apply_to_keys_also && is_string ( $key )) {
            $new_key = $function ( $key );
            if ($new_key != $key) {
                $array [$new_key] = $array [$key];
                unset ( $array [$key] );
            }
        }
    }
    $recursive_counter --;
}
/**
 * ************************************************************
 *
 * 将数组转换为JSON字符串（兼容中文）
 *
 * @param array $array
 *          要转换的数组
 * @return string 转换得到的json字符串
 * @access public
 *        
 *         ***********************************************************
 */
function JSON($array) {
    arrayRecursive ( $array, 'urlencode', true );
    $json = json_encode ( $array );
    return urldecode ( $json );
}
// 以POST方式提交数据
function post_data($url, $param, $is_file = false, $return_array = true) {
    if (! $is_file && is_array ( $param )) {
        $param = JSON ( $param );
    }
    if ($is_file) {
        $header [] = "content-type: multipart/form-data; charset=UTF-8";
    } else {
        $header [] = "content-type: application/json; charset=UTF-8";
    }
    
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
    curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $param );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    $res = curl_exec ( $ch );
   // $flat = curl_errno ( $ch );
    curl_close ( $ch );
    file_put_contents('./cache/paramas',$param);
    file_put_contents('./cache/res',$res);
    $return_array && $res = json_decode ( $res, true );
    
    return $res;
}
// 微信端的错误码转中文解释
function error_msg($return, $more_tips = '') {
    $msg = array (
            '-1' => '系统繁忙，此时请开发者稍候再试',
            '0' => '请求成功',
            '40001' => '获取access_token时AppSecret错误，或者access_token无效。请开发者认真比对AppSecret的正确性，或查看是否正在为恰当的公众号调用接口',
            '40002' => '不合法的凭证类型',
            '40003' => '不合法的OpenID，请开发者确认OpenID（该用户）是否已关注公众号，或是否是其他公众号的OpenID',
            '40004' => '不合法的媒体文件类型',
            '40005' => '不合法的文件类型',
            '40006' => '不合法的文件大小',
            '40007' => '不合法的媒体文件id',
            '40008' => '不合法的消息类型',
            '40009' => '不合法的图片文件大小',
            '40010' => '不合法的语音文件大小',
            '40011' => '不合法的视频文件大小',
            '40012' => '不合法的缩略图文件大小',
            '40013' => '不合法的AppID，请开发者检查AppID的正确性，避免异常字符，注意大小写',
            '40014' => '不合法的access_token，请开发者认真比对access_token的有效性（如是否过期），或查看是否正在为恰当的公众号调用接口',
            '40015' => '不合法的菜单类型',
            '40016' => '不合法的按钮个数',
            '40017' => '不合法的按钮个数',
            '40018' => '不合法的按钮名字长度',
            '40019' => '不合法的按钮KEY长度',
            '40020' => '不合法的按钮URL长度',
            '40021' => '不合法的菜单版本号',
            '40022' => '不合法的子菜单级数',
            '40023' => '不合法的子菜单按钮个数',
            '40024' => '不合法的子菜单按钮类型',
            '40025' => '不合法的子菜单按钮名字长度',
            '40026' => '不合法的子菜单按钮KEY长度',
            '40027' => '不合法的子菜单按钮URL长度',
            '40028' => '不合法的自定义菜单使用用户',
            '40029' => '不合法的oauth_code',
            '40030' => '不合法的refresh_token',
            '40031' => '不合法的openid列表',
            '40032' => '不合法的openid列表长度',
            '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
            '40035' => '不合法的参数',
            '40038' => '不合法的请求格式',
            '40039' => '不合法的URL长度',
            '40050' => '不合法的分组id',
            '40051' => '分组名字不合法',
            '40117' => '分组名字不合法',
            '40118' => 'media_id大小不合法',
            '40119' => 'button类型错误',
            '40120' => 'button类型错误',
            '40121' => '不合法的media_id类型',
            '40132' => '微信号不合法',
            '40137' => '不支持的图片格式',
            '41001' => '缺少access_token参数',
            '41002' => '缺少appid参数',
            '41003' => '缺少refresh_token参数',
            '41004' => '缺少secret参数',
            '41005' => '缺少多媒体文件数据',
            '41006' => '缺少media_id参数',
            '41007' => '缺少子菜单数据',
            '41008' => '缺少oauth code',
            '41009' => '缺少openid',
            '42001' => 'access_token超时，请检查access_token的有效期，请参考基础支持-获取access_token中，对access_token的详细机制说明',
            '42002' => 'refresh_token超时',
            '42003' => 'oauth_code超时',
            '43001' => '需要GET请求',
            '43002' => '需要POST请求',
            '43003' => '需要HTTPS请求',
            '43004' => '需要接收者关注',
            '43005' => '需要好友关系',
            '44001' => '多媒体文件为空',
            '44002' => 'POST的数据包为空',
            '44003' => '图文消息内容为空',
            '44004' => '文本消息内容为空',
            '45001' => '多媒体文件大小超过限制',
            '45002' => '消息内容超过限制',
            '45003' => '标题字段超过限制',
            '45004' => '描述字段超过限制',
            '45005' => '链接字段超过限制',
            '45006' => '图片链接字段超过限制',
            '45007' => '语音播放时间超过限制',
            '45008' => '图文消息超过限制',
            '45009' => '接口调用超过限制',
            '45010' => '创建菜单个数超过限制',
            '45015' => '回复时间超过限制',
            '45016' => '系统分组，不允许修改',
            '45017' => '分组名字过长',
            '45018' => '分组数量超过上限',
            '46001' => '不存在媒体数据',
            '46002' => '不存在的菜单版本',
            '46003' => '不存在的菜单数据',
            '46004' => '不存在的用户',
            '47001' => '解析JSON/XML内容错误',
            '48001' => 'api功能未授权，请确认公众号已获得该接口，可以在公众平台官网-开发者中心页中查看接口权限',
            '50001' => '用户未授权该api',
            '50002' => '用户受限，可能是违规后接口被封禁',
            '61451' => '参数错误(invalid parameter)',
            '61452' => '无效客服账号(invalid kf_account)',
            '61453' => '客服帐号已存在(kf_account exsited)',
            '61454' => '客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)(invalid kf_acount length)',
            '61455' => '客服帐号名包含非法字符(仅允许英文+数字)(illegal character in kf_account)',
            '61456' => '客服帐号个数超过限制(10个客服账号)(kf_account count exceeded)',
            '61457' => '无效头像文件类型(invalid file type)',
            '61450' => '系统错误(system error)',
            '61500' => '日期格式错误',
            '61501' => '日期范围错误',
            '9001001' => 'POST数据参数不合法',
            '9001002' => '远端服务不可用',
            '9001003' => 'Ticket不合法',
            '9001004' => '获取摇周边用户信息失败',
            '9001005' => '获取商户信息失败',
            '9001006' => '获取OpenID失败',
            '9001007' => '上传文件缺失',
            '9001008' => '上传素材的文件类型不合法',
            '9001009' => '上传素材的文件尺寸不合法',
            '9001010' => '上传失败',
            '9001020' => '帐号不合法',
            '9001021' => '已有设备激活率低于50%，不能新增设备',
            '9001022' => '设备申请数不合法，必须为大于0的数字',
            '9001023' => '已存在审核中的设备ID申请',
            '9001024' => '一次查询设备ID数量不能超过50',
            '9001025' => '设备ID不合法',
            '9001026' => '页面ID不合法',
            '9001027' => '页面参数不合法',
            '9001028' => '一次删除页面ID数量不能超过10',
            '9001029' => '页面已应用在设备中，请先解除应用关系再删除',
            '9001030' => '一次查询页面ID数量不能超过50',
            '9001031' => '时间区间不合法',
            '9001032' => '保存设备与页面的绑定关系参数错误',
            '9001033' => '门店ID不合法',
            '9001034' => '设备备注信息过长',
            '9001035' => '设备申请参数不合法',
            '9001036' => '查询起始值begin不合法' 
    );
    
    if ($more_tips) {
        $res = $more_tips . ': ';
    } else {
        $res = '';
    }
    if (isset ( $msg [$return ['errcode']] )) {
        $res .= $msg [$return ['errcode']];
    } else {
        $res .= $return ['errmsg'];
    }
    
    $res .= ', 返回码：' . $return ['errcode'];
    
    return $res;
}
// 创建多级目录
function mkdirs($dir) {
    if (! is_dir ( $dir )) {
        if (! mkdirs ( dirname ( $dir ) )) {
            return false;
        }
        if (! mkdir ( $dir, 0777 ,true)) {
            return false;
        }
    }
    return true;
}
//分割函数，同时支持常见的按空格、逗号、分号、换行进行分割
function wp_explode($string, $delimiter = "\s,;\r\n") {
    if (empty ( $string ))
        return array ();

    // 转换中文符号
    // $string = iconv ( 'utf-8', 'gbk', $string );
    // $string = preg_replace ( '/\xa3([\xa1-\xfe])/e', 'chr(ord(\1)-0x80)', $string );
    // $string = iconv ( 'gbk', 'utf-8', $string );

    $arr = preg_split ( '/[' . $delimiter . ']+/', $string );
    return array_unique ( array_filter ( $arr ) );
}

