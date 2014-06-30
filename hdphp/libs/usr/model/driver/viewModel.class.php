<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * @author                向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
class viewModel extends Model {

    public $view = array(); //视图配置
    public $join_model = true; //需要操作的视图模型

    /**
     * 对关联或视图等参数格式化
     * @param type $value
     * @return type 
     */

    public function join($type = false) {
        if ($type === true) {
            $this->join_model = true;
            return $this;
        }
        if ($type === false) {//不关联任何模型
            $this->join_model = false;
            return $this;
        }
        if (is_array($type)) {
            $join = $type;
        } else {
            $join = preg_replace("/\s/", '', $type);
            $join = preg_split("/[|,]/", $type);
        }
        $this->join_model = array();
        foreach ($join as $v) {
            $this->join_model[] = strtoupper($v);
        }
        return $this;
    }

    protected function get_join_args($relationArgs) {
        if ($this->join_model === false) {
            return false;
        }
        $args = array();
        $relationArgs = array_change_key_case_d($relationArgs, 0);
        foreach ($relationArgs as $table => $v) {
            if (is_array($this->join_model) && !in_array(strtoupper($table), $this->join_model)) {
                continue;
            }
            //配置表名
            $args[$table]['table'] = C("DB_PREFIX") . $table;
            //检测ON值
            if (!isset($v['on']) || empty($v['on'])) {//如果存在ON语句
                error(L('viewmodel_get_join_args1')); //定义视图必须指定ON值，如果不清楚使用规范，请参数HD框架帮助手册
            }
            $args[$table]['on'] = ' ON ' . preg_replace("/(\w+?)\./", C("DB_PREFIX") . '\1.', $v['on']);
            //检测join_type 连接类型
            if (isset($v['type']) && !in_array(strtolower($v['type']), array('left', 'right', 'inner'))) {
                error(L("viewmodel_get_join_args2")); //视图模型定义type值定义错误，type必须为'left', 'right', 'inner'之一。可以不设置TYPE值，
                //不设置将使用INNER JOIN 连接操作，如果不清楚使用规范，请参数HD框架帮助手册
            }
            if (isset($v['type'])) {
                $args[$table]['join'] = ' ' . strtoupper($v['type']) . ' JOIN';
            } else {
                $args[$table]['join'] = ' INNER JOIN';
            }
            if (isset($v['field']) && !empty($v['field'])) {//格式化字段  加上AS前缀           
                if (is_string($v['field'])) {
                    $args[$table]['field'] = preg_split("/,/", $v['field']);
                }
                $sql_field = '';
                foreach ($args[$table]['field'] as $k => $field) {
                    $field = str_replace(' ', '', $field);
                    $fieldArr = explode('|', $field);
                    if (count($fieldArr) > 1) {
                        $sql_field.= $args[$table]['table'] . '.`' . $fieldArr[0] . '` AS ' . $fieldArr[1] . ',';
                    } else {
                        $sql_field.= $args[$table]['table'] . '.`' . $field . '`,';
                    }
                }
                $args[$table]['field'] = trim($sql_field, ',');
            } else {
                $db = M();
                $field = $db->db->get_fields($args[$table]['table']); //获得表字段
                $args[$table]['field'] = $args[$table]['table'] . '.`' . implode('`,' . $args[$table]['table'] . '.`', $field['field']) . '`';
            }
        }
        return $args;
    }

    function select() {

        //查询参数
        $opt = $this->get_args(func_get_args());
        //没有关联需求时执行普通SELECT操作
        if (empty($this->view) || !$this->join_model) {
            $this->join_model = true;
            return call_user_func_array(array($this->db, __FUNCTION__), $opt);
        }
        $where = $opt;
        if (!empty($opt)) {
            foreach ($opt as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $m => $n) {
                        if (method_exists($this, $m)) {
                            call_user_func(array($this, $m), array($n));
                            unset($where[$k]);
                        }
                    }
                }
            }
            if (!empty($where)) {
                $this->where($where);
            }
        }
        $this->get_join();
        $this->join_model = true;
        return $this->db->select();
    }

    /**
     * 组合SELECT 中的from 后的语句 
     */
    protected function get_join() {
        $view_args = $this->get_join_args($this->view);
        if ($view_args == false)
            return false;
        $join = $field = '';
        foreach ($view_args as $table => $args) {
            if ($this->db->opt['field'] == $this->db->field) {
                $field.=$args['field'] . ',';
            }
            $join.=$args['join'] . ' ' . $args['table'] . ' ' . $args['on'];
        }
        $field = empty($field) ? '' : ', ' . rtrim($field, ',');
        if ($this->db->opt['field'] == $this->db->field) {
            $this->db->opt['field'] = $this->tableName . '.`' .
                    implode('`,' . $this->tableName . '.`', $this->db->opt['fieldArr']) . '`';
        }
        $this->db->opt['field'].= $field;
        $this->db->opt['from_table'] = $this->tableName . $join;
    }

}

?>