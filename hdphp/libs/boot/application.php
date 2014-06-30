<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-3-1 下午05:59:00
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
final class application {

    /**
     * 运行项目
     */
    static public function run() {
        self::init(); //初始化运行环境
        self::formatRequest(); //如果开启自动转义,去除转义
        self::setAppGroupPath(); //设置应用路径
        self::loadAppGroupConfig(); //载入应用组配置
        self::setAppPath(); //设置PATH_APP
        self::loadAppConfig(); //加载应用配置文件
        self::loadUserFile(); //加载系统类文件数据
        self::setPathConst(); //设置缓存目录常量
        self::createDemoControl(); //创建应用目录
        self::setTplConst(); //设置模板常量
        self::language(); //加载语言包
        self::ajaxCloseDebug(); //ajax时关闭调试
        self::setCharset(); //设置字符集
        self::createDir(); //创建项目缓存目录结构
        self::session_set();
        self::compileAppGroupFile(); //项目核心文件编译缓存
        self::compileAppFile(); //模块核心文件编译缓存
        debug::start("app_start"); //调试开启，需要打开配置文件调试开关
        self::apprun(); //项目开始
        debug::show("app_start", "app_end"); //显示调试结果
        log::save(); //记录日志
    }

    /**
     * 加载应用组配置文件
     */
    static private function loadAppGroupConfig() {
        if (!defined("APP_GROUP")) {
            return;
        }
        $app_group_config = PATH_APP_GROUP . '/config/config.php';
        is_file($app_group_config) && C(include $app_group_config);
        url::parse_url();
    }

    /**
     * 加载应用配置文件 
     */
    static private function loadAppConfig() {
        $app_config = PATH_APP . '/config/config.php';
        is_file($app_config) && C(include $app_config);
        if (!defined("APP_GROUP")) {//应用组没有定义路径规则
            url::parse_url();
        }
    }

    /**
     * 将系统核心类路径压入配置文件中 
     */
    static private function loadUserFile() {
        C(include(PATH_HD . '/libs/boot/usrFiles.php')); //将系统核心类路径压入配置文件中
    }

    /**
     * 设置PATH_APP_GROUP
     * @return type 
     */
    static private function setAppGroupPath() {
        if (defined("APP_GROUP_PATH")) {
            define("PATH_APP_GROUP", rtrim(str_replace("\\", "/", APP_GROUP_PATH), '/'));
            return;
        }
        if (defined("APP_GROUP")) {
            define("PATH_APP_GROUP", PATH_ROOT . '/' . APP_GROUP);
        } else {
            define("PATH_APP_GROUP", PATH_ROOT);
        }
    }

    /**
     * 设置PATH_APP
     * @return type 
     */
    static private function setAppPath() {
        if (defined("APP_PATH")) {
            define("PATH_APP", rtrim(str_replace("\\", "/", realpath(APP_PATH)), '/'));
            return;
        }
        if (defined("APP_NAME")) {
            define("PATH_APP", PATH_APP_GROUP . '/' . APP_NAME);
            return;
        }
        $var_app = C("var_app");
        if (!isset($_GET[$var_app]) && empty($_SERVER['PATH_INFO'])) {
            define("PATH_APP", PATH_APP_GROUP . '/' . C("DEFAULT_APP"));
            return;
        }
        if (isset($_GET[$var_app])) {
            define("PATH_APP", PATH_APP_GROUP . '/' . $_GET[$var_app]);
            return;
        }
        $pathinfo = rtrim($_SERVER['PATH_INFO'], '/');
        $path = explode(C("pathinfo_dli"), $pathinfo);
        foreach ($path as $k => $v) {
            if ($v == $var_app) {
                if (!isset($path[$k + 1])) {
                    header("Content-type:text/html;charset=utf-8");
                    error("URL中" . $var_app . "后连接应用名如:" . $var_app . '/admin 形式', false);
                }
                define("PATH_APP", PATH_APP_GROUP . '/' . $path[$k + 1]);
                return;
            }
        }
        define("PATH_APP", PATH_APP_GROUP . '/' . $path[0]);
    }

    //设置基础字符集
    static public function setCharset() {
        $charset = strtoupper(C("CHARSET")) == 'UTF8' ? "UTF-8" : strtoupper(C("CHARSET"));
        define("CHARSET", $charset);
    }

