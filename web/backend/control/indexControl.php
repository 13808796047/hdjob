<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-6-20
 * Describe   : 后台首页
 */

class indexControl extends myControl {

    private $backend_menu;

    function __construct() {
        parent::__construct();
        $this->backend_menu = K('backendMenu');
    }

    function index() {
        $menu_list = $this->backend_menu->oneLevelMenu();
        $this->assign('menu_list', $menu_list);
        $this->display();
    }
    function home(){
        $this->display();
    }

    function pinyin(){
        echo string::pinyin($_POST['pinyin']);
        exit;
    }
    
    function update_cache() {
        if (dir::del(PATH_TEMP . '/Applications')) {
            $this->success("缓存目录已经全部删除成功");
        }
    }

}