<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: cache.class.php      2012-1-12  20:35:19
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
ini_set("memory_limit", "20M");
set_time_limit(0);

class fileCache implements cacheInterface {

    public $prefix = "cache_";

    /**
     *  获得缓存文件
     * @param type $path
     * @return type 
     */
    private function getCacheFile($name, $path = null) {
        $path = is_null($path) ? CACHE_METHODL_PATH . '/cache' : $path;
        return $path . '/' . $this->prefix . md5_d($name) . '.php';
    }

    /**
     * 缓存文件是否有效
     * @param type $file
     * @param type $time 
     */
    private function isValid($name, $time, $path) {
        $cacheFile = $this->getCacheFile($name, $path);
        $time = $time ? (int) $time : C("CACHE_DEFAULT_TIME"); //缓存时间
        if (is_file($cacheFile)) {
            if ((filemtime($cacheFile) + $time ) <= time()) {//缓存文件过期时删除
                unlink($cacheFile);
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 获得缓存内容
     * @param type $name
     * @param type $path
     * @return type 
     */
    public function get($name, $path = null) {
        $varName = "cache_" . md5_d($name);
        $cacheFile = $this->getCacheFile($name, $path);
        if (is_file($cacheFile)) {
            include $cacheFile;
            return $$varName;
        }
        return false;
    }

    /**
     *  是否存在缓存，如果过期删除
     * @param type $name
     * @param type $time
     * @param type $path
     * @return boolean 
     */
    public function is_cache($name, $time = null, $path = null) {
        return $this->isValid($name, $time, $path);
    }

    /**
     * 设置缓存数据
     * @param type $args
     * @return type 
     */
    public function set($name, $data, $time = null, $path = null) {
        $cacheFile = $this->getCacheFile($name, $path);
        if ($this->isValid($name, $time, $path)) {//缓存是否有效
            return true;
        }
        $data = "<?php \r\n  if(!defined('PATH_HD'))exit;\r\n" . $this->createCatchData($name, $data) . "?>";
        dir::create(dirname($cacheFile)); //创建缓存目录
        if (file_put_contents($cacheFile, $data)) {
            return true;
        }
    }

    public function del($name, $path = null) {
        $cacheFile = $this->getCacheFile($name, $path);
        return unlink($cacheFile);
    }

    public function delAll() {
        return dir::del(PATH_APP);
    }

    /**
     * 创建缓存数据
     * @param string $name    缓存变量名
     * @param void $data    需要缓存数据
     * @return string       缓存数据 
     */
    public function createCatchData($name, $data) {
        $name = "cache_" . md5_d($name);
        $str = '';
        if (is_array($data)) {
            $str.="\$$name = " . array_to_String($data);
        } else {
            $str.="$$name = '" . addcslashes($data, '\'\\') . '\'';
        }
        return $str . ";\r\n";
    }

}

?>
