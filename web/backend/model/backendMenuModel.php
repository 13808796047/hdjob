<?php
/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-6-24
 * Describe   : 后台菜单模型
 */

class backendMenuModel extends Model {
    public $backend_menu;

    function __construct() {
        $this->backend_menu = M('backend_menu');
    }

    /* 一级菜单列表 */

    function oneLevelMenu($cond = array('state' => 1,'parent_id' => 0)) {
        $menu = $this->backend_menu->order('sort asc')->where($cond)->findall();
        return $menu;
    }

    /**
     * 查找二级菜单
     * @param type $parent_id 父级菜单id
     * @param type $level 菜单级数(默认二级菜单)
     * @return type 菜单列表
     */
    function twoLevelMenu($parent_id, $cond = array('state' => 1)) {

        $menu = $this->backend_menu->where($cond)->in(array('parent_id' => $parent_id))->order('sort asc')->findall();
        return $menu;
    }

    function menuList() {
        if(cache_exists('menu')){
            return cache_get('menu');
        }
        $menu = array();
        $menu = $this->oneLevelMenu();
        $menu_nums = count($menu);
        for ($i = 0; $i < $menu_nums; $i++) {
            $menu[$i]['two_level'] = $this->twoLevelMenu($menu[$i]['id']);
            $two_menu_nums = count($menu[$i]['two_level']);
            for ($j = 0; $j < $two_menu_nums; $j++) {
                if ($menu[$i]['two_level'][$j]['id']) {
                    $menu[$i]['two_level'][$j]['three_level'] = $this->twoLevelMenu($menu[$i]['two_level'][$j]['id'], 3);
                }
            }
        }
        cache_set('menu',$menu);
        return $menu;
    }

    /**
     * 添加菜单
     * @param type $menu_data 菜单数据
     * @return type 插入菜单的ＩＤ
     */
    function addMenu($menu_data) {
        $insert_id = $this->backend_menu->insert($menu_data);
        cache_del('menu');
        return $insert_id;
    }

    /**
     * 取得菜单信息
     * @param int $id 菜单ID
     */
    public function getMenuInfo($id)
    {
        return $this->backend_menu->where('id='.$id)->find();
    }

    /**
     * 更新菜单
     */
    public function updateMenu($cond,$data)
    {
        return $this->backend_menu->where($cond)->update($data);
    }

    public function delMenu($id)
    {
        return $this->backend_menu->in($id)->del();
    }

}

?>