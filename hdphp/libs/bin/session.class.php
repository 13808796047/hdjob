<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: session      2:28:16
 * @author               向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
final class session {

    /**
     *  开启SESSION
     *  系统会对SESSION开启状态进行自动配置，所以这个方法不要使用
     */
    static function start() {
        session_id() || session_start();
    }

    /**
     * 设置SESSION值 
     * @param string $name    键名
     * @param void $value   值
     */
    static function set($name, $value) {
        self::start();
        $_SESSION[$name] = $value;
    }

    /**
     *  获得SESSION值 
     * @param string $name    键名
     * @return void         值 
     */
    static function get($name) {
        self::start();
        return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
    }

    /**
     * 返回SESSION_NAME的值 
     * @return type 
     */
    static function get_session_name() {
        self::start();
        return session_name();
    }

    /**
     * 获得SESSION_ID
     * @return string
     */
    static function get_session_id() {
        self::start();
        return session_id();
    }

    /**
     * 删除SESSION数据
     * @param sting $name   变量名
     * @return boolean      是否删除成功
     */
    static function del($name) {
        self::start();
        if (!isset($_SESSION[$name])) {
            return false;
        }
        unset($_SESSION[$name]);
        return true;
    }

    /**
     * 删除所有SESSION值，不释放SESSION_ID
     */
    static function delall() {
        self::start();
        $_SESSION = array();
    }

    /**
     * 删除所有SESSION值，释放SESSION_ID 
     */
    static function destroy() {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * 设置SESSION存储路径
     * @param type $path 
     */
    static function set_save_path($path) {
        self::start();
        if (!is_dir($path)) {
            dir::create($path);
        }
        session_save_path($path);
    }

    /**
     * 检测变量$name的SESSION值是否存在
     * @param stirng $name    session名
     * @return boolean 
     */
    static function is_set($name) {
        self::start();
        return isset($_SESSION[$name]);
    }

    /**
     *  设置SESSION_ID生命周期
     *  @param type $time    SESSION生命周期，秒数
     */
    static function set_cookie($time = null) {
        $SESSION_COOKIE_LIFETIME = is_null($time) ? C("SESSION_COOKIE_LIFETIME") : $time;
        if ((int) $SESSION_COOKIE_LIFETIME > 0) {
            setcookie(session_name(), session_id(), time() + $SESSION_COOKIE_LIFETIME, '/');
        }
    }

}

?>
