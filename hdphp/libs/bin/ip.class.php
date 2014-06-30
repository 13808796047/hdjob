<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-10 下午07:59:57
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
class ip {

    static private $ip; //用户登录IP
    static private $ipfile;

    static public function area($ip = "", $is_simple = true, $ipfile = '') {
        if (!$ip) {
            $ip = self::ip_get_client();
        }
        self::$ipfile = PATH_HD . '/org/dat/tinyipdata.dat';
        $ipfile = self::$ipfile;
//        $return = '';

        if (!file_exists($ipfile))
            $ipfile = '../' . $ipfile;
        if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
            $iparray = explode('.', $ip);
            if ($iparray [0] == 10 || $iparray [0] == 127 || ($iparray [0] == 192 && $iparray [1] == 168) || ($iparray [0] == 172 && ($iparray [1] >= 16 && $iparray [1] <= 31))) {
                return '局域网';
            } elseif ($iparray [0] > 255 || $iparray [1] > 255 || $iparray [2] > 255 || $iparray [3] > 255) {
                return 'ERR';
            } elseif ($is_simple) {
                return self::change_simply_area(self::convertip_tiny($ip, $ipfile));
            } else {
                return self::convertip_tiny($ip, $ipfile);
            }
        }
    }

    /**
     * 从ip文件得到ip所属地区
     * 
     * 过滤掉了具体的位置（如 网通/电信/**网吧） 基本到市
     * * */
    static private function convertip_tiny($ip, $ipdatafile) {

        static $fp = NULL, $offset = array(), $index = NULL;

        $ipdot = explode('.', $ip);
        $ip = pack('N', ip2long($ip));
        $ipdot [0] = (int) $ipdot [0];
        $ipdot [1] = (int) $ipdot [1];

        if ($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
            $offset = unpack('Nlen', fread($fp, 4));
            $index = fread($fp, $offset ['len'] - 4);
        } elseif ($fp == FALSE) {
            return '- Invalid IP data file';
        }

        $length = $offset ['len'] - 1028;
        $start = unpack('Vlen', $index [$ipdot [0] * 4] . $index [$ipdot [0] * 4 + 1] . $index [$ipdot [0] * 4 + 2] . $index [$ipdot [0] * 4 + 3]);

        for ($start = $start ['len'] * 8 + 1024; $start < $length; $start += 8) {
            if ($index {$start} . $index {$start + 1} . $index {$start + 2} . $index {$start + 3} >= $ip) {
                $index_offset = unpack('Vlen', $index {$start + 4} . $index {$start + 5} . $index {$start + 6} . "\x0");
                $index_length = unpack('Clen', $index {$start + 7});
                break;
            }
        }
        fseek($fp, $offset ['len'] + $index_offset ['len'] - 1024);
        if ($index_length ['len']) {
            return mb_convert_encoding(fread($fp, $index_length ['len']), 'UTF-8', 'GB2312'); //将读出的gb编码数据转成utf-8并返回
        } else {
            return '未知';
        }
    }

    static private function change_simply_area($area) {
        $tmp = explode(' ', $area); //过滤掉一些具体信息
        return $tmp [0];
    }

    static public function ip_get_client() {
        $ip = ''; //保存客户端IP地址
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            } else {
                $ip = getenv("REMOTE_ADDR");
            }
        }
        $clientIp = ''; //客户端ip
        preg_match("/[\d\.]{7,15}/", $ip, $clientIp);
        return !empty($clientIp[0]) ? $clientIp[0] : '0.0.0.0';
    }

}
