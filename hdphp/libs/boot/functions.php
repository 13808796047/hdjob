<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-5 下午01:53:33
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */

/**
 * 加载核心模型
 * @param type $tableName           表名
 * @param type boolean  $full       是否为全表名
 * @return Model                    返回模型对象
 */
function M($tableName = null, $full = null) {
    return new Model($tableName, $full);
}

/**
 * 
 * 加载扩展模型文件
 * @param $tabname	表名
 */
function K($model) {
    $modelArr = get_model_file($model);
    if (!is_file($modelArr[0])) {
        error(L("functions_k_is_file") . $modelArr[0], false); //还没有定义模型文件
    }
    load_file($modelArr[0]);
    $modelClass = $modelArr[1] . 'Model';
    if (!class_exists($modelClass, false)) {
        error(L("functions_k_error"), false); //模型类定义有误
    }

    $model = new $modelClass($model);
    return $model;
}

/**
 * 获得关联模型
 * @param type $model 
 */
function R($tableName = null, $full = null) {
    return new relationModel($tableName, $full);
}

/**
 * 获得视图模型
 * @param type $model 
 */
function V($tableName = null, $full = null) {
    return new viewModel($tableName, $full);
}

/**
 * 获得模型文件 
 */
function get_model_file($path) {
    $path = rtrim($path, '/');
    $path = str_ireplace("Model.php", '', $path);
    $path = str_replace(C("MODEL_FIX"), '', $path);
    $pathArr = explode('/', $path);
    $arr = array();
    switch (count($pathArr)) {
        case 1:
            $arr['app_group'] = '';
            $arr['app'] = PATH_APP . '/';
            $arr['model'] = $pathArr[0];
            break;
        case 2:
            $arr['app_group'] = PATH_APP_GROUP . '/';
            $arr['app'] = $pathArr[0] . '/';
            $arr['model'] = $pathArr[1];
            break;
        default:
            return array($path . C("MODEL_FIX") . '.php', array_pop($pathArr));
    }
    $modelFile = $arr['app_group'] . $arr['app'] . 'model/' . $arr['model'] . C("MODEL_FIX") . '.php';
    return array($modelFile, array_pop($pathArr));
}

/**
 * 加载控制器 
 */
function get_control_file($path) {
    $path = rtrim($path, '/');
    $path = str_ireplace(C("CONTROL_FIX") . ".php", '', $path);
    $path = str_replace(C("CONTROL_FIX"), '', $path);
    $pathArr = explode('/', $path);
    $arr = array();
    switch (count($pathArr)) {
        case 1:
            $arr['app_group'] = '';
            $arr['app'] = PATH_APP . '/';
            $arr['control'] = $pathArr[0];
            break;
        case 2:
            $arr['app_group'] = PATH_APP_GROUP . '/';
            $arr['app'] = $pathArr[0] . '/';
            $arr['control'] = $pathArr[1];
            break;
        default:
            return array($path . C("CONTROL_FIX") . '.php', array_pop($pathArr));
    }
    $controlFile = $arr['app_group'] . $arr['app'] . 'control/' . $arr['control'] . C("CONTROL_FIX") . '.php';
    return array($controlFile, array_pop($pathArr));
}

/**
 * 加载控制器
 * @param type $control         控制器名，可以为module.control形式调用其他模块的控制器
 */
function Control($control) {
    $controlArr = get_control_file($control);
    static $_control = array();
    $name = md5($controlArr[0]);
    if (isset($_control [$name])) {
        return $_control [$name];
    }
    //控制器文件
    $control_file = $controlArr[0];
    load_file($control_file);
    $controlClass = $controlArr[1] . "Control";
    if (class_exists($controlClass, false)) {
        $_control [$name] = new $controlClass();
        return $_control [$name];
    } else {
        error($control_file . L("functions_control_error") . $control[1], false); //" 控制器文件中没有定义类" 
    }
}

/**
 * 生成序列字符串
 * @param type $var 
 */
function md5_d($var) {
    return md5(serialize($var));
}

