<?php

class indexControl extends commonControl {

    //版权信息|1
    function index() {
        $_SESSION['step'] = 1;
        $this->display();
    }

    //检测环境|2
    function check() {
        if ($_SESSION['step'] < 1) {
            go("index");
        }
        $_SESSION['step'] = 2;
        $uploadsize = ini_get("upload_max_filesize"); //允许上传的单个文件最大值
        $gd = gd_info(); //获得GD库所有信息
        $gdversion = $gd['GD Version']; //获得GD库版本
        $diskspace = get_size(disk_free_space('.'));
        $system = array(
            "phpversion" => PHP_VERSION,
            "phpos" => PHP_OS,
            "uploadsize" => $uploadsize,
            "gdversion" => $gdversion,
            "diskspace" => $diskspace
        );
        $this->assign("system", $system);
        $this->display();
    }

    //配置数据库连接|3
    function setconfig() {
        if ($_SESSION['step'] < 2) {
            go("check");
        }
        $_SESSION['step'] = 3;
        $this->display();
    }

    //安装数据库|4
    function installdb() {
        $_SESSION['adminpost'] = $_POST['admin'];
        if ($_SESSION['step'] < 3) {
            go("setconfig");
        }
        $_SESSION['step'] = 4;
        $this->writeconfig();
        $this->display();
    }

    //写入数据库配置
    function writeconfig() {
        $str = '<?php
                if (!defined("PATH_HD"))exit;
                return ';
        $str.=var_export($_POST['db'], true) . ';?>';
        $dbconfigfile = "../config/database.php";
        file_put_contents($dbconfigfile, $str);
    }
    /**
     * 检查数据库链接
     */
    public function checkDbConnect()
    {
        $db = mysql_connect($_POST['host'], $_POST['user'], $_POST['pwd']);
        if (!$db) {//数据库链接出错
            echo 0;
            exit;
        }
    }

    //写入数据库
    function createtable() {
        if ($_SESSION['step'] < 3) {
            go("setconifg");
        }
        $_SESSION['step'] = 4;
        $admin = $_SESSION['adminpost'];
        $username = $admin['username'];
        $password = md5_d($admin['password']);
        $email = $admin['email'];

        $dbconfig = include "../config/database.php";
        $dbprefix = $dbconfig['DB_PREFIX']; //表前缀
        $sql = PATH_APP . '/data/hd_recruit.sql';//获取数据库SQL
        $content=file_get_contents($sql);
        $search=array(
            '{_DB_PREFIX_}',
            '{_ADMIN_USER_}',
            '{_ADMIN_PASSWORD_}',
            '{_ADMIN_EMAIL_}',
            '{_ADMIN_CREATE_}'
        );
        $replace=array(
            $dbprefix,
            $username,
            $password,
            $email,
            time()
        );
        $content=str_replace($search, $replace, $content);
        $sql_array=explode('---------------INSERT--DATA--------------', $content);
        $created_sql=explode('-----------------------------------',$sql_array[0]);
        $insert_sql=explode('-----------------------------------',$sql_array[1]);

        // $preg = "/(CREATE|USE).*;/sU";
        // preg_match_all($preg, $sql, $tables);
        //连接数据库
        $db = mysql_connect($dbconfig['DB_HOST'], $dbconfig['DB_USER'], $dbconfig['DB_PASSWORD']);
        if (!$db) {
            $this->error("数据库连接配置错误");
        }
        mysql_query("SET NAMES UTF8");
        mysql_query("CREATE DATABASE IF NOT EXISTS " . $dbconfig['DB_DATABASE'] . " CHARSET utf8");
        // mysql_query("USE " . $dbconfig['DB_DATABASE']);
        mysql_select_db($dbconfig['DB_DATABASE']);
        $buffer = ini_get("output_buffering");
        $error = 0; //错误状态码
        foreach ($created_sql as $sql) {
            $preg = "/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`(.*?`)/iU";
            preg_match($preg, $sql, $tb);
            echo str_repeat(" ", $buffer); //将缓冲区填满
            if ($tb) {
                if (mysql_query($sql)) {
                    echo "<span style='font-size:14px;height:20px;display:block;width:500px;color:#89B928;'>数据表" . $tb[1] . "创建成功.....</span>";
                } else {
                    $error = 1;
                    echo "<span style='font-size:14px;height:20px;display:block;width:500px;color:#f00;'>数据表" . $tb[1] . "创建失败.....</span>";
                }
                
            } else {
                mysql_query($sql);
            }
             // sleep(1);
            ob_flush();
            flush();
        }

        //写入系统数据，包括管理员
        foreach ($insert_sql as $sql) {
            mysql_query($sql);
        }
        
        echo "<script>
            setTimeout(function(){
                    parent.window.location.href='" . __CONTROL__ . "/finish';
            },0)
            
                </script>";
        exit;
    }

    //安装完成
    function finish() {
        if ($_SESSION['step'] < 4) {
            go("index");
        }
        unset($_SESSION['step']); //卸载安装步骤
        $this->assign('root',__ROOT__);
        file_put_contents(PATH_APP . "/lock.php", "");
        $str = "<?php\ndefine('APP_GROUP', 'web');\ndefine('COMPILE', FALSE);\ninclude './hdphp/hdphp.php';\nHD::run();\n?>";
        file_put_contents(dirname(PATH_ROOT) . '/index.php', $str);
        @unlink(PATH_ROOT . "/index.html");
        $this->display();
    }

}
