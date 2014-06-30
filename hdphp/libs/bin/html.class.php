<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: html.class.php      2012-5-15  22:28:46
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
final class html {

    public $error; //错误信息

    /**
     * 生成静态页面
     * @param type $arg     生成静态数据
     * array(控制器名，方法名，表态数据，保存表态文件路径）
     * array(news,show,1,'h/b/hd.html');表示生成news控制器中的show方法生成ID为1的文章
     */

    public function create($control, $method, $data) {
        $control = rtrim($control, C("CONTROL_FIX"));
        $file = get_control_file($control . C('CONTROL_FIX'));
        load_file($file[0]);
        $className = $file[1] . C("CONTROL_FIX");
        $obj = new $className;
       // $obj->$method();
//        $GLOBALS['html_control'] = $control;
//        $GLOBALS['html_method'] = $method;
        foreach ($data as $v) {//设置GET参数
            ob_start();
            foreach ($v as $m => $n) {
                $_GET[$m] = $n;
            }
            if (!isset($v['html_file'])) {//验证是否含有html_file内容，即生成文件的名子
                error(L("html_create_error1"), false);
                return false;
            }
            $dirname = dirname($v['html_file']);
            if (!$this->createDir($dirname)) {//创建一下生成静态的目录
                error(L("html_create_error2"));
                return false;
            }
            $obj->$method(); //执行控制器方法
            $content = ob_get_clean();
            file_put_contents($v['html_file'], $content);
        }
        return true;
    }

    /**
     * 创建目录
     * @staticvar array $dirArr
     * @param type $dir
     * @return boolean 
     */
    private function createDir($dir) {
        static $dirArr = array();
        if (isset($dirArr[$dir]))
            return true;
        if (!dir::create($dir))
            return false;
        $dirArr[$dir] = true;
        return true;
    }

    /**
     * 删除表态文件
     * @param void $name     目录名或者HTML文件
     * @return boolean     
     */
    public function del($name) {
        if (is_array($name)) {
            foreach ($name as $v) {
                if (is_file($v)) {
                    unlink($v);
                    continue;
                }
                dir::del($v);
            }
        } else {
            if (is_file($name)) {
                unlink($name);
            } else {
                dir::del($name);
            }
        }
        return true;
    }

}

?>
