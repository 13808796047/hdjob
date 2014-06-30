<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: classdir.php      2012-2-7  10:39:03
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
//usr系统类包
if (!defined("PATH_HD"))
    exit;
return array(
    "USR_FILES" => array(
        "hdtag" => PATH_HD . '/libs/usr/view/hd/hdTag.class.php', //核心标签
        "hdView" => PATH_HD . '/libs/usr/view/hd/hdView.class.php', //后盾视图类
        "smartyView" => PATH_HD . '/libs/usr/view/smarty/SmartyView.class.php', //smarty类
        "Model" => PATH_HD . '/libs/usr/model/Model.class.php', //模型类
        "relationModel" => PATH_HD . '/libs/usr/model/driver/relationModel.class.php', //关联模型
        "viewModel" => PATH_HD . '/libs/usr/model/driver/viewModel.class.php', //视图模型
        "dbFactory" => PATH_HD . '/libs/usr/db/dbFactory.class.php', //数据库工厂，指派驱动
        "dbInterface" => PATH_HD . '/libs/usr/db/dbInterface.class.php', //数据库接口
        "cacheFactory" => PATH_HD . '/libs/usr/cache/cacheFactory.class.php', //缓存工厂类
        "cacheInterface" => PATH_HD . '/libs/usr/cache/cacheInterface.class.php', //缓存工厂类
        "sessionFactory" => PATH_HD . '/libs/usr/session/sessionFactory.class.php', //数据库工厂，指派驱动
        "viewFactory" => PATH_HD . '/libs/usr/view/viewFactory.class.php', //视图工厂
    )
);
?>
