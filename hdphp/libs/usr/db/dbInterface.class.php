<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: dbInterface.class.php      2012-5-20  11:04:59
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
interface dbInterface {
    public function connect_db(); //打开连接   参数为表名

    public function close(); //关闭数据库

    public function exe($sql); //发送没有返回值的sql

    public function query($sql); //有返回值的sql

    public function get_insert_id(); //获得最后插入的id

    public function get_affected_rows(); //受影响的行数

    public function get_version(); //获得版本

    public function autocommit($opt); //自动提交模式true开启false关闭

    public function commit(); //提供一个事务

    public function rollback(); //回滚事务
}

?>
