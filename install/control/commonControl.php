<?php

class commonControl extends Control {

    //验证锁文件，配置安装步骤
    function __auto() {
        header("Content-type:text/html;charset=utf-8");
        C("debug", 0);
        $this->checklock();
        if (!isset($_SESSION['step'])) {
            $_SESSION['step'] = 0;
        }
        $this->assign("setup", include PATH_APP . '/data/setup.php');
    }

    //验证锁文件
    function checklock() {
        if (is_file(PATH_APP . '/lock.php')) {
            header("Content-type:text/html;charset=utf-8");
            $str = "<h4 style='color:#6CA1B4;font-size:18px;font-weight:bold;padding:10px;border:solid 1px #dcdcdc;'>系统已经安装，请删除install目录中的lock.php文件</h4>";
            exit($str);
        }
    }

}

?>
