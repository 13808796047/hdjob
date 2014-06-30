<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-10 下午07:59:57
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
//异常处理类
final class exceptionHD extends Exception {

    function __construct($message, $code = 1) {
        parent::__construct($message, $code);
    }

    /**
     * 异常处理函数
     */
    static public function exception(exceptionHD $e) {
        $e->show();
    }

    //获得异常信息
    static public function getException() {
        $trace = $this->getTrace();
        $exception ['message'] = "<b>[" . L("exceptionhd_getexception1") . "] </b>" . $this->message . "<br/>";
        $exception['message'].="\t<b>[" . L("exceptionhd_getexception2") . "] </b>" . $this->getFile() . "<br/>";
        $exception['message'].="\t<b>[" . L("exceptionhd_getexception3") . "] </b>" . $this->getLine() . "<br/>";
        $info = '';
        foreach ($trace as $k => $v) {
            if (empty($v['file']))
                continue;
            $info[$k]['file'] = $v ['file'];
            $info[$k]['class'] = empty($v['class']) ? '' : $v['class'];
            $info[$k]['function'] = empty($v['function']) ? '' : $v['function'];
        }

        $exception['info'] = $info;
        return $exception;
    }

    static private function show() {
        $exception = self::getException();
        log::write(strip_tags($exception['message'])); //写入日志
        $exceptionTpl = PATH_HD . '/libs/boot/debug/tpl/exception.html';
        if (!C("DEBUG")) {
            $e['message'] = C("ERROR_MESSAGE") . "\t\t <span style='color:#666; font-weight:normal;'>" . L("exceptionhd_show") . "</span>";
            include $exceptionTpl;
            exit;
        }
        $e['message'] = $exception['message'];
        $loadfile = '<table width=100%>
            <thead><tr>
            <td>Index</td>
            <td>File</td>
            <td>Class</td>
            <td>Function</td>
            </tr></thead>';
        $info = array_reverse($exception['info']);
        foreach ($info as $k => $v) {
            $loadfile.="<tr><td>" . $k . "</td><td>" . $v['file'] . "</td><td>" . $v['class'] . "</td><td>" . $v['function'] . "</td></tr>";
        }
        $e['loadfile'] = $loadfile . "</table>";

        include $exceptionTpl;
    }

    static public function error($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_ERROR :
            case E_USER_ERROR :
                $errormsg = "[" . L("exceptionhd_error1") . "]{$errstr}\t[" . L("exceptionhd_error2") . "]$errfile\t[" . L("exceptionhd_error3") . "]$errline";
                log::write($errormsg);
                error($errormsg);
                break;
            case E_USER_WARNING :
            case E_USER_NOTICE :
            default :
                $errormsg = "[" . L("exceptionhd_error4") . "] {$errstr}\t[" . L("exceptionhd_error5") . "]{$errfile}\t[" . L("exceptionhd_error6") . "]$errline";
                self::notice(func_get_args());
                log::set($errormsg);
        }
    }

    static private function notice($e) {
        if (!C("DEBUG")) {
            return;
        }
        if (C("SHOW_NOTICE")) {
            $time = number_format(microtime(true) - debug::$runtime ['app_start'], 5);
            $memory = function_exists("memory_get_usage") ? get_size(memory_get_usage()) : '';
            $message = $e [1];
//            $file = substr($e [2], strlen($_SERVER ['DOCUMENT_ROOT']));
            $file = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $e[2]);
            $line = $e [3];
            $message = "
<div style='width:800px;margin:0px 0px 20px 20px;border:solid 1px #dcdcdc; background:#fff;'>
<h1 style='color:#000;font-size:14px; border-bottom:solid 1px #dcdcdc;
line-height:1.5em;padding:5px 20px  '><span style='display:block;'>" . L("exceptionhd_notice") . ": $message</span></h1>
	<table style='border:solid 1px #dcdcdc;width:780px;color:#4F5155; font-size:13px;background-color:#F9F9F9;margin:10px; '>
                    <tr><td style='padding:6px 10px;'>Filename: " . $file . "</td><tr>
                    <tr><td style='padding:6px 10px;'>Line: $line</td><tr>
                    <tr><td style='padding:6px 10px;'>time: $time</td><tr>
                    <tr><td style='padding:6px 10px;'>Memory: $memory</td><tr>
	</table>
</div>";
            echo $message;
        }
    }

}
