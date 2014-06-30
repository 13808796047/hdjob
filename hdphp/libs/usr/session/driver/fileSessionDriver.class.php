<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: file      10:58:11
 * @author                向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
load_file(PATH_HD . '/libs/usr/session/sessionAbstract.class.php');

class fileSessionDriver extends sessionAbstract {

    public $session_save_path; //SESSION储存路径
    private $session_file; //当前用户SESSION文件

    function __construct() {
        parent::__construct();
        $this->session_save_path = PATH_SESSION;
    }

    function open() {
        return true;
    }

    function read($sid) {
        $this->session_file = $this->session_save_path . '/' . $sid;
        if (!is_file($this->session_file)) {
            return false;
        }
        return file_get_contents($this->session_file);
    }

    function write($sid, $data) {
        return file_put_contents($this->session_file, $data) ? true : false;
    }

    function destroy($sid) {
        if (is_file($this->session_file)) {
            unlink($this->session_file);
        }
    }

    function gc() {
        foreach (glob($this->session_save_path . "/*") as $file) {
            if (filemtime($file) + $this->session_lifetime < time()) {
                unlink($file);
            }
        }
        return true;
    }

}

?>
