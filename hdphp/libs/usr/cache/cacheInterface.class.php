<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: cacheInterface.class.php      2012-5-20  10:40:11
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
/**
 *缓存处理接口 
 */
interface cacheInterface{
    public function set($name, $data, $time = null, $path = null);//设置缓存,如果过期删除
    public function get($name, $path = null);//获得缓存数据
    public function is_cache($name, $time = null,$path = null);//是否存在缓存,如果过期删除
    public function del($name, $path = null);//删除缓存
    public function delall();//删除所有缓存数据
}
?>
