<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-3 下午10:26:17
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
final class dir {

    /**
     * 转为标准路径结构 
     * @param 	string 		目录名
     * return 	string		目录名
     */
    static public function dir_path($dir_name) {
        $dirname = str_ireplace("\\", "/", $dir_name);
        return substr($dirname, "-1") == "/" ? $dirname : $dirname . "/";
    }

    /**
     * 获得扩展名
     * @param	$file 		文件名
     * return 	string 		扩展名
     */
    static public function get_ext($file) {
        return strtolower(substr(strrchr($file, "."), 1));
    }

    /**
     * 遍历目录内容
     * @param 	$dirName	目录名
     * @param	$ext	array 扩展名
     * @param 	$son	是否显示子目录
     * return 	Array	
     */
    static function tree($dirName, $exts = '', $son = 0, $list = array()) {
        $dirPath = self::dir_path($dirName);
        static $id = 0;
        if (is_array($exts))
            $exts = implode("|", $exts);
        foreach (glob($dirPath . '*') as $v) {
            $id++;
            if (is_dir($v) || !$exts || preg_match("/\.($exts)/i", $v)) {
                $list [$id] ['name'] = basename($v);
                $list [$id] ['path'] = str_replace("\\", "/", realpath($v));
                $list [$id] ['type'] = filetype($v);
                $list [$id] ['filemtime'] = filemtime($v);
                $list [$id] ['fileatime'] = fileatime($v);
                $list [$id] ['filesize'] = filesize($v);
                $list [$id] ['iswrite'] = is_writeable($v) ? 1 : 0;
                $list [$id] ['isread'] = is_readable($v) ? 1 : 0;
            }
            if ($son) {
                if (is_dir($v)) {
                    $list = self::tree($v, $exts, $son = 1, $list);
                }
            }
        }
        return $list;
    }

    /**
     * 只显示目录树
     * @param string	$dirName	目录名
     * @param string	$pid	父目录ID
     * @param array	$dirs	目录列表	
     */
    static function tree_dir($dirName, $son = 0, $pid = 0, $dirs = array()) {
        static $id = 0;
        $dirPath = self::dir_path($dirName);
        foreach (glob($dirPath . "*") as $v) {
            if (is_dir($v)) {
                $id++;
                $dirs [$id] = array("id" => $id, 'pid' => $pid, "dirname" => basename($v), "dirpath" => $v);
                if ($son) {
                    $dirs = self::tree_dir($v, $son, $id, $dirs);
                }
            }
        }
        return $dirs;
    }

    /**
     * 
     * 删除目录，支持多层
     * @param	$dirName	目录名
     */
    static function del($dirName) {
        $dirPath = self::dir_path($dirName);
        if (!is_dir($dirName))
            return false;
        foreach (glob($dirPath . "*") as $v) {
            is_dir($v) ? self::del($v) : unlink($v);
        }
        return rmdir($dirName);
    }

    /**
     * 批量创建目录
     * @param $dirArr	目录名数组
     * @param $auth		权限
     */
    static function create($dirName, $auth = 0777) {
        if ((strlen($dirName) - strrpos($dirName, '.') ) <= 5) {
            $dirName = dirname($dirName);
        }
        $dirPath = self::dir_path($dirName);
        if (is_dir($dirPath))
            return true;
        $dirs = explode('/', $dirPath);
        $dir = '';
        foreach ($dirs as $v) {
            $dir .= $v . '/';
            if (is_dir($dir))
                continue;
                mkdir($dir, $auth);
        }
        return is_dir($dirPath);
    }

    /**
     * 复制目录
     * @param string	$olddir		原目录
     * @param string	$newdir		复制到目录
     * @param boolean     $strip_space      去空白去注释
     */
    static function copy($olddir, $newdir, $strip_space = false) {
        $olddir = self::dir_path($olddir);
        $newdir = self::dir_path($newdir);
        if (!is_dir($olddir))
            error("复制失败：" . $olddir . "目录不存在");
        if (!is_dir($newdir))
            self::create($newdir);
        foreach (glob($olddir . '*') as $v) {
            $to = $newdir . basename($v);
            if (is_file($to))
                continue;
            if (is_dir($v)) {
                self::copy($v, $to, $strip_space);
            } else {
                if ($strip_space) {
                    $data = file_get_contents($v);
                    $preg = array(
                        '/(?<!\\\\)\/\*[^;\]\}\)\'"]+?\*\//is', //去多行注释
                        '/(?<=[,;{}])\s*\/\/.*/im', //去代码后单行注释  
                        '/^\s*\/\/.*/im', //去除前面没有代码的单行注释
                            // '/(?<=})\s*/is', //去除以}开头的空白
                            // '/(?<![a-z])\s*/is', //去非字符间的空白
                    );
                    $data = preg_replace($preg, '', $data);
                    file_put_contents($to, $data);
                } else {
                    copy($v, $to);
                }
                chmod($to, "0777");
            }
        }
        return true;
    }

}