/**
 * 批量创建目录
 * @param $dirArr	目录名数组
 * @param $auth		权限
 */
function dir_create($dirName, $auth = 0777) {
    $dirName = str_replace("\\", "/", $dirName);
    $dirPath = substr($dirName, "-1") == "/" ? $dirName : $dirName . "/";
    if (is_dir($dirPath))
        return true;
    $dirs = explode('/', $dirPath);
    $dir = '';
    
    foreach ($dirs as $v) {
        $dir .= $v . '/';
        if (is_dir($dir))
            continue;
        @mkdir($dir, $auth,true);
        
    }
    return is_dir($dirPath);
}

/**
 * 生成对象 或执行方法
 * @param class $class      类
 * @param type $method   方法
 * @param type $args        参数
 * @return class            
 */
function O($class, $method = '', $args = array()) {
    static $result = array();
    $name = empty($args) ? $class . $method : $class . $method . md5_d($args);
    if (!isset($result [$name])) {
        $class = new $class ();
        if (!empty($method) && method_exists($class, $method)) {
            if (!empty($args)) {
                $result [$name] = call_user_func_array(array(&$class, $method), $args);
            } else {
                $result [$name] = $class->$method();
            }
        } else {
            $result [$name] = $class;
        }
    }
    return $result [$name];
}

/**
 * 载入系统类库
 * @param $type	指定载入类型如mysqli
 */
//function load_sys_class($class) {
//    switch ($class) {
//        case "hdView":
//            loadFile(PATH_HD . '/libs/view/hd/hdView.class.php');
//            break;
//        case "smartyView":
//            loadFile(PATH_HD . '/libs/view/smarty/SmartyView.class.php'); //暂未构建
//            break;
//        case "Model":
//            loadFile(PATH_HD . '/libs/model/Model.class.php');
//            break;
//        case "relationModel":
//            loadFile(PATH_HD . '/libs/model/relationModel.class.php');
//            break;
//    }
//    return;
//}

/**
 * 
 * 载入与判断文件
 * @param string 	$file
 */
function load_file($file = "") {
    static $fileArr = array();
    static $time = array();
    if (!isset($time['start'])) {
        $time['start'] = microtime(true);
    }
    if (empty($file)) {
        return $fileArr;
    }
    $file_path = realpath($file); //规范化路径
    if (!is_file($file)) {
        error($file . L("functions_load_file_is_file"), false); //" 文件不存在，载入失败"
    }
    $name = md5($file); //保存到数组中的MD5文件名
    if (isset($fileArr [$name])) {//如果在内存中存在变量   即代表文件已经加载  停止加载
        return $fileArr [$name];
    }
    if (is_file($file_path)) {//如果文件存在  则加载文件
        include $file; //载入文件
        $fileArr[$name] = array(); //保存到静态变量中
        $fileArr [$name]['path'] = $file_path;
        $fileArr[$name]['time'] = number_format(microtime(true) - $time['start'], 3);
        if (function_exists("memory_get_usage")) {
            $fileArr [$name]['memory'] = number_format(memory_get_usage() / pow(1024, 1), 0) . "kb";
        } else {
            $fileArr [$name]['memory'] = "0kb";
        }
        return true;
    } elseif (C("DEBUG")) {
        error($file . C("load_file_debug"), false); //文件不存在
    }
}

/**
 * 移除URL中的指定GET变量
 * @param string $var  要移除的GET变量名称
 * @param string $url   操作的url
 * @return string   移除GET变量后的URL地址 
 */
function url_remove_param($var, $url = null) {
        return url::url_remove_param($var, $url);
}

/**
 * 根据大小返回标准单位 KB  MB GB等
 */
function get_size($size, $decimals = 2) {
    switch (true) {
        case $size > pow(1024, 3):
            return round($size / pow(1024, 3), $decimals) . " GB";
        case $size > pow(1024, 2):
            return round($size / pow(1024, 2), $decimals) . " MB";
        case $size > pow(1024, 1):
            return round($size / pow(1024, 1), $decimals) . " KB";
        default:
            return $size . 'B';
    }
}

