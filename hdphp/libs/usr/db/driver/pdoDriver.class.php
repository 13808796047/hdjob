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
class pdoDriver extends mysql {

    static protected $db_link = null; //是否连接
    public $link = null; //数据库连接

    function connect_db() {
        if (is_null(self::$db_link)) {
            $dsn = "mysql:host=" . C("DB_HOST") . ';dbname=' . C("DB_DATABASE");
            $username = C("DB_USER");
            $password = C("DB_PASSWORD");
            try {
                self::$db_link = new Pdo($dsn, $username, $password);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
            self::setCharts();
        }
        $this->link = self::$db_link;
        return $this->link;
    }

    /**
     * 设置字符集 
     */
    static private function setCharts() {
        $character = C("CHARSET");
        $sql = "SET character_set_connection=$character,character_set_results=$character,character_set_client=binary";
        self::$db_link->query($sql);
    }

    //获得最后插入的ID号
    public function get_insert_id() {
        return $this->link->lastInsertId();
    }

    //获得受影响的行数
    public function get_affected_rows() {
        return $this->link->affected_rows;
    }

    //遍历结果集(根据INSERT_ID)
    protected function fetch() {
//        $res = $this->lastquery->fetch_assoc();
//        if (!$res) {
//            $this->result_free();
//        }
//        return $res;
    }

    //执行SQL没有返回值 
    public function exe($sql) {
        $this->opt_reset(); //查询参数初始化
        $this->debug($sql); //将SQL添加到调试DEBUG
        $this->lastSql = $sql;
        $this->sqlQueryArr[] = $sql; //将SQL存入
        is_object($this->link) or $this->connect($this->table);
        $this->lastquery = $this->link->query($sql);
        $this->querycount++;
        if (!$this->lastquery) {
            $this->error();
        }
        return $this->lastquery;
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
        if (!$this->lastquery) {
            return false;
        }
        $list = array();
        $list = $this->lastquery->fetch(PDO::FETCH_ASSOC);
        $this->result_free();
        if ($cache_time > 0) {
            cache_set($sql, $list, $cache_time, CACHE_SELECT_PATH);
        }
        return $list ? $list : false;
    }

    //释放结果集
    protected function result_free() {
        $this->lastquery = null;
    }

    //操作错误 
    protected function error() {
        error($this->link->errorInfo(), false);
    }

    // 错误代码 
    protected function errno() {
        error($this->link->errorCode(), false);
    }

    // 获得MYSQL版本信息
    public function get_version() {
        is_object($this->link) or $this->connect($this->table);
        return preg_replace("/[a-z-]/i", "", $this->link->server_info);
    }

    //自动提交模式true开启false关闭
    public function autocommit() {
        $state = func_get_arg(0);
        $this->link->autocommit($state);
    }

    //提供一个事务
    public function commit() {
        $this->link->commit();
        $this->autocommit(true);
    }

    //回滚事务
    public function rollback() {
        $this->link->rollback();
        $this->autocommit(true);
    }

    // 释放连接资源
    public function close() {
        if (is_object($this->link)) {
            $this->link->close();
        }
    }

    //析构函数  释放连接资源
    public function __destruct() {
        $this->close();
    }

}