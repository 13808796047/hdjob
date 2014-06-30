<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-13 上午12:23:43
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
final class viewFactory {

    public static $viewFactory = ''; //静态工厂实例
    protected $driver_list = array(); //驱动链接组

    function __construct() {
        
    }

    /**
     * 返回工厂实例，单例模式
     */
    public static function factory($driver = null) {
        //只实例化一个对象
        if (self::$viewFactory == '') {
            self::$viewFactory = new viewFactory($driver);
        }
        if (is_null($driver)) {
            $driver = C("TPL_ENGINE");
        }
        if (isset(self::$viewFactory->driver_list[$driver])) {
            return self::$viewFactory->driver_list[$driver];
        }
        self::$viewFactory->getDriver($driver);
        return self::$viewFactory->driver_list[$driver];
    }

    /**
     * 获得数据库驱动接口
     * @param string $driver
     */
    public function getDriver($driver) {
        if (isset($this->driver_list[$driver])) {
            return $this->driver_list[$driver];
        }
        $class = $driver . "View";
        $classFile = PATH_HD . '/libs/usr/view/hd/' . $class . '.class.php'; //类文件
        load_file($classFile); //加载类文件
        $this->driver_list[$driver] = new $class(); //视图操作引擎对象
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