/**
 * 数组转为常量
 * @param ARRAY 	$arr
 */
function array_defined($arr) {
    foreach ($arr as $k => $v) {
        $k = strtoupper($k);
        if (is_string($v)) {
            define($k, $v);
        } elseif (is_numeric($v)) {
            defined($k, $v);
        } elseif (is_bool($v)) {
            $v = $v ? 'true' : 'false';
            define($k, $v);
        }
    }
    return true;
}

/**
 * 载入或设置配置顶
 * @param string	$name
 * @param string 	$value
 */
function C($name = null, $value = null) {
    static $config = array();
    if (is_null($name)) {
        return $config;
    }
    if (is_string($name)) {
        $name = strtolower($name);
        if (!strstr($name, '.')) {
            if (is_null($value)) {
                if (isset($config[$name]) && !is_array($config[$name])) {
                    $config[$name] = trim($config[$name]);
                }
                return isset($config [$name]) && !empty($config[$name]) ? $config [$name] : null;
            }
            $config [$name] = $value;
            return $config[$name];
        }
//二维数组
        $name = array_change_key_case_d(explode(".", $name), 0);
        if (is_null($value)) {
            return isset($config [$name[0]] [$name[1]]) ? $config [$name[0]][$name[1]] : null;
        }
        $config [$name[0]] [$name[1]] = $value;
    }
    if (is_array($name)) {
        $config = array_merge($config, array_change_key_case_d($name, 0));
        return true;
    }
}

//加载语言处理
function L($name = null, $value = null) {
    static $languge = array();
    if (is_null($name)) {
        return $languge;
    }
    if (is_string($name)) {
        $name = strtolower($name);
        if (!strstr($name, '.')) {
            if (is_null($value))
                return isset($languge [$name]) ? $languge [$name] : null;
            $languge [$name] = $value;
            return $languge[$name];
        }
//二维数组
        $name = array_change_key_case_d(explode(".", $name), 0);
        if (is_null($value)) {
            return isset($languge [$name[0]] [$name[1]]) ? $languge [$name[0]][$name[1]] : null;
        }
        $languge [$name[0]] [$name[1]] = $value;
    }
    if (is_array($name)) {
        $languge = array_merge($languge, array_change_key_case_d($name, 0));
        return true;
    }
}

//记录编译文件
function tplCompile($tplFile = null) {
    static $file = array();
    if (is_null($tplFile))
        return $file;
    $file[] = $tplFile;
}

/**
 * 将数组键名变成大写或小写
 * @param type $arr 
 * @param int   $type   转为大小写方式    1大写   0小写
 */
function array_change_key_case_d($arr, $type = 0) {
    $function = $type ? 'strtoupper' : 'strtolower';
    $newArr = array(); //格式化后的数组
    if (!is_array($arr) || empty($arr))
        return $newArr;
    foreach ($arr as $k => $v) {
        $k = $function($k);
        if (is_array($v)) {
            $newArr[$k] = array_change_key_case_d($v, $type);
        } else {
            $newArr[$k] = $v;
        }
    }
    return $newArr;
}

/**
 *  将数组中的值全部发为大写或小写
 * @param type $arr
 * @param type $type    1值大写 0值小写
 * @return type 
 */
function array_change_value_case($arr, $type = 1) {
    $function = $type ? 'strtoupper' : 'strtolower';
    $newArr = array(); //格式化后的数组
    foreach ($arr as $k => $v) {
        if (is_array($v)) {
            $newArr[$k] = array_change_value_case($v, $type);
        } else {
            $newArr[$k] = $function($v);
        }
    }

    return $newArr;
}

/**
 * 多个PHP文件合并
 * @param array $filenameArr    文件    
 * @param type $delSpace        是否删除注释及空格
 */
function php_merge($filenameArr, $delSpace = false) {
    if (!is_array($filenameArr)) {
        $filenameArr = array($filenameArr);
    }
    $str = ''; //格式化后的内容
    foreach ($filenameArr as $file) {
        $data = trim(file_get_contents($file));
        $data = substr($data, 0, 5) == '<?php' ? substr($data, 5) : $data;
        $data = substr($data, - 2) == '?>' ? substr($data, 0, - 2) : $data;
        if ($delSpace) {
            $data = strip_space($data);
        }
        $str.=$data;
    }
    return $str;
}

