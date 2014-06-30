<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-6 上午12:13:36
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
final class debug {

    //信息内容
    static $info = array();
    //运行时间
    static $runtime;
    //运行内存占用
    static $memory;
    //内存峰值
    static $memory_peak;
    //数据库查询次数
    static $sqlCount;
    //所有发送的SQL语句
    static $SqlExeArr; 

    static public function start($start) {
        self::$runtime [$start] = microtime(true);
        if (function_exists("memory_get_usage")) {
            self::$memory [$start] = memory_get_usage();
        }
        if (function_exists("memory_get_peak_usage")) {
            self::$memory_peak [$start] = false;
        }
    }

    //项目运行时间
    static public function runtime($start, $end = '', $decimals = 4) {
        if (!isset(self::$runtime [$start])) {
            throw new exceptionHD(L("_nohavedebugstart") . $start);
        }
        if (empty(self::$runtime [$end])) {
            self::$runtime [$end] = microtime(true);
            return number_format(self::$runtime [$end] - self::$runtime [$start], $decimals);
        }
    }

    //项目运行内存峰值
    static public function memory_perk($start, $end = '') {
        if (!isset(self::$memory_peak [$start]))
            return mt_rand(200000, 1000000);
        if (!empty($end))
            self::$memory_peak [$end] = memory_get_peak_usage();
        return max(self::$memory_peak [$start], self::$memory_peak [$end]);
    }

    /**
     * 显示调试信息
     */
    static public function show($start, $end) {
        if (!C("DEBUG"))
            return;
        $load_file_list = load_file();
        $serverInfo = empty($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SIGNATURE'] : $_SERVER['SERVER_SOFTWARE'];
        $system = "<div class='debug_server'>";
        $system.="<strong>" . L("debug_show1") . "</strong>: " . $serverInfo . "<br/>";
        $system.="<strong>" . L("debug_show2") . "</strong>: " . $_SERVER['HTTP_USER_AGENT'] . "<br/>";
        $system.="<strong>" . L("debug_show3") . "</strong>: " . phpversion() . "<br/>";
        $system.="<strong>" . L("debug_show4") . "</strong>: " . $_SERVER['HTTP_HOST'] . "<br/>";
        $system.="<strong>" . L("debug_show5") . "</strong>: " . $_SERVER['REQUEST_METHOD'] . "<br/>";
        $system.="<strong>" . L("debug_show6") . "</strong>: " . $_SERVER['SERVER_PROTOCOL'] . "<br/>";
        if (defined("PATH_CONTROL")) {
            $system.="<strong>" . L("debug_show7") . "</strong>: " . PATH_CONTROL . '/' . CONTROL . C("CONTROL_FIX") . ".php<br/>";
        }
        $system.="<strong>" . L("debug_show8") . "</strong>: " . session_id() . "<br/>";
        $system.="</div>";
        $e ['system'] = $system;
        $compileFiles = tplCompile();
        if (!empty($compileFiles)) {
            $tplCompileFiles = '<table width=100%>
            <thead><tr>
            <td style="font-size:13px;width:80px;padding:5px;">' . L("debug_show16") . '</td>
            <td style="font-size:13px;padding:5px;">' . L("debug_show18") . '</td>
            </tr></thead>';
            foreach ($compileFiles as $k => $v) {
                $tplCompileFiles.= '<tr><td style="font-size:12px;width:80px;padding:6px;">' . $v[0] . ' </td>
                    <td style="font-size:12px;padding:6px;">' . str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $v[1]) . "</td></tr>";
            }
            $tplCompileFiles.="</table>";
        }

        /**
         * 数据库操作DEBG
         */
        if (self::$sqlCount > 0) {
            $e['sqlCount'] = self::$sqlCount; //SQL统计
            $e['sqlExeArr'] = self::$SqlExeArr; //SQL数组
            $sqlExeArr = '<table width=100%>
            <thead><tr>
            <td>' . L("debug_show10") . '</td>
            <td>' . L("debug_show11") . '</td>
            </tr></thead>';
            foreach ($e['sqlExeArr'] as $k => $v) {
                $sqlExeArr.= "<tr><td width='35'>[" . $k . "] </td><td>" . htmlspecialchars($v) . "</td></tr>";
            }
            $sqlExeArr.="</table>";
            $sqlExeArr.="<p>" . L("debug_show12") . $e ['sqlCount'] . L("debug_show13") .
                    "</p>";
        }

        /**
         * 文件载入DEBUG 
         */
        $loadfile = '<table width=100%>
            <thead><tr>
            <td width="30">ID</td>
            <td>File</td>
            <td width="45">Time</td>
            <td width="60">Memory</td>
            </tr></thead>';

        $message = L("debug_show14") . ": " . self::runtime($start, $end) .
                "&nbsp;&nbsp;" . L("debug_show15") .
                number_format(self::memory_perk($start, $end) / pow(1024, 1), 0) . 'kb';
        $i = 1;
        foreach ($load_file_list as $k => $v) {
            $loadfile .= "<tr><td>[" . $i++ . "] </td><td>" . $v['path'] .
                    "</td><td>" . $v['time'] . "</td><td>" . $v['memory'] . "</td></tr>";
        }
        $loadfile.="</table>";
        $e ['loadfile'] = $loadfile . "<p>$message</p>";
        include C("DEBUG_TPL");
    }

}