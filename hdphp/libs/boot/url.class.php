<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-26 下午06:53:41
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
//路由器
final class url {

    static private $is_set_app_name; //入口文件是否定义应用
    static private $query_string; //原始URL参数
    static private $is_pathinfo; //是否为pathinfo

    /**
     * 解析URL为GET数组
     */

    static public function parse_url() {
        self::get_query_string(); //设置URL的QUERY_STRING
        self::$is_set_app_name = defined("APP_NAME") ? true : false; //是否定义APP_NAME
        $get = array(); //get参数
        if (defined("APP_NAME")) {
            $get [C('VAR_APP')] = APP_NAME;
        }
        if (self::$is_pathinfo) {
            $info = explode('/', self::$query_string);
            if (!defined("APP_NAME")) {
                if ($info [0] != C("VAR_APP")) {//应用
                    $get [C('VAR_APP')] = $info [0];
                    array_shift($info);
                } else {
                    if (!isset($get[C('VAR_APP')])) {
                        exit("<div style='padding:20px;border:solid 1px #dcdcdc;font-size:14px;'>
                            " . L("_nohaveapp") . "</div>");
                    }
                    $get [C('VAR_APP')] = $info [1];
                    array_shift($info);
                    array_shift($info);
                }
            }
            if (isset($info[0]) && $info[0] != C('VAR_CONTROL')) {//控制器
                $get [C('VAR_CONTROL')] = isset($info [0]) ? $info[0] : C("DEFAULT_CONTROL");
                array_shift($info);
            } else {
                $get [C('VAR_CONTROL')] = isset($info [1]) ? $info[1] : C("DEFAULT_CONTROL");
                array_shift($info);
                array_shift($info);
            }
            if (isset($info[0]) && $info[0] != C("VAR_METHOD")) {//方法
                $get [C('VAR_METHOD')] = isset($info [0]) ? $info[0] : C("DEFAULT_METHOD");
                array_shift($info);
            } else {
                $get [C('VAR_METHOD')] = isset($info [1]) ? $info[1] : C("DEFAULT_METHOD");
                array_shift($info);
                array_shift($info);
            }
            $count = count($info);
            for ($i = 0; $i < $count;) {
                $get [$info [$i]] = isset($info [$i + 1]) ? $info [$i + 1] : '';
                $i+=2;
            }
        }
        $_GET = array_merge($_GET, $get);
        //去除空参数
        $get =array();
        foreach($_GET as $k=>$v){
            if($v==''){
                continue;
            }
            $get[$k]=$v;
        }
        $_GET=$get;
        // $_GET = array_filter($_GET);
        self::set_url_const();
    }

    /**
     * 设置URL常量 
     */
    static private function set_url_const() {
        //当前控制器
        define("APP", (defined("APP_NAME") ? APP_NAME : (isset($_GET [C('VAR_APP')]) && !empty($_GET[C('VAR_APP')]) ? $_GET [C('VAR_APP')] : C("DEFAULT_APP"))));
        //当前控制器
        define("CONTROL", (isset($_GET [C('VAR_CONTROL')]) && !empty($_GET[C('VAR_CONTROL')]) ? $_GET [C('VAR_CONTROL')] : C("DEFAULT_CONTROL")));
        //当前动作方法
        define("METHOD", (isset($_GET [C('VAR_METHOD')]) && !empty($_GET[C('VAR_METHOD')]) ? $_GET [C('VAR_METHOD')] : C("DEFAULT_METHOD")));

        $host = $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $url = isset($_SERVER['REDIRECT_URL']) ? str_replace($_SERVER['REQUEST_URI'], '', $_SERVER['REDIRECT_URL']) : $_SERVER['SCRIPT_NAME'];
        define("__HOST__", "http://" . trim($host, '/')); //域名
        $root = trim(str_ireplace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME'])), DIRECTORY_SEPARATOR);
        $root = empty($root) ? "" : '/' . $root;
        define("__ROOT__", __HOST__ . $root); //主页URL根地址地址 
        define("__HDPHP__", __HOST__ . '/' . trim(str_ireplace($_SERVER['DOCUMENT_ROOT'], "", PATH_HD),DIRECTORY_SEPARATOR));
        define("__STATIC__", __ROOT__ . '/static');
        define("__DATA__", __ROOT__ . '/data');
        define("__WEB__", __HOST__ . $url); //带入口文件的常量

        $url_type = C("URL_TYPE"); // URL类型    1:pathinfo  2:普通模式  3:rewrite 重写
        switch ($url_type) {
            case 1:
                define("__APP__", __WEB__ . (self::$is_set_app_name ? '' : '/' . APP));
                define("__CONTROL__", __APP__ . '/' . CONTROL);
                define("__METH__", __CONTROL__ . '/' . METHOD);
                break;
            case 2:
                define("__APP__", __WEB__ . (self::$is_set_app_name ? '' : '?' . C("VAR_APP") . '=' . APP));
                define("__CONTROL__", __APP__ . (self::$is_set_app_name ? '?' : '&') . C('VAR_CONTROL') . '=' . CONTROL);
                define("__METH__", __CONTROL__ . '&' . C('VAR_METHOD') . '=' . METHOD);
                break;
        }
        define('__URL__', self::get_format_url()); //解析生成全路径URL
        define("__ORG__", __HDPHP__ . '/org');
    }

    /**
     * 根据配置项获得格式化后的URL地址
     * @return string   URL地址
     */
    static private function get_format_url() {
        $pathinfo_dli = C("PATHINFO_Dli");
        $url_type = C("URL_TYPE"); // URL类型    1:pathinfo  2:普通模式  3:rewrite 重写
        $url = ''; //组合后的URL地址
        switch ($url_type) {
            case 1:
                foreach ($_GET as $k => $v) {
                    if (in_array($k, array(C("VAR_APP"), C("VAR_CONTROL"), C("VAR_METHOD"))))
                        continue;
                    $url.=$pathinfo_dli . $k . $pathinfo_dli . $v;
                }
                $url = trim($url, $pathinfo_dli);
                $url = __METH__ . $pathinfo_dli . $url;
                break;
            case 2:
                foreach ($_GET as $k => $v) {
                    $url.=$k . '=' . $v . '&';
                }
                $url = trim($url, '&');
                $url = __WEB__ . '?' . $url;
                break;
        }
        return rtrim($url, $pathinfo_dli);
    }

    /**
     * 设置URL的QUERY_STRING
     */
    static private function get_query_string() {
        $pathinfo_var = C("PATHINFO_VAR");
        if (!isset($_GET[$pathinfo_var]) && (!isset($_SERVER['PATH_INFO']) || empty($_SERVER['PATH_INFO']))) {
            self::$is_pathinfo = FALSE;
            return;
        }

        self::$is_pathinfo = true;
        self::$query_string = isset($_GET[$pathinfo_var]) ? $_GET[$pathinfo_var] : str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
        self::$query_string = trim(self::$query_string, '/');
        $pathinfo_dli = C("PATHINFO_Dli");
        self::del_pathinfo_html();
        if (C("route")) {
            self::parse_route(); //解析处理URL路由规则
        }
        $url = explode("?", self::$query_string);
        if (count($url) >= 2) {
            //得出控制器与方法
            $arr = explode($pathinfo_dli, $url[0]);
            if (defined("APP_NAME")) {
                self::$query_string = count($arr) == 1 ? $url[0] . $pathinfo_dli . C("DEFAULT_METHOD") . $pathinfo_dli . $arr[1] : self::$query_string;
            } else {
                self::$query_string = count($arr) == 2 ? $url[0] . $pathinfo_dli . C("DEFAULT_METHOD") . $pathinfo_dli . $arr[1] : self::$query_string;
            }
        }
        self::$query_string = trim(preg_replace("/\?|=|&/i", '/', self::$query_string), '/');
        self::$query_string = str_replace($pathinfo_dli, '/', self::$query_string);
    }

    /**
     * 清除伪静态变量 
     */
    static private function del_pathinfo_html() {
        $pathinfo_html = "." . trim(C("PATHINFO_HTML"), "."); //清除伪静态变量
        self::$query_string = str_ireplace($pathinfo_html, "", self::$query_string);
    }

    /**
     * 解析路径规则
     */
    static private function parse_route() {
        $route = C("route");
        $search = array(
            "/(:year)/i",
            "/(:month)/i",
            "/(:day)/i",
            "/(:num)/i",
            "/(:any)/i",
            "/(:\w+)/i",
            "/\//",
        );
        $replace = array(
            "\d{2,4}",
            "\d{1,2}",
            "\d{1,2}",
            "\d+",
            ".+",
            "(\w+)",
            "\/",
        );

        foreach ($route as $k => $v) {
            $v = trim($v, '/');
            //正则路由
            if (preg_match("/^\/.*\/[isUx]*$/i", $k)) {
                $v = str_replace("#", '\\', $v);
                if (preg_match($k, self::$query_string)) {
                    self::$query_string = preg_replace($k, $v, self::$query_string);
                    break;
                }
                continue;
            }
            //非正则路由
            $preg_k = "/^\/?" . preg_replace($search, $replace, $k) . "$/i";
            if (!preg_match($preg_k, self::$query_string)) {
                continue;
            }
            preg_match("/[^:\sa-z0-9]/i", $k, $routeVar); //路径规则分隔符如_
            //路由规则没有设置GET参数
            if (!$routeVar) {
                self::$query_string = $v;
                break;
            }
            $role = explode($routeVar[0], $k);
            $urls = explode($routeVar[0], self::$query_string);
            self::$query_string = $v; //匹配成功的路由规则
            $getName = ''; //GET参数名称
            foreach ($role as $m => $n) {
                if (!strstr($n, ":")) {
                    continue;
                }
                $getName = str_replace(":", "", $n);
                $_GET[$getName] = $urls[$m];
            }
            break;
        }
    }

    /**
     * 移除URL中的指定GET变量
     * @param string $var  要移除的GET变量
     * @return string   移除GET变量后的URL地址 
     */
    static public function url_remove_param($var, $url = null) {
        $pathinfo_dli = C("PATHINFO_Dli");
        if (!is_null($url)) {
            $url = strstr($url, "&") ? $url . '&' : $url . $pathinfo_dli;
            $url = str_replace($pathinfo_dli, "###", $url);
            $search = array(
                "/$var" . "###" . ".*?" . "###" . "/",
                "/$var=.*?&/i",
                "/\?&/",
                "/&&/"
            );
            $replace = array(
                "",
                "",
                "?",
                ""
            );
            $url = preg_replace($search, $replace, $url);
            $url = rtrim($url, "&");
            $url = rtrim($url, "###");
            $url = str_replace("###", $pathinfo_dli, $url);
            return $url;
        }
        $get = $_GET;
        unset($get[C("VAR_APP")]);
        unset($get[C("VAR_CONTROL")]);
        unset($get[C("VAR_METHOD")]);
        $url = '';
        $url_type = C("URL_TYPE");
        if(!is_array($var)){
            $var=array($var);
        }
        foreach ($get as $k => $v) {
            // if ($k == $var)
            //     continue;
            if(in_array($k,$var)){
                continue;
            }
            if ($url_type == 1) {
                $url.=$pathinfo_dli . $k . $pathinfo_dli . $v;
            } else {
                $url.="&" . $k . "=" . $v;
            }
        }
        $url = trim($url, $pathinfo_dli);
        $url = trim($url, "&");
        $url = empty($url) ? "" : $pathinfo_dli . $url;
        if ($url_type == 1) {
            return __METH__ . $url;
        } else {
            return __METH__ . "&" . trim($url, "&");
        }
    }

}

?>