/**
 * 去空格，去除注释包括单行及多行注释
 * $data    用于操作的数据内容
 */
function strip_space($data) {
    $data = trim(trim($data), "<?php");
    $data = trim($data, '?>');
    $preg = array(
        '/(?<!\\\\)\/\*[^;\]\}\)\'"]+?\*\//is', //去多行注释
        '/(?<=[,;{}])\s*\/\/.*/im', //去代码后单行注释  
        '/^\s*\/\/.*/im', //去除前面没有代码的单行注释
            //'/(?<=})\s*/is', //去除以}开头的空白
            //'/(?<![a-z])\s*/is', //去非字符间的空白
    );
    $data = preg_replace($preg, '', $data);
    return trim($data);
}

/**
 * 抛出异常
 * @param type $msg  错误信息
 * @param type $type    异常类
 * @throws type 
 */
function throw_exception($msg, $type = "exceptionHD") {
    if (class_exists("exceptionHD")) {
        throw new $type($msg);
    } else {
        error($msg);
    }
}

/**
 * 输出错误信息
 * @param $msg		信息内容
 */
function error($error, $showFile = true) {
    $exception = array(); //错误内容
    $backtrace = debug_backtrace();
    $exception ['message'] = "<b>[ERROR]</b> " . $error . "<br/>";
    if ($showFile) {
        $exception['message'].="\t<b>[FILE]</b> " . $backtrace[0]['file'] . "<br/>";
        $exception['message'].="\t<b>[LINE]</b> " . $backtrace[0]['line'] . "<br/>";
    }
    log::write(strip_tags($exception['message'])); //写入日志
    if (!C("DEBUG")) {
        $e['message'] = C("ERROR_MESSAGE") .
                "\t\t <span style='color:#666; font-weight:normal;'>
                    " . L("functions_error_debug") . "
                    </span>"; //查看详细错误信息方法有两种： ① 查看网站日志文件  ② 开启调试模式
        include C("LIBS_ERROR_TPL");
        exit;
    }
    $e ['message'] = $exception['message'];
    include C("LIBS_ERROR_TPL");
    debug::show("app_start", "app_end");
    exit;
}

// ------------------------------------------------------------------------

/**
 * Set HTTP Status Header
 *
 * @access  public
 * @param   int     the status code
 * @param   string
 * @return  void
 */
