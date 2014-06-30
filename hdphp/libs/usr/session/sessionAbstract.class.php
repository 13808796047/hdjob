<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: sessionAbstract      11:50:50
 * @author               向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
abstract class sessionAbstract {

    abstract function open();

    abstract function read($sid);

    abstract function write($sid, $data);

    abstract function destroy($sid);

    abstract function gc();

    protected $session_name; //COOKIE中的session_name名称
    protected $cookie_lifetime; //SESSION_ID的COOKIE保存时间
    protected $session_lifetime; //SESSION 过期时间
    protected $session_gc_divisor; //SESSION清理频率
    protected $card; //客户端验证密匙

    //构造函数

    function __construct() {
        
    }

//初始化SESSION环境
    public function init() {
        @ini_set('session.use_trans_sid', 0);
        @ini_set('session.auto_start', 0);
        @ini_set('session.use_cookies', 1);
        $this->set_session_name();
        $this->set_session_id();
        $this->session_lifetime = (int) C("SESSION_LIFTTIME");
        $this->cookie_lifetime = (int) C("SESSION_COOKIE_LIFETIME");
        $this->session_gc_divisor = (int) C("SESSION_GC_DIVISOR");
        $this->card = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        session_set_save_handler(
                array($this, "open"), array($this, "close"), array($this, "read"), array($this, "write"), array($this, "destroy"), array($this, "gc")
        );
    }

    protected function set_session_name() {
        $this->session_name = C("SESSION_NAME") ? C("SESSION_NAME") : session_name();
        session_name($this->session_name);
    }

    public function close() {
        if (mt_rand(1, $this->session_gc_divisor) == 1) {
            $this->gc();
        }
        return true;
    }

    //设置SESSION_ID值考虑UPLOADIFY||swfupload
    protected function set_session_id() {
        if (isset($_GET[$this->session_name])) {
            session_id($_GET[$this->session_name]);
        } elseif (isset($_POST[$this->session_name])) {
            session_id($_POST[$this->session_name]);
        }elseif (isset($_COOKIE[$this->session_name])) {
            return;
        }
    }

}

?>
