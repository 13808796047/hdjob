<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-8-9
 * Describe   : 
 */

class webConfigModel extends Model {

    private $validate_rule;

    function __construct() {
        $this->validate_rule = M('validate_rule');
    }

    /**
     * 添加验证规则
     * @param type $data 
     */
    function addRule($data) {
        return $this->validate_rule->insert($data);
    }

    /**
     * 取得所有的验证规则 
     */
    function getRule() {
        return $this->validate_rule->findall();
    }

    function editRule($condition, $data) {
        return $this->validate_rule->where($condition)->update($data)>=0;
    }

    function delRule($condition) {
        return $this->validate_rule->delete($condition);
    }

}