if ( ! function_exists('set_status_header'))
{
    function set_status_header($code = 200, $text = '')
    {
        $stati = array(
                            200 => 'OK',
                            201 => 'Created',
                            202 => 'Accepted',
                            203 => 'Non-Authoritative Information',
                            204 => 'No Content',
                            205 => 'Reset Content',
                            206 => 'Partial Content',

                            300 => 'Multiple Choices',
                            301 => 'Moved Permanently',
                            302 => 'Found',
                            304 => 'Not Modified',
                            305 => 'Use Proxy',
                            307 => 'Temporary Redirect',

                            400 => 'Bad Request',
                            401 => 'Unauthorized',
                            403 => 'Forbidden',
                            404 => 'Not Found',
                            405 => 'Method Not Allowed',
                            406 => 'Not Acceptable',
                            407 => 'Proxy Authentication Required',
                            408 => 'Request Timeout',
                            409 => 'Conflict',
                            410 => 'Gone',
                            411 => 'Length Required',
                            412 => 'Precondition Failed',
                            413 => 'Request Entity Too Large',
                            414 => 'Request-URI Too Long',
                            415 => 'Unsupported Media Type',
                            416 => 'Requested Range Not Satisfiable',
                            417 => 'Expectation Failed',

                            500 => 'Internal Server Error',
                            501 => 'Not Implemented',
                            502 => 'Bad Gateway',
                            503 => 'Service Unavailable',
                            504 => 'Gateway Timeout',
                            505 => 'HTTP Version Not Supported'
                        );

        if ($code == '' OR ! is_numeric($code))
        {
            // show_error('Status codes must be numeric', 500);
            echo '状态码必须是数字';
            exit;
        }

        if (isset($stati[$code]) AND $text == '')
        {
            $text = $stati[$code];
        }

        if ($text == '')
        {
            show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
            echo '没有可用的状态描述文本。';
            exit;
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

        if (substr(php_sapi_name(), 0, 3) == 'cgi')
        {
            header("Status: {$code} {$text}", TRUE);
        }
        elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
        {
            header($server_protocol." {$code} {$text}", TRUE, $code);
        }
        else
        {
            header("HTTP/1.1 {$code} {$text}", TRUE, $code);
        }
    }
}

/**
 * 输出打印数据
 */
function show($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

/**
 * 打印输出 show的别名 
 */
function p($var) {
    show($var);
}

function dump($var) {
    show($var);
}

/**
 * 跳转网址
 */
function go($url) {
    $url = getUrl($url);
    echo "<script type='text/javascript'>location.href='$url';</script>";
    exit;
//    header("location:" . $url);exit;
}

/**
 * 将字符串格式路径转为URL  如admin/user/add 转为网址/应用组/应用/模块/控制器/方法
 * @param type $path 
 */
function getUrl($path = '') {
    if (preg_match('/http:\/\/|https:\/\//i', $path))
        return $path;
    $url = ''; //URL地址
    $pathArr = array_filter(explode('/', trim($path, '/')));
    switch (count($pathArr)) {
        case 0:
            $url = __APP__;
            break;
        case 1:
            $url = __CONTROL__ . '/' . $pathArr[0];
            break;
        case 2:
            $url = __APP__ . '/' . $pathArr[0] . '/' . $pathArr[1];
            break;
        case 3:
            $url = __WEB__ . '/' . $pathArr[0] . '/' . $pathArr[1] . '/' . $pathArr[2];
            break;
        default:
            $url = $path;
            break;
    }
    return $url;
}

/**
 * 获得客户端IP地址
 * @staticvar type $realip
 * @return type 
 */
function ip_get_client() {
    return ip::ip_get_client();
}

/**
 * 是否为AJAX提交
 * @return boolean 
 */
function ajax_request() {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        return true;
    return false;
}

/**
 * 对数组或字符串进行转义处理，数据可以是字符串或数组及对象
 * @param void $data
 * @return type 
 */
function addslashes_d($data) {
    if (is_string($data)) {
        return addslashes($data);
    }
    if (is_array($data)) {
        $var = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $var[$k] = addslashes_d($v);
                continue;
            } else {
                $var[$k] = addslashes($v);
            }
        }
        return $var;
    }
}

/**
 * 去除转义
 * @param type $data
 * @return type 
 */
function stripslashes_d($data) {
    if (is_string($data)) {
        return stripslashes($data);
    }
    if (is_array($data)) {
        $var = array();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $var[$k] = stripslashes_d($v);
                continue;
            } else {
                $var[$k] = stripslashes($v);
            }
        }
        return $var;
    }
}

//以下是缓存操作函数
/**
 * 将数据缓存
 * @param string $name    缓存名
 * @param void $data    数据内容
 * @param int $time    缓存时间 
 * @param string $path  如果是文件缓存可以设置路径
 */
function cache_set($name, $data, $time = null, $path = null) {
    $cacheObj = cacheFactory::factory();
    return $cacheObj->set($name, $data, $time, $path);
}

/**
 * 获得缓存数据
 * @param string $name  缓存名
 * @param string $path  如果是文件缓存可以设置读取路径 可以不用设置
 */
function cache_get($name, $path = null) {
    $cacheObj = cacheFactory::factory();
    return $cacheObj->get($name, $path);
}

/**
 * 将数据缓存
 * @param string $name    缓存名
 * @param int $time    缓存时间 
 * @param string $path  如果是文件缓存可以设置路径
 */
function cache_exists($name, $time = null, $path = null) {
    $cacheObj = cacheFactory::factory();
    return $cacheObj->is_cache($name, $time, $path);
}

