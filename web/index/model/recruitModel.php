<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-8-9
 * Describe   : 企业发布的招聘信息
 */

class recruitModel extends Model {

    private $recruit;

    function __construct() {
        $this->recruit = M('recruit');
    }

    /**
     * 增加企业招聘
     * @param type $data 
     */
    function addRecruit($data) {
        return $this->recruit->insert($data);
    }

}

