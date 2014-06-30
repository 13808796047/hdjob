<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网 ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-8 下午09:27:00
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
//控制器基类
class Control extends HDPHP {

    protected $view = null; //视图对象
    private $rbac; //RBAC角色对象
    protected $error; //错误信息
    public $obj_html = null; //生成静态html对象

    function __init() {
        if (method_exists($this, "__auto")) {//子类如果存在auto方法，自动运行
            $this->__auto();
        }
    }

    public function __call($method, $args) {
        if (substr($method, -5) == "Model") {
            if (strstr($method, '_')) {
                $method = str_replace("_", "/", substr($method, 0, -5));
                return $this->kmodel($method);
            } else {
                return $this->kmodel(substr($method, 0, -5));
            }
        }
    }

    /**
     * 
     * @param type $tableName
     * @param type $full
     * @return type 
     */
    public function model($tableName = null, $full = null) {
        return M($tableName, $full);
    }

    /**
     * 生成扩展模型对象
     * @param type $model 
     */
    public function kmodel($model) {
        return K($model);
    }

    /**
     * 获得视图对象 
     */
    protected function getViewObj() {
        if (is_null($this->view)) {
            $this->view = viewFactory::factory();
        }
    }

    /**
     * 模板显示
     * @param string    $tpl_file       模板文件
     * @param number    $cacheTime      缓存时间
     * @param string    $contentType    文件类型
     * @param string    $charset        字符集
     * @param boolean   $show           是否显示
     */
    public function display($tplFile = "", $cacheTime = null, $contentType = "text/html", $charset = "", $show = true) {
        $this->getViewObj();
        return $this->view->display($tplFile, $cacheTime, $contentType, $charset, $show);
    }

    public function get_html_obj() {
        $args = func_get_args(); //获得参数
        if (is_null($this->html)) {
            $this->obj_html = new html();
        }
    }

    /**
     * 验证模板缓存是否过期
     * @param type $time    缓存时间，默认为1小时
     * @return type boolean 缓存是否有效
     */
    public function is_cache($time = 3600) {
        $this->getViewObj();
        return $this->view->is_cache($time);
    }

    /**
     *  生成表态文件
     * @param type $control     执行的控制器（模块）
     * @param type $method     方法名称
     * @param type $data        数据，用于组合到$_GET中，
     *                                      其中必须存在html_file元素即html文件名如array('id'=>1,'html_file'=>'h/b/1.html')
     *                                      批量生成时请传入二维数组如array(array('id'=>1,'html_file'=>'h/1.html'),array('id'=>2,'html_file'=>'/h/2.html'))
     */
    public function html_create($control, $method, $data) {
        $this->get_html_obj();
        $this->obj_html->create($control, $method, $data);
    }

    /**
     * 删除静态文件
     * @param type $name     目录名或者HTML文件
     */
    public function html_del($name) {
        $this->get_html_obj();
        $this->obj_html->del($name);
    }

    /**
     * 向模板中传入变量
     * @param type $var     变量名
     * @param type $value   变量值
     */
    public function assign($name, $value) {
        $this->getViewObj();
        $this->view->assign($name, $value);
    }