/**
 * 删除缓存
 * @param string $name  缓存名
 * @param string $path  缓存路径 如果是文件缓存可以设置读取路径 可以不用设置
 */
function cache_del($name, $path = null) {
    $cacheObj = cacheFactory::factory();
    return $cacheObj->del($name, $path);
}

/**
 * 删除全部缓存数据 
 */
function cache_delall() {
    $cacheObj = cacheFactory::factory();
    return $cacheObj->delAll();
}

/**
 * 将数组转为字符串表示形式
 * @param array $array  数组
 * @param number $level 等级不要传参数
 * @return string   转换后的字符串 
 */
function array_to_String($array, $level = 0) {
    if (!is_array($array)) {
        return "'" . $array . "'";
    }
    $space = ''; //空白
    for ($i = 0; $i <= $level; $i++) {
        $space.="\t";
    }
    $arr = "Array\n$space(\n";
    $c = $space;
    foreach ($array as $k => $v) {
        $k = is_string($k) ? '\'' . addcslashes($k, '\'\\') . '\'' : $k;
        $v = !is_array($v) && (!preg_match("/^\-?[1-9]\d*$/", $v) || strlen($v) > 12) ?
                '\'' . addcslashes($v, '\'\\') . '\'' : $v;
        if (is_array($v)) {
            $arr.="$c$k=>" . array_to_String($v, $level + 1);
        } else {
            $arr.="$c$k=>$v";
        }
        $c = ",\n$space";
    }
    $arr.="\n$space)";
    return $arr;
}

/**
 * json_encode 
 */
if (!function_exists('json_encode')) {

    function json_encode($value) {
        $json = new json();
        return $json->encode($value);
    }

}
/**
 * json_decode 
 */
if (!function_exists('json_decode')) {

    function json_decode($json_value, $bool = false) {
        $json = new json();
        return $json->decode($json_value, $bool);
    }

}

/**
 * 手机号码查询
 * */
function mobile_area($mob) {
    $mob = substr($mob, 0, 7);
    $string = file_get_contents(PATH_HD . "/org/dat/mobile.dat");
    $string = strstr($string, $mob);
    $num = strpos($string, "\n");
    if (!$num)
        return false;
    $end = substr($string, 0, $num);
    list($a, $area) = explode(",", $end);
    $toCharset = C("charset");
    if (preg_match("/utf8|utf-8/i", $toCharset)) {
        $toCharset = "UTF-8";
    }
    return iconv("gbk", $toCharset, $area);
}

/**
 * 自动加载类
 * @param type $classname 
 */
function __autoload($classname) {
    if (substr($classname, -7) == 'Control' && strlen($classname) > 7) {
        $classFile = PATH_APP . '/control/' . $classname . '.php';
    } else {
        $classFile = PATH_HD . '/libs/bin/' . $classname . '.class.php';
    }
    if (C("USR_FILES." . $classname)) {
        $classFile = C("USR_FILES." . $classname);
    }
    load_file($classFile);
}

/**
 * 验证扩展是否加载
 * @param type $ext 
 */
function extension_exists($ext) {
    $ext = strtolower($ext);
    $loaded_extensions = get_loaded_extensions();
    return in_array($ext, array_change_value_case($loaded_extensions, 0));
}

/**
 * 根据类型获得图像扩展名
 */
if (!function_exists('image_type_to_extension')) {

    function image_type_to_extension($type, $dot = true) {
        $e = array(1 => 'gif', 'jpeg', 'png', 'swf', 'psd', 'bmp',
            'tiff', 'tiff', 'jpc', 'jp2', 'jpf', 'jb2', 'swc',
            'aiff', 'wbmp', 'xbm');
        $type = (int) $type;
        return ($dot ? '.' : '') . $e[$type];
    }

}

/**
 * 获得随机字符串
 * @param number $len 数量
 * @return string   
 */
function rand_str($len = 6) {
    $data = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $str = '';
    while (strlen($str) < $len)
        $str.=substr($data, mt_rand(0, strlen($data) - 1), 1);
    return $str;
}