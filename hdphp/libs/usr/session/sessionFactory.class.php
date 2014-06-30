<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: session      10:57:53
 * @author               向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
//SESSION驱动工厂
final class sessionFactory {

    public static $sessionFactory = null; //静态工厂实例
    protected $driver = array(); //驱动

    /**
     * 构造函数
     */

    private function __construct() {
        
    }

    /**
     * 返回工厂实例，单例模式
     */
    public static function factory() {
        //只实例化一个对象
        if (is_null(self::$sessionFactory)) {
            self::$sessionFactory = new sessionFactory();
        }
        $driver = strtolower(C("SESSION_ENGINE"));
        if (isset(self::$sessionFactory->driver[$driver])) {
            return self::$sessionFactory->driver[$driver];
        }
        self::$sessionFactory->getDriver($driver);
        return self::$sessionFactory->driver[$driver];
    }

    /**
     * 获得数据库驱动接口
     * @param string $driver
     */
    private function getDriver($driver) {
        $class = $driver . 'SessionDriver'; //数据库驱动
        $classFile = PATH_HD . '/libs/usr/session/driver/' . $class . '.class.php'; //加载驱动类库文件
        load_file($classFile);
        $this->driver[$driver] = new $class;
    }

}

?>
