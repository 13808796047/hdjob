<?php

/**
 * 后盾网  http://www.houdunwang.com
 * 2011-7-13 上午12:23:43
 */
load_file(PATH_HD . '/libs/usr/view/view.class.php');

final class hdView extends view {

    static private $vars = array(); //模板变量
    public $tplFile; //模版文件
    private $compileFile; //编译文件
    private $endFix; //缓存文件后缀
    public $cacheFile; //缓存文件名
    public $cacheTime; //缓存时间
    private $cacheStat; //缓存是否失效

    /**
     * 模板显示
     * @param string    $tpl_file       模板文件
     * @param number    $cacheTime      缓存时间
     * @param string    $contentType    文件类型
     * @param string    $charset        字符集
     * @param boolean   $show           是否显示
     */

    public function display($tplFile = "", $cacheTime = null, $contentType = "text/html", $charset = "", $show = true) {
        $this->endFix = '.' . trim(C("TPL_FIX"), '.');
        $this->tplFile = $this->getTemplateFile($tplFile);
        $this->set_cache_file(); //设置缓存文件
        $this->set_cache_time($cacheTime); //设置缓存时间
        $this->compileFile = CACHE_COMPILE_PATH . '/' . md5_d($this->tplFile) . '.php';
        if (C("debug")) {
            tplCompile(array(basename($this->tplFile), $this->compileFile)); //记录模板编译文件
        }
        $content = false; //静态缓存数据内容
        if ($this->cacheTime > 0) {//缓存控制ssp
                dir::create(CACHE_TPL_PATH); //缓存目录
            if ($this->cacheStat || $this->is_cache($this->cacheTime)) {
                $content = file_get_contents($this->cacheFile);
            }
        }
        if ($content === false) {//不使用缓存
            if ($this->checkCompile($tplFile)) {//编译文件失效
                $this->compile();
            }
            $_CONFIG = C();
            $_LANGUAGE = L();
            if (!empty(self::$vars)) {//加载全局变量
                extract(self::$vars);
            }
            ob_start();
            include($this->compileFile);
            $content = ob_get_clean();
            if ($this->cacheTime > 0) {
                file_put_contents($this->cacheFile, $content);
            }
        }
        if ($show) {
            $charset = strtoupper(C("CHARSET")) == 'UTF8' ? "UTF-8" : strtoupper(C("CHARSET"));
            header("Content-type:" . $contentType . ';charset=' . $charset);
            echo $content;
        } else {
            return $content;
        }
    }

    /**
     * 获得视图内容 
     */
    public function fetch($tplFile = "", $cacheTime = null, $contentType = "text/html", $charset = "") {
        return $this->display($tplFile, $cacheTime, $contentType, $charset, false);
    }

    /**
     * 配置缓存时间
     */
    private function set_cache_time($cacheTime) {
        $this->cacheTime = (int) $cacheTime ? $cacheTime : ($this->cacheTime ? $this->cacheTime : intval(C("TPL_CACHE_TIME"))); //缓存时间
    }

    /**
     * 获得缓存文件 
     */
    private function set_cache_file() {
        $this->cacheFile = CACHE_TPL_PATH . '/' . md5($_SERVER['REQUEST_URI']) . '.' . trim(C("TPL_FIX"), '.');
    }

    /**
     * 验证缓存是否过期
     * @param number    $cacheTime  缓存时间
     * @return boolean 
     */
    public function is_cache($cacheTime = null) {
        $this->set_cache_file();
        $this->set_cache_time($cacheTime);
        $cacheStat = false;
        if (is_file($this->cacheFile)) {
            if ((filemtime($this->cacheFile) + $this->cacheTime) > time()) {
                $cacheStat = true;
            } else {
                @unlink($this->cacheFile);
            }
        }
        $this->cacheStat = $cacheStat;
        return $cacheStat;
    }

    /**
     * 验证编译文件是否过期  包括时间验证  是否开启调试
     * @param type $tplFile             模版文件
     * @param type $compileFile     编译文件
     */
    private function checkCompile() {
        $tplFile = $this->tplFile;
        $compileFile = $this->compileFile;
        return !file_exists($compileFile) || (filemtime($tplFile) > filemtime($compileFile)) || (C("DEBUG") && C("ALWAYS_COMPILE_TPL"));
    }

    /**
     * 解析模版 +创建编译文件
     * @param type $tplFile
     * @param type $compileFile 
     */
    public function compile() {

        if (!$this->checkCompile())
            return;
        load_file(PATH_HD . '/libs/usr/view/hd/compile.class.php');
        $assignVar = array_keys(self::$vars); //assign分配的变量
        $compileObj = new compile($this->tplFile, $this->compileFile, $assignVar);
        $compileObj->run();
    }

    /**
     * 获得编译文件内容
     * @return String
     */
    public function get_compile_content() {
        return file_get_contents($this->compileFile);
    }

    /**
     * 向模板中传入变量
     * @param type $var     变量名
     * @param type $value   变量值
     */
    public function assign($var, $value) {
//        $var = $var . "_";
        self::$vars[$var] = $value; //传入变量
    }

    function __set($name, $value) {
        if (isset(self::$vars[$name])) {
            self::$vars[$name] = $value;
        }
    }

}

