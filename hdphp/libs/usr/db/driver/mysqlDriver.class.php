<?php

/* 后盾网  http://www.houdunwang.com
 * 向军
 * 2011-7-22 上午11:48:20
 */
load_file(PATH_HD . '/libs/usr/db/driver/mysql.class.php');

/**
 * mysqli数据库驱动
 * @author Administrator
 */
class mysqlDriver extends mysql {

    static protected $db_link = null; //是否连接
    public $link = null; //数据库连接

    function connect_db() {
        if (is_null(self::$db_link)) {
            $link = mysql_connect(C("DB_HOST"), C("DB_USER"), C("DB_PASSWORD"));
            if (!$link) {
                error(mysqli_connect_error() . L("mysqlidriver_connect_db"), false); //数据库连接出错了请检查配置文件中的参数
            } else {
                self::$db_link = $link;
                self::setCharts();
            }
        }
        $this->link = self::$db_link;
        mysql_select_db(C("DB_DATABASE"), $this->link);
        return $this->link;
    }

    /**
     * 设置字符集 
     */
    static private function setCharts() {
        $character = C("CHARSET");
        $sql = "SET character_set_connection=$character,character_set_results=$character,character_set_client=binary";
        mysql_query($sql, self::$db_link);
    }

    //获得最后插入的ID号
    public function get_insert_id() {
        return mysql_insert_id($this->link);
    }

    //获得受影响的行数
    public function get_affected_rows() {
        return mysql_affected_rows($this->link);
    }

    //遍历结果集(根据INSERT_ID)
    protected function fetch() {
        $res = mysql_fetch_assoc($this->lastquery);
        if (!$res) {
            $this->result_free();
        }
        return $res;
    }

    //执行SQL没有返回值 
    public function exe($sql) {
        $this->opt_reset(); //查询参数初始化
        $this->debug($sql); //将SQL添加到调试DEBUG
        $this->lastSql = $sql;
        $this->sqlQueryArr[] = $sql; //将SQL存入
        is_resource($this->link) or $this->connect($this->table);
        $this->lastquery = mysql_query($sql, $this->link);
        $this->querycount++;
        if (!$this->lastquery) {
            $this->error();
        }
        return $this->get_affected_rows();
    }

    //发送查询 返回数组
    public function query($sql) {
        $cache_time = $this->cache_time;
        if ($cache_time > 0) {
            if (cache_exists($sql, $cache_time, CACHE_SELECT_PATH)) {
                $this->opt_reset(); //查询参数初始化
                return cache_get($sql, CACHE_SELECT_PATH);
            }
        }
        if (!$this->exe($sql))
            return false;
        $list = array();
        if (!$this->lastquery) {
            return false;
        }
        while (($res = $this->fetch()) != false) {
            $list [] = $res;
        }
        if ($cache_time > 0) {
            cache_set($sql, $list, $cache_time, CACHE_SELECT_PATH);
        }
        return $list ? $list : false;
    }

    //释放结果集
    protected function result_free() {
        if (isset($this->lastquery)) {
            mysql_free_result($this->lastquery);
        }
        $this->result = null;
    }

    //操作错误 
    protected function error() {
        error(mysql_errno(), false);
    }

    // 错误代码 
    protected function errno() {
        error(intval(mysql_errno()), false);
    }

    // 获得MYSQL版本信息
    public function get_version() {
        is_resource($this->link) or $this->connect($this->table);
        return preg_replace("/[a-z-]/i", "", mysql_get_server_info());
    }

    //自动提交模式true开启false关闭
    public function autocommit() {
        $state = (int) func_get_arg(0);

        mysql_query("SET AUTOCOMMIT", $state);
    }

    //提供一个事务
    public function commit() {
        mysql_query("COMMIT", $this->link);
        $this->autocommit(true);
    }

    //回滚事务
    public function rollback() {
        mysql_query("ROLLBACK", $this->link);
        $this->autocommit(true);
    }

    // 释放连接资源
    public function close() {
        if (is_resource($this->link)) {
            mysql_close($this->link);
            self::$db_link = null;
            $this->link = null;
        }
    }

    //析构函数  释放连接资源
    public function __destruct() {
        $this->close();
    }

}