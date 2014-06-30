<?php
/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-13 上午12:14:57
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */

class HDPHP {

    function __construct() {
        if (method_exists($this, '__init')) {
            $this->__init();
        }
    }

    //魔术方法__get
    function __get($var) {
        return isset($this->$var) ? $this->$var : NULL;
    }

    //魔术方法__set
    function __set($var, $value) {
        if (property_exists($this, $var)) {
            $this->$var = $value;
        }
    }

    //魔术方法__call
    function __call($method, $args) {
        if (method_exists($this, $method)) {
            $this->$method($args);
        }
    }

}