    //SESSION自定义处理机制
    static public function session_set() {
        $sessionDriver = sessionFactory::factory();
        $sessionDriver->init();
        if (C("SESSION_AUTO")) {
            session_start();
        }
    }

    /**
     * 系统环境初始化
     */
    static private function init() {//项目运行初始化
        C(include PATH_HD . '/libs/boot/config.php'); //加载框架核心配置文件
        C("DEBUG_TPL", PATH_HD . '/libs/boot/debug/tpl/debug.html');
        C("LIBS_ERROR_TPL", PATH_HD . '/libs/boot/debug/tpl/hd_error.html');
        @ini_set('memory_limit', '128M');
        @ini_set("register_globals", "off");
        @ini_set('magic_quotes_runtime', 0);
        define("MAGIC_QUOTES_GPC", @get_magic_quotes_gpc() ? true : false ); //是否开启系统转义
        C(require (PATH_HD . '/libs/boot/config.php')); //载入核心配置文件
        if (function_exists("spl_autoload_register")) {
            spl_autoload_register(array(__CLASS__, "autoload")); //注册自动载入函数
        }
        set_error_handler(array("exceptionHD", "error"), E_ALL); //设置错误处理函数
        set_exception_handler(array("exceptionHD", "exception")); //设置异常
       
        $_SERVER['REQUEST_URI'] = rtrim($_SERVER['REQUEST_URI'], '/');
        $_SERVER['DOCUMENT_ROOT'] = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
        $_SERVER['SCRIPT_FILENAME'] = str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']);
        if (!strstr($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
            $_SERVER['REQUEST_URI'] = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
        }
        if (isset($_SERVER['PATH_INFO'])) {
            $_SERVER['PATH_INFO'] = trim($_SERVER['PATH_INFO'], '/');
        }
        //加载系统框架核心语言包language
        $charset = C("CHARSET");
        $charset = preg_match("/utf8|utf-8/i", $charset) ? "utf8" : (preg_match("/gbk|gb2312/i", $charset) ? "gbk" : $charset);
        $systemLanguage = in_array($charset, array("utf8", "gbk")) ? $charset : "en";
        $systemLanguage = PATH_HD . '/data/language/' . $systemLanguage . '.php';
        L(include $systemLanguage);
    }

    static private function formatRequest() {
        if (!MAGIC_QUOTES_GPC) {
            return;
        }
        $_GET = stripslashes_d($_GET);
        $_POST = stripslashes_d($_POST);
        $_REQUEST = stripslashes_d($_REQUEST);
        $_COOKIE = stripslashes_d($_COOKIE);
    }

    /**
     * 设置模板常量
     */
    static private function setTplConst() {
        $tplDir = rtrim(C("TPL_DIR"), '/');
        if (empty($tplDir)) {
            $tplDir = 'tpl';
            C("TPL_DIR", $tplDir);
        }
        $template_dir = ''; //模板路径
        $style = C('TPL_STYLE') ? '/' . C('TPL_STYLE') : ''; //配置文件中模板风格
        if (strstr($tplDir, '/')) {
            $template_dir = $tplDir . $style;
        } else {
            $template_dir = PATH_APP . '/' . $tplDir . $style;
        }
        define("PATH_TPL", $template_dir);
        define("__TPL__", __HOST__ . '/' . trim(str_ireplace($_SERVER['DOCUMENT_ROOT'], '', PATH_TPL), '/')); //模版目录URL地址
        define("__PUBLIC__", __TPL__ . '/public');
    }

    /**
     * 设置系统常量
     */
    static private function setPathConst() {
        define("PATH_CONTROL", PATH_APP . '/control');
        define("CACHE_APP_GROUP_PATH", defined("APP_GROUP") ? PATH_TEMP . '/Applications/' . APP_GROUP . '_G/' : PATH_TEMP . '/Applications/'); //应用组临时缓存目录
        define("CACHE_APP_PATH", CACHE_APP_GROUP_PATH . APP . '_A'); //应用运行临时文件目录路径
        define("CACHE_CONTROL_PATH", CACHE_APP_PATH . '/' . CONTROL . '_C'); //控制器运行临时文件目录路径
        define("CACHE_METHODL_PATH", CACHE_CONTROL_PATH . '/' . METHOD); //方法运行临时文件目录路径
        define("CACHE_TABLE_PATH", PATH_TEMP . '/table'); //表结构缓存目录路径
        define("CACHE_SELECT_PATH", CACHE_METHODL_PATH . '/select'); //查询结果缓存路径
        define("CACHE_COMPILE_PATH", CACHE_METHODL_PATH . '/compile'); //模板编译文件路径
        define("CACHE_TPL_PATH", CACHE_METHODL_PATH . '/tpl'); //模板静态缓存目录路径
    }

    /**
     * 创建项目缓存目录结构 
     */
    static private function createDir() {
        if (!is_dir(CACHE_APP_PATH)) {
            dir::create(CACHE_APP_PATH);
        }
    }

    /**
     *  URL路由处理 
     */
    static private function route() {
        url::route(); //URL路由处理
    }

    /**
     * 加载语言包 
     */
    static private function language() {
        $charset = C("CHARSET");
        $charset = preg_match("/utf8|utf-8/i", $charset) ? "utf8" : (preg_match("/gbk|gb2312/i", $charset) ? "gbk" : $charset);
        $language = in_array($charset, array("utf8", "gbk")) ? $charset : "en";
        if ($language) {
            //加载核心语言包
            $systemLanguage = PATH_HD . '/data/language/' . $language . '.php';
            L(include $systemLanguage);
            //应用组及应用语言包
            $appGroupLang = PATH_APP_GROUP . '/language/' . $language . '.php';
            $appLang = PATH_APP . '/language/' . $language . '.php';

            if (is_file($appGroupLang)) {
                L(include($appGroupLang));
            }
            if (is_file($appLang)) {
                L(include($appLang));
            }
        }
    }

    //ajax时关闭调试
    static private function ajaxCloseDebug() {
        if (ajax_request() && !C("debug_ajax")) {//异步时是否关闭调试模式
            C("debug", 0);
        }
    }

    /**
     * 将项目及模块核心文件编译缓存
     */
    static private function compileAppGroupFile() {
        if (!defined('APP_GROUP')) {
            return;
        }
        $compileAppFile = CACHE_APP_GROUP_PATH . 'APP_GROUP_' . APP_GROUP . '.php';

        if (file_exists($compileAppFile) && !C("DEBUG")) {
            load_File($compileAppFile);
            return;
        }
        $appLibs = PATH_APP_GROUP . '/libs';
        if (C("DEBUG")) {
            $files = glob($appLibs . '/*');
            if (!$files)
                return;
            foreach ($files as $v) {
                load_file($v);
            }
        } else {
            $compileAppCon = ''; //应用核心文件编译内容
            $files = glob($appLibs . '/*');
            if (!$files)
                return;
            $compileAppCon = php_merge($files);
            $data = "<?php  if(!defined('PATH_HD')){exit;}" . $compileAppCon . " ?>";
            file_put_contents($compileAppFile, $data);
            load_File($compileAppFile);
        }
    }

    /**
     * 将项目及应用核心文件编译缓存
     */
    static private function compileAppFile() {
        $compileAppFile = CACHE_APP_PATH . '/APP_' . APP . '.php';
        if (file_exists($compileAppFile) && !C("DEBUG")) {
            load_File($compileAppFile);
            return;
        }
        $appLibs = PATH_APP . '/libs';
        if (C("DEBUG")) {
            $appFile = glob($appLibs . '/*');
            if (!$appFile)
                return;
            foreach ($appFile as $v) {
                load_file($v);
            }
        } else {
            $appFile = glob($appLibs . '/*');
            if (!$appFile)
                return;
            $compileAppCon = '';
            $compileAppCon = php_merge($appFile);
            $data = "<?php  if(!defined('PATH_HD')){exit;}" . $compileAppCon . " ?>";
            file_put_contents($compileAppFile, $data);
            load_File($compileAppFile);
        }
    }

    /**
     * 运行项目
     */
    static public function apprun() {
        if (function_exists("date_default_timezone_set")) {//设置时区
            date_default_timezone_set(C("default_time_zone"));
        }
        if (!is_dir(PATH_APP)) {
            error(APP.L("application_apprun4") .' <a href="'.dirname(__HDPHP__).'/setup/index.php?m=delcache&temp=D:/wamp/www/hdcms/temp">'.L("application_apprun_createapp").'</a>' , FALSE);
        }
        $controlFile = rtrim(PATH_APP, '/') . '/control/' . CONTROL;
        $control = Control($controlFile); //执行动作
        $method = strtolower(METHOD); //模块方法
        if (!method_exists($control, $method)) {
            if ($method == 'ueditorupload') {
                //ueditor
                include (PATH_HD . '/org/ueditor/hd_upload.php');
            } elseif ($method == 'keditorupload') {
                //kingeditor
                include (PATH_HD . '/org/kindeditor/hd_upload.php');
            }elseif ($method == 'swfupload') {
                //swfupload上传处理
                include (PATH_HD . '/org/swfupload250/hd_upload.php');
            } elseif ($method == 'swfuploaddel') {
                //swfupload删除文件处理
                include (PATH_HD . '/org/swfupload250/hd_delfile.php');
            } else {
               error(L("application_apprun1") . $controlFile . C("CONTROL_FIX") . '.php' . L("application_apprun2") . $method . L("application_apprun3"), false);
            }
        }
            call_user_func(array(&$control, $method));
       
    }

    /**
     * 自动载入函数
     */
    static private function autoload($classname) {
        if (substr($classname, -7) == 'Control' && strlen($classname) > 7) {
            $classFile = get_control_file($classname);
            $classFile = $classFile[0];
        } else {
            $classFile = PATH_HD . '/libs/bin/' . $classname . '.class.php';
        }
        if (C("USR_FILES." . $classname)) {
            $classFile = C("USR_FILES." . $classname);
        }
        load_file($classFile);
    }

    /**
     * 创建测试主控制器文件 
     */
    static private function createDemoControl() {
        if (is_dir(PATH_TEMP . '/Applications')) {
            return;
        }
        $demoDir = array();
        if (defined("APP_GROUP")) {
            $demoDir['demo_app_group_libs_dir'] = PATH_APP_GROUP . '/libs';
            $demoDir['demo_app_group_config_dir'] = PATH_APP_GROUP . '/config';
            $demoDir['demo_app_group_language_dir'] = PATH_APP_GROUP . '/language';
        }

        $demoDir['demo_app_dir'] = PATH_APP;
        if (is_dir($demoDir['demo_app_dir'])) {
            return;
        }
        $demoDir['demo_control_dir'] = PATH_APP . '/control';
        $demoDir['demo_model_dir'] = PATH_APP . '/model';
        $demoDir['demo_libs_dir'] = PATH_APP . '/libs';
        $demoDir['demo_config_dir'] = PATH_APP . '/config';
        $demoDir['demo_tpl_dir'] = PATH_APP . '/tpl';
        $demoDir['demo_language_dir'] = PATH_APP . '/language';
        $demoDir['demo_tpl_public_dir'] = PATH_APP . '/tpl/public';
        foreach ($demoDir as $v) {
            dir_create($v);
        }
       
        $demo_control_file = $demoDir['demo_control_dir'] . '/index' . C("CONTROL_FIX") . '.php';
        $data = <<<str
<?php
class indexControl extends Control{
    function index(){
        header("Content-type:text/html;charset=utf-8");
        echo "<div style='font-size:16px;font-weight:bold;color:#333;margin-left:20px;border:solid 2px #F00;width:500px;height:30px;padding:30px 50px 20px;'>感谢使用由后盾网提供的HD开源框架，基础目录已经创建成功！</div>";
    }
}
str;
        file_put_contents($demo_control_file, $data);
        copy(PATH_HD . '/libs/boot/config.php', $demoDir['demo_config_dir'] . '/config.php'); //复制错误页面error.html到模版文件夹下
        copy(PATH_HD . '/data/tpl/error.html', $demoDir['demo_tpl_public_dir'] . '/error.html'); //复制错误页面error.html到模版文件夹下
        copy(PATH_HD . '/data/tpl/success.html', $demoDir['demo_tpl_public_dir'] . '/success.html'); //复制错误页面error.html到模版文件夹下

        $languageDataUtf8 = <<<str
<?php
/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * @author                向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
/**
 * 本文件为语言包测试文件，在视图页面中通过{\$hd.lang.title}即可调用 
 * 可以创建任意多个语言文件如gbk,en,utf8等
 * 具体使用哪一个语言包可以能过C("language","utf8")这种方式设计或者直接修改配置文件
 */
if(!defined("PATH_HD"))exit;
return array(
    "title"=>"后盾多语言测试",
);
?>
str;
        file_put_contents($demoDir['demo_language_dir'] . '/utf8.php', $languageDataUtf8);
    }

}