<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: validate.class.php      2012-3-10  17:08:16
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
class validate extends HDPHP {

    public $error = false; //验证错误信息  初始没有错误信息
    private $rule; //验证规则  数组形式

    //不能为空
    public function _nonull($name, $value, $msg) {
        if (empty($value)) {
            return $msg;
        }
        return true;
    }

    public function _email($name, $value, $msg) {
        $preg = "/^\w{2,20}@\w+\.[a-z]+(\.[a-z]+)?$/i";
        if (preg_match($preg, $value)) {
            return true;
        }
        return $msg;
    }

    public function _maxlen($name, $value, $msg, $arg) {
        if (!is_numeric($value)) {
            return L("validate__maxlen1") . $name . L("validate__maxlen2");
        }
        if (!is_numeric($arg)) {
            error(L("validate__maxlen3"));
        }
        if ($value < $arg) {

            return true;
        }
        return $msg;
    }

    public function _minlen($name, $value, $msg, $arg) {
        if (!is_numeric($value)) {
            return L("validate__minlen1") . $name . L("validate__minlen2");
        }
        if (!is_numeric($arg)) {
            error(L("validate__minlen3"));
        }
        if ($value < $arg) {
            return true;
        }
        return $msg;
    }

    public function _http($name, $value, $msg, $arg) {
        $preg = "/^(http[s]?:)?(\/{2})?([a-z0-9]+\.)?[a-z0-9]+(\.(com|cn|cc|org|net|com.cn))$/i";
        if (preg_match($preg, $value)) {
            return true;
        }
        return $msg;
    }

    public function _tel($name, $value, $msg, $arg) {
        $preg = "/(?:\(\d{3,4}\)|\d{3,4}-?)\d{8}/";
        if (preg_match($preg, $value)) {
            return true;
        }
        return $msg;
    }

    public function _phone($name, $value, $msg, $arg) {
        $preg = "/^\d{11}$/";
        if (preg_match($preg, $value)) {
            return true;
        }
        return $msg;
    }

    public function _Identity($name, $value, $msg, $arg) {
        $preg = "/^\d{15} ¦\d{18}$/";
        if (preg_match($preg, $value)) {
            return true;
        }
        return $msg;
    }

    public function _length($name, $value, $msg, $arg) {
        $leng = explode(',', $arg);
        if (strlen($value) > $leng[0] && strlen($value) < $leng[1]) {
            return true;
        }
        return $msg;
    }

    public function _user($name, $value, $msg, $arg) {
        $arg = explode(',', $arg);
        $preg = "/^[a-zA-Z]\w{" . $arg[0] . ',' . $arg[1] . "}$/";
        if (preg_match($preg, $value)) {
            return true;
        }
        return $msg;
    }

    public function _num($name, $value, $msg, $arg) {
        $arg = explode(',', $arg);
        if ($value > $arg[0] && $value < $arg[1]) {
            return true;
        }
        return $msg;
    }

    public function _regexp($name, $value, $msg, $preg) {
        if (preg_match($preg, $value)) {
            return true;
        }
        return $msg;
    }

    public function _confirm($name, $value, $msg, $arg) {
        if ($value == $_POST[$arg]) {
            return true;
        }
        return $msg;
    }

}

?>
