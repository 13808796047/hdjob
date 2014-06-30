<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-8-14 下午03:02:18
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
//数据库驱动工厂
final class dbFactory {

    public static $dbFactory = null; //静态工厂实例
    protected $driver_list = array(); //驱动组

    /**
     * 构造函数
     */

    private function __construct() {
        
    }

    /**
     * 返回工厂实例，单例模式
     */
    public static function factory($driver = null, $tableName = null) {
        //只实例化一个对象
        if (is_null(self::$dbFactory)) {
            self::$dbFactory = new dbFactory();
        }
        if (is_null($driver)) {
            $driver = C("DB_DRIVER");
        }
        if (is_null($tableName)) {
            $tableName = 'empty';
        }
        if (isset(self::$dbFactory->driver_list[$tableName])) {
            return self::$dbFactory->driver_list[$tableName];
        }
        self::$dbFactory->getDriver($driver, $tableName);
        return self::$dbFactory->driver_list[$tableName];
    }

    /**
     * 获得数据库驱动接口
     * @param string $driver
     */
    private function getDriver($driver, $tableName) {

        $class = $driver . 'Driver'; //数据库驱动
        $classFile = PATH_HD . '/libs/usr/db/driver/' . $class . '.class.php'; //加载驱动类库文件
        
        load_file($classFile);
        $this->driver_list[$tableName] = new $class;
        $table = $tableName == 'empty' ? null : $tableName;
        $this->driver_list[$tableName]->connect($table);
    }

    /**
     * 释放连接驱动
     */
    private function close() {
        foreach ($this->driver_list as $db) {
            $db->close();
        }
    }

    /**
     * 析构函数
     * Enter description here ...
     */
    function __destruct() {
        $this->close();
    }

}