    /**
     * 错误页面处理
     * @param type $title       标题
     * @param type $msg         内容
     * @param type $url         跳转URL
     * @param type $time        时间 
     */
    public function error($msg = "", $url = "", $time = 2) {
        $msg = $msg ? $msg : L("control_error_msg");
        $time = is_numeric($time) ? $time : 3;
        $this->assign("msg", $msg);
        if ($url == "") {
            $url = "window.history.back(-1);";
        } else {
            $url = "window.location.href='" . getUrl($url) . "'";
        }
        $tplFile = C("ERROR_TPL") ? C("ERROR_TPL") : 'error' . C("TPL_FIX");
        $style = C('TPL_STYLE') ? '/' . C('TPL_STYLE') . '/' : '/'; //配置文件中模板风格
        $tpl_dir = C("TPL_DIR");
        $tpl_dir = strstr($tpl_dir, '/') ? $tpl_dir . $style : PATH_APP . '/' . $tpl_dir . $style . 'public/';
        $tpl = $tpl_dir . $tplFile;
        $this->assign("url", $url);
        $this->assign("time", $time);
        $this->display($tpl);
        exit;
    }

//成功页面处理
    public function success($msg = "", $url = "", $time = 2) {
        $msg = $msg ? $msg : L("control_success_msg");
        $time = is_numeric($time) ? $time : 3;
        $this->assign("msg", $msg);
        if ($url == "") {
            $url = "window.history.back(-1);";
        } else {
            $url = "window.location.href='" . getUrl($url) . "'";
        }
        $tplFile = C("SUCCESS_TPL") ? C("SUCCESS_TPL") : 'success' . C("TPL_FIX");
        $style = C('TPL_STYLE') ? '/' . C('TPL_STYLE') . '/' : '/'; //配置文件中模板风格
        $tpl_dir = C("TPL_DIR");
        $tpl_dir = strstr($tpl_dir, '/') ? $tpl_dir . $style : PATH_APP . '/' . $tpl_dir . $style . 'public/';
        $tpl = $tpl_dir . $tplFile;
        $this->assign("url", $url);
        $this->assign("time", $time);
        $this->display($tpl);
        exit;
    }


    /**
     * 页面直接跳转
     * @param type $url 
     */
    public function go($url) {
        go($url);
    }

    /**
     *
     * @param array $data       数据
     * @param string $root      根节点
     * @param string $encoding  编码
     * @return string           XML字符串 
     */
    public function xml_create($data, $root = null, $encoding = "UTF-8") {
        return xml::xml_create($data, $root = null, $encoding = "UTF-8");
    }

    /**
     * 将XMLL               字符串或文件转为数组
     * @param string $xml   XML字符串或XML文件
     * @return array        解析后的数组
     */
    public function xml_to_array($xml) {
        return xml::xml_to_array($xml);
    }

    /**
     *  开启SESSION
     *  系统会对SESSION开启状态进行自动配置，所以这个方法不要使用
     */
    static function session_start() {
        session::start();
    }

    /**
     * 设置SESSION值 
     * @param string $name    键名
     * @param void $value   值
     */
    function session_set($name, $value) {
        $args = func_get_args();
        return call_user_func_array(array("session", "set"), $args);
    }

    /**
     *  获得SESSION值 
     * @param string $name    键名
     * @return void         值 
     */
    function session_get($name) {
        $args = func_get_args();
        return call_user_func_array(array("session", "get"), $args);
    }

    /**
     * 返回SESSION_NAME的值 
     * @return string  
     */
    function session_get_session_name() {
        $args = func_get_args();
        return call_user_func_array(array("session", "get_session_name"), $args);
    }

    /**
     * 获得SESSION_ID
     * @return string
     */
    function session_get_session_id() {
        $args = func_get_args();
        return call_user_func_array(array("session", "get_session_id"), $args);
    }

    /**
     * 删除SESSION数据
     * @param sting $name   变量名
     * @return boolean      是否删除成功
     */
    function session_del($name) {
        $args = func_get_args();
        return call_user_func_array(array("session", "del"), $args);
    }

    /**
     * 删除所有SESSION值，不释放SESSION_ID
     */
    function session_delall() {
        $args = func_get_args();
        return call_user_func_array(array("session", "delall"), $args);
    }

    /**
     * 删除所有SESSION值，释放SESSION_ID 
     */
    function session_destroy() {
        $args = func_get_args();
        return call_user_func_array(array("session", "destroy"), $args);
    }

    /**
     * 检测变量$name的SESSION值是否存在
     * @param stirng $name    session名
     * @return boolean 
     */
    function session_is_set($name) {
        $args = func_get_args();
        return call_user_func_array(array("session", "is_set"), $args);
    }

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
    function cache_delAll() {
        $cacheObj = cacheFactory::factory();
        return $cacheObj->delAll();
    }

}