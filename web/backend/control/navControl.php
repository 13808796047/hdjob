<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-6-25
 * Describe   : 后台菜单管理
 */

class navControl extends myControl {

    private $backend_menu;

    function __construct() {
        parent::__construct();
        $this->backend_menu = K('backendMenu');
    }

    /* 添加后台菜单 */

    function addBackendMenu() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {          
            $insert_id = $this->backend_menu->addMenu($_POST);
            if ($insert_id) {
                echo '<script>alert("添加成功");window.parent.location.href="' . __APP__ . '";</script>';
            }
        } else {
            $menu_list = $this->backend_menu->menuList();
            $this->assign('menu_list', $menu_list);
            $this->display();
        }
    }

    /**
     * 显示二级菜单 
     */
    function showTwoMenu() {
        C('DEBUG', 0);
        $menu_data = $this->backend_menu->twoLevelMenu($_POST['menuid']);
        $menu_data_nums = count($menu_data);
        for ($i = 0; $i < $menu_data_nums; $i++) {
            if (isset($menu_data[$i]['id'])) {
                $menu_data[$i]['three_menu'] = $this->backend_menu->twoLevelMenu($menu_data[$i]['id']);
            }
        }
        $this->assign('menu_data', $menu_data);
        $this->display('showTwoMenu');
    }
    //菜单列表
    function menuList() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            foreach ($_POST['sort'] as $key => $value) {
                $this->backend_menu->updateMenu('id='.$key,array('sort'=>$value));
                go('menuList');
            }
        }
        $menus=$this->backend_menu->backend_menu->findall();
        $menus=formatLevelData2($menus,array('id','parent_id'));
        $this->assign('menus',$menus);
        $this->display();
    }

    /**
     * 修改菜单
     */
    public function editMenu()
    {
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            $this->backend_menu->updateMenu('id='.$_GET['id'],$_POST);
            go('menuList');
        }
        $menu=$this->backend_menu->getMenuInfo($_GET['id']);
        $menu_list = $this->backend_menu->menuList();
        $this->assign('menu', $menu);
        $this->assign('menu_list', $menu_list);
        $this->display();
    }
    /**
     * 删除菜单
     */
    public function delMenu()
    {
        $this->backend_menu->delMenu($_POST);
        echo 1;
        exit;
    }

}