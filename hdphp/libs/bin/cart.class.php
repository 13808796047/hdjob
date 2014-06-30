<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: cart      2:28:58
 * @author               向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
//购物车类
final class cart {

    public static $cart_name = 'cart'; //购物车名

    /**
     * 设置购物车的存储时间，单位为秒
     * 同时会影响整个session的储存时间
     * @param type $time 
     */

    static public function life_time($time = 0) {
        session::set_cookie($time);
    }

    /**
     * 添加购物车
     * @param array $data
     * $data为数组包含以下几个值
     * $data=array(
     *  "id"=>1,                        //商品ID
     *  "name"=>"后盾网2周年西服",      //商品名称
     *  "num"=>2,                       //商品数量
     *  "price"=>188.88,                //商品价格
     *  "options"=>array(               //其他参数，如价格、颜色可以是数组或字符串|可以不添加    
     *      "color"=>"red", 
     *      "size"=>"L"
     *  )    
     */
    static function add($data) {
        if (!is_array($data) || !isset($data['id']) || !isset($data['name']) || !isset($data['num']) || !isset($data['price'])) {
            error(L("cart_add_error"));
        }
        $data = isset($data[0]) ? $data : array($data);
        $goods = self::get_goods(); //获得商品数据
        foreach ($data as $v) {
            $options = isset($v['options']) ? $v['options'] : '';
            $sid = $v['id'] . md5_d($options); //生成维一ID用于处理相同商品有不同属性时
            if (isset($goods[$sid])) {
                if ($v['num'] == 0) {//如果数量为0删除商品
                    unset($goods[$sid]);
                    continue;
                }
                $goods[$sid] = $v;
                $goods[$sid]['total'] = $v['num'] * $v['price'];
            } else {
                if ($v['num'] == 0)
                    continue;
                $goods[$sid] = $v;
                $goods[$sid]['total'] = $v['num'] * $v['price'];
            }
        }
        self::save($goods);
    }

    static private function save($goods) {
        $_SESSION[self::$cart_name]['goods'] = $goods;
        $_SESSION[self::$cart_name]['total_rows'] = self::get_total_rows();
        $_SESSION[self::$cart_name]['total'] = self::get_total();
    }

    /**
     * 更新购物车
     * @param array $data
     * $data为数组包含以下几个值
     * $data=array(
     *  "sid"=>1,                        //商品的唯一SID，不是商品的ID
     *  "num"=>2,                       //商品数量
     */
    static function update($data) {
        $goods = self::get_goods(); //获得商品数据
        if (!isset($data['sid']) || !isset($data['num'])) {
            error(L("cart_update_error"));
        }
        $data = isset($data[0]) ? $data : array($data); //允许一次删除多个商品
        foreach ($data as $dataOne) {
            foreach ($goods as $k => $v) {
                if ($k == $dataOne['sid']) {
                    if ($dataOne['num'] == 0) {
                        unset($goods[$k]);
                        continue;
                    }
                    $goods[$k]['num'] = $dataOne['num'];
                }
            }
        }
        self::save($goods);
    }

    /**
     * 统计购物车中商品数量
     */
    static function get_total_rows() {
        $goods = self::get_goods(); //获得商品数据
        $rows = 0;
        foreach ($goods as $v) {
            $rows+=$v['num'];
        }
        return $rows;
    }

    /**
     * 获得商品汇总价格
     */
    static function get_total() {
        $goods = self::get_goods(); //获得商品数据
        $total = 0;
        foreach ($goods as $v) {
            $total+=$v['price'] * $v['num'];
        }
        return $total;
    }

    /**
     * 删除购物车
     * @param array $data 
     * 必须传递商品的sid值
     */
    static function del($data) {
        $goods = self::get_goods(); //获得商品数据
        if (empty($goods)) {
            return false;
        }
        $sid = array(); //要删除的商品SID集合
        if (is_string($data)) {
            $sid['sid'] = $data;
        }
        if (is_array($data) && !isset($data['sid'])) {
            error(L("cart_del_error"));
        }

        $sid = isset($sid[0]) ? $sid : array($sid); //可以一次删除多个商品
        foreach ($sid as $d) {
            foreach ($goods as $k => $v) {
                if ($k == $d['sid']) {
                    unset($goods[$k]);
                }
            }
        }
        self::save($goods);
    }

    /**
     * 清空购物车中的所有商品 
     */
    static function delAll() {
        $data = array();
        $data['goods'] = array();
        $data['total_rows'] = 0;
        $data['total'] = 0;
        session::set(self::$cart_name, $data);
    }

    /**
     * 获得购物车商品数据 
     */
    static function get_goods() {
        $cart_name = C("CART_NAME") ? strtolower(C("CART_NAME")) : self::$cart_name;
        $data = session::get(self::$cart_name);
        if ($data == false) {
            $data = array("goods" => array(), "total_rows" => 0, "total" => 0);
            session::set(self::$cart_name, $data);
        }
        return $data['goods'];
    }

    /**
     * 获得购物车中的所有数据，包括商品数据、总数量、总价格 
     */
    static function get_all_data() {
        return session::get(self::$cart_name);
    }

}

?>
