<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: mysql.php      2012-7-5 下午01:53:33
 * @author                向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
abstract class mysql implements dbInterface {

    protected $table = null; //表名
    public $field; //字段字符串
    public $fieldArr; //字段数组
    public $lastquery; //最后发送的查询结果集
    public $pri = null; //默认表主键
    public $opt = array(); //SQL 操作
    public $opt_old = array(); //旧的SQL操作
    static $lastSql = ''; //最后发送的SQL
    // protected $sqlQueryArr = array(); //所有sql语句数组
    protected $cache_time; //查询操作缓存时间单位秒
    protected $db_prefix; //表前缀
    /**
     * 关联参数 包括2个参数on 是否启用关联 result 如父表执行删除时的id，关联天根据此ID执行操作
     */
    protected $condition = array(
        "eq" => " = ", "neq" => " <> ",
        "gt" => " > ", "egt" => " >= ",
        "lt" => " < ", "elt" => " <= ",
    );

    public function connect($table) {
        if (is_null($this->link)) {
            $this->link = $this->connect_db(); //通过数据驱动如MYSQLI连接数据库
        }
        if (!is_null($table)) {
            $this->db_prefix = C("db_prefix"); //表前缀
            $this->table($table);
            $this->table = $table;
            $this->field = $this->opt['field'];
            $this->fieldArr = $this->opt['fieldArr'];
            $this->pri = $this->opt['pri'];
            $this->opt_reset(); //初始始化WHERE等参数
        } else {
            $this->opt_init();
        }
        return $this->link;
    }

    /**
     * 初始化表字段与主键及发送字符集
     * @param string $opt   表名
     */
    public function table($tableName) {
        if (is_null($tableName))
            return;
        $this->opt_init();
        $field = $this->get_fields($tableName); //获得表结构信息设置字段及主键属性
        $this->opt['table'] = $tableName;
        $this->opt['from_table'] = $tableName;
        $this->opt['pri'] = isset($field['pri']) && !empty($field['pri']) ? $field['pri'] : '';
        $this->opt['field'] = '`' . implode('` , ' . '`', $field['field']) . '`';
        $this->opt['fieldArr'] = $field['field'];
    }

    /**
     * 查询参数初始化
     * 每次执行curd后必须执行 
     */
    protected function opt_reset() {
        $this->opt_old = $this->opt; //将opt赋值给旧的opt_old属性
        $this->opt_old['field'] = $this->field; //修改字段为全部字段
        $this->opt_init(); //查询参数初始化
    }

    /**
     * 查询操作归位
     */
    public function opt_init() {
        $this->cache_time = intval(C("CACHE_SELECT_TIME")); //SELECT查询缓存时间
        $opt = array(
            'field' => $this->field,
            'fieldArr' => $this->fieldArr,
            'where' => '',
            'like' => '',
            'group' => '',
            'having' => '',
            'order' => '',
            'limit' => '',
            'in' => '',
            'table' => $this->table,
            'pri' => $this->pri,
            'from_table' => $this->table
        );
        $this->opt = array_merge($this->opt, $opt);
    }

    /**
     * 获得表字段
     * @param string $tableName 表名
     * @return type 
     */
    public function get_fields($tableName) {
        $tableCache = $this->get_cache_table($tableName);
        $tableField = array();
        foreach ($tableCache as $v) {
            $tableField['field'][] = $v['field'];
            if ($v['key']) {
                $tableField['pri'] = $v['field'];
            }
        }
        return $tableField;
    }

//获得表结构缓存  如果不存在则生生表结构缓存@
    private function get_cache_table($tableName) {
        $cacheName = C("DB_DATABASE") . $tableName;
        $cacheTableField = cache_get($cacheName, CACHE_TABLE_PATH);
        if ($cacheTableField)
            return $cacheTableField;
//如果缓存不存在表结构，获得表结构
        $sql = "show columns from " . $tableName;
        $fields = $this->query($sql);
        if ($fields === false) {
            error("表{$tableName}不存在", false);
        }
        $n_fields = array();
        $f = array();
        foreach ($fields as $res) {
            $f ['field'] = $res ['Field'];
            $f ['type'] = $res ['Type'];
            $f ['null'] = $res ['Null'];
            $f ['field'] = $res ['Field'];
            $f ['key'] = ($res ['Key'] == "PRI" && $res['Extra']) || $res ['Key'] == "PRI";
            $f ['default'] = $res ['Default'];
            $f ['extra'] = $res ['Extra'];
            $n_fields [] = $f;
        }
        cache_set($cacheName, $n_fields, null, CACHE_TABLE_PATH);
        return $n_fields;
    }

//将查询SQL压入调试数组
    protected function debug($sql) {
        self::$lastSql=$sql;
        if (C("DEBUG")) {
            debug::$sqlCount++; //查询次数加1
            debug::$SqlExeArr[] = $sql; //压入一条成功发送SQL
        }
    }

    /**
     * 查找记录,可以通过WHERE,ORDER,LIMIT等方法做限制
     * @param array $opt field字段列表 table表名 where条件 limit order排序
     */
    public function select($opt = '') {
        if (empty($this->opt['from_table']))
            error(L("mysql_select_error"), false); //"没有可操作的数据表"
        $opt = $this->formatArgs($opt);
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
        //添加表前缀
        $opt = array("where", "group", "having", "order", "limit");
        foreach ($opt as $v) {
            $this->opt[$v] = $this->add_table_fix($this->opt[$v]);
        }
        $sql = "SELECT " . $this->opt['field'] . " FROM " . $this->opt['from_table'] .
                $this->opt['where'] . $this->opt['group'] . $this->opt['having'] .
                $this->opt['order'] . $this->opt['limit'];
        $data = $this->query($sql);
        return $data;
    }

    //添加表前缀
    public function add_table_fix($sql) {
        $db_prefix = C("DB_PREFIX");
        $sql = preg_replace(array("/`\s*/i","/\s*`/"), array("`","`"), $sql);
        $sql = str_replace($db_prefix, "", $sql);
        return preg_replace("/\s+(`)?([a-z]\w+)(`)?([^@])?\.([a-z])/i", " \\1$db_prefix\\2\\3.\\5", $sql);
    }

    /**
     * 插入记录
     * @param type $opt  参数必须为数组
     * @return type  返回受影响记录数
     */
    public function insert($opt) {
        if (empty($this->opt['table']))
            error(L("mysql_select_error"), false); //"没有可操作的数据表"
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            error(L("mysql_insert_error"), false); //'没有任何数据要插入,系统会将$_POST值自动插入，也可以手动将数据传入或者用ORM方式，请查看HD手册学习'
        }
        $opt = $opt[0];
        if (!is_array(current($opt))) {
            $opt = array($opt);
        }
        $insert_id = array(); //插入的ID
        $emptypri = empty($this->opt['pri']);

        foreach ($opt as $k => $data) {
            if (is_string($k))
                continue;
            $value = $this->formatField($data);
            if (empty($value)) {
                error(L("mysql_insert_error2"), false); //"插入数据错误，原因可能为1：插入内容为空   2：字段名非法，看一下HD框架手册吧！"
            }
            $sql = "INSERT INTO " . $this->opt['table'] . "(" . implode(',', $value['fields']) . ")" .
                    "VALUES (" . implode(',', $value['values']) . ")";
            $insert_id[] = $this->exe($sql) ? $this->get_insert_id() : false; //执行后的结果
        }
        //没有主键
        if ($emptypri) {
            return $this->get_affected_rows();
        }
        return count($insert_id) > 1 ? $insert_id : $insert_id[0];
    }

    /**
     * SQL中的REPLACE方法，如果存在与插入记录相同的主键或unique字段进行更新操作
     * @param type $opt
     * @return type 
     */
    public function replace($opt) {
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            error(L("mysql_replace_error1"), false); //没有任何数据要插入,系统会将$_POST值自动插入，也可以手动将数据传入或者用ORM方式，请查看HD手册学习
        }
        $opt = $opt[0];
        if (!is_array(current($opt))) {
            $opt = array($opt);
        }
        $insert_id = array(); //插入的ID
        foreach ($opt as $k => $data) {
            if (is_string($k))
                continue;
            $value = $this->formatField($data);
            if (empty($value)) {
                error(L("mysql_replace_error2"), false); //插入数据错误，原因可能为1：插入内容为空   2：字段名非法，看一下HD框架手册吧！
            }
            $sql = "REPLACE INTO " . $this->opt['table'] . "(" . implode(',', $value['fields']) . ")" .
                    "VALUES (" . implode(',', $value['values']) . ")";
            $insert_id[] = $this->exe($sql) ? $this->get_insert_id() : false; //执行后的结果
        }
        return count($insert_id) > 1 ? $insert_id : $insert_id[0];
    }

    /**
     * 更新数据
     * @param type $opt
     * @return type
     */
    public function update($opt) {
        if (empty($this->opt['table']))
            error(L("mysql_select_error"), false); //"没有可操作的数据表"
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            error(L("mysql_update_error1"), false); //没有任何数据要更新,系统会将$_POST值自动更新，也可以手动将数据传入或者用ORM方式，请查看HD手册学习
        }
        $opt = $opt[0];
        if (!is_array(current($opt))) {
            $opt = array($opt);
        }
        $affected = 0; //受影响记录数
        foreach ($opt as $k => $data) {
            if (is_string($k))
                continue;
            if (empty($this->opt['where'])) {
                if (isset($data[$this->opt['pri']])) {
                    $this->opt['where'] = " WHERE " . $this->opt['pri'] . " = '" . $data[$this->opt['pri']] . "'";
                } else {
                    error(L("mysql_update_error2"), false); //UPDATE更新语句必须输入条件,如果更新数据有表的主键字段也可以做为条件使用
                }
            }
            $value = $this->formatField($data);
            if (empty($value)) {
                error(L("mysql_update_error3"), false);
            }
            $sql = "UPDATE " . $this->opt['table'] . " SET ";
            foreach ($value['fields'] as $k => $v) {
                $sql.= $v . "=" . $value['values'][$k] . ',';
            }
            $sql = trim($sql, ',') . $this->opt['where'] . $this->opt['limit'];
            $affected+= $this->exe($sql);
        }
        return $affected;
    }

    /**
     * 删除方法	传入ID可以是数组
     * @param (int|arr)	$id
     */
    public function delete($opt) {
        if (empty($this->opt['table']))
            error(L("mysql_select_error"), false); //"没有可操作的数据表"
        if (!empty($opt)) {//参数不为空时配置WHERE
            $this->where($opt);
        }
        if (empty($this->opt['where'])) {
            error(L("mysql_delete_error"), false); //DELETE删除语句必须输入条件,如果删除数据有表的主键字段也可以做为条件使用，还不清楚就看一下HD手册吧
        }
        $sql = "DELETE FROM " . $this->opt['table'] . $this->opt['where'] . $this->opt['limit'];
        $affected = $this->exe($sql);
        return $affected;
    }

    /**
     * 删除表中所有记录
     */
    public function delall($opt) {
        $sql = "DELETE FROM `{$opt['table']}`";
        return $this->exe($sql);
    }
    /**
    * 保留键为字段的数组元素 如过滤$_GET
    */
    public function field_filter($opt) {
        $opt = $this->formatArgs($opt);
        $opt = $opt[0];
        $field = array();
        foreach ($opt as $k => $v) {
            if ($this->isField($k))
                $field[$k] = $v;
        }

        return $field;
    }

    //count max min avg 方法共有的执行方法
    private function statistics($type, $opt) {
        if (empty($this->opt['from_table']))
            error(L("mysql_select_error"), false); //"没有可操作的数据表"
        $opt = $this->formatArgs($opt);
        $field_list = empty($this->opt['field']) ? '' : ',' . $this->opt['field']; //统计字段表示
        $field='';//分组参数
        if ($opt === false) {//无参数
            if (!empty($this->opt['pri'])) {
                $field = " $type(" . $this->opt['table'] . '.' . $this->opt['pri'] . ") " . " AS " . $this->opt['pri'];
            } elseif ($type == 'count') {
                $field = " $type(*) ";
            }
        } elseif (is_array($opt[0]) || (!$this->isField($opt[0]) && !strstr($opt[0], "|"))) {//参数为条件
            $this->where($opt);
            $field = " $type(" . $this->opt['table'] . '.' . $this->opt['pri'] . ") " . " AS " . $this->opt['pri'];
        } else {
            $opt = explode("|", $opt[0]);
            $as = isset($opt[1]) ? $opt[1] : $opt[0]; //别名
            $t = strstr($opt[0],".")?$opt[0]:$this->opt['table'] . '.' . $opt[0];
            $field = " $type(" . $t. ") " . " AS " . $as;
        }
        if (!$field)//如果不能组合成分组
            return;
        if (!empty($this->opt['group']) && !empty($this->opt['pri'])) {
            $field = $field . $field_list;
        }
        $this->opt['field'] = $field;
        $result = $this->select();
        return $result ? (count($result) > 1 ? $result : current($result[0])) : false;
    }

    /**
     * 统计记录总数
     */
    public function count($opt) {
        return $this->statistics(__FUNCTION__, $opt);
    }

    /**
     * 查找最大的值
     * @param type $opt 
     */
    public function max($opt = '') {
        return $this->statistics(__FUNCTION__, $opt);
    }

    /**
     * 查找最小的值
     * @param type $opt 
     */
    public function min($opt = '') {
        return $this->statistics(__FUNCTION__, $opt);
    }

    /**
     * 查找平均值
     * @param type $opt 
     */
    public function avg($opt = '') {
        return $this->statistics(__FUNCTION__, $opt);
    }

    /**
     * 得到标准的传递参数，统一转为数组
     * @param type $opt
     * @return type 
     */
    private function formatArgs($opt) {
        if (is_array($opt)) {
            $opt = array_filter($opt);
        }
        if (empty($opt)) {
            return false;
        }
        if (!is_array($opt)) {
            $opt = array($opt);
        }
        return $opt;
    }

    /**
     * 格式化SQL操作参数 字段加上标识符  值进行转义处理
     */
    private function formatField($vars) {
        $data = array(); //格式化的数据
        if (!is_array($vars)) {
            return;
        }
        foreach ($vars as $k => $v) {
            if (!$this->isField($k)) {//字段非法
                continue;
            }
            $data['fields'][] = "`" . $k . "`";
            $data['values'][] = "'" . addslashes($v) . "'";
        }
        return $data;
    }

    /**
     * 查询条件 
     * @param type $opt
     * @return type 
     */
    public function where($opt) {
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            return;
        }
        if (!strstr($this->opt['where'], 'WHERE')) {
            $this->opt['where'].= " WHERE ";
        } else {
            $this->opt['where'].=' AND ';
        }
        $condition = array_keys($this->condition);

        foreach ($opt as $args) {
            //非数时where(8)|where("uid>2");
            if (!is_array($args)) {
                if (in_array(strtolower($args), array("or", "and"))) {//是否为or and
                    $this->opt['where'] = rtrim($this->opt['where'], " AND ");
                    $this->opt['where'].=" " . strtoupper($args) . " ";
                } elseif (is_numeric($args)) {
                    $this->in($opt);
                    $this->opt['where'].=" AND ";
                    break;
                } else {
                    $this->opt['where'].=" $args " . " AND ";
                }
                continue;
            }
            foreach ($args as $k => $v) {//数组where(array("uid"=>array("gt"=>2)));
                if (is_array($v)) {
                    foreach ($v as $m => $n) {
                        if (in_array(strtolower($n), array("or", "and"))) {//是否为or|and
                            $this->opt['where'] = rtrim($this->opt['where'], " AND ");
                            $this->opt['where'].=" " . strtoupper($n) . " ";
                            continue;
                        }
                        if (is_numeric($m)) {//值为数值
                            if (is_numeric($n)) {
                                $this->in($args);
                                continue 3;
                            }
                            if (is_string($n)) {
                                $this->opt['where'].=" $n " . " AND ";
                            }
                            continue;
                        }
                        if (in_array(strtolower($m), $condition)) {
                            $n = is_numeric($n)?$n:"'$n'";
                            $this->opt['where'].=" `" . $k . "`" . $this->condition[$m] . $n  . " 
AND ";
                            continue;
                        }
                        $this->opt['where'].=$this->opt['table'] . ".$k = '$n' " . " AND ";
                    }
                    continue;
                }
                if (in_array(strtolower($v), array("or", "and"))) {//是否为or and
                    $this->opt['where'] = rtrim($this->opt['where'], " AND ");
                    $this->opt['where'].=" " . strtoupper($v) . " ";
                    continue;
                }
                if (is_numeric($k)) {//值为数值
                    if (is_numeric($v)) {
                        $this->in($args);
                        $this->opt['where'].=" AND ";
                        continue 2;
                    }
                    if (is_string($v)) {
                        $this->opt['where'].=" $v " . " AND ";
                    }
                    continue;
                }
                $this->opt['where'].=$this->opt['table'] . ".$k = '$v' " . " AND ";
            }
        }
        $this->opt['where'] = rtrim($this->opt['where'], " AND ");
    }

    /**
     * in 语句
     * @param type $opt 
     */
    public function in($opt) {
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            error(strtoupper(__FUNCTION__) . L("mysql_in_error"), false); //的参数不能为空，如果不清楚使用方式请查看HD手册学习
        }
        if (isset($opt[0]) && is_array($opt[0]))
            $opt = $opt[0];
        $in = '';
        foreach ($opt as $k => $v) {
            $field = is_numeric($k) ? $this->opt['pri'] : $k;
            $in = trim($in, ',');
            if (is_string($v)) {
                $v = trim($v);
                if (in_array(strtolower($v), array("or", "and"))) {//是否为or and
                    $this->opt['where']=preg_replace("/(AND|OR)\s*$/", "", $this->opt['where']);
                    $this->opt['where'].=" " . strtoupper($v) . " ";
                    continue;
                }
                $v = strstr($v, ',') ? explode(",", $v) : array($v); //解决字符传参1,2,3
            }
            if (is_numeric($v)) {
                $in.=',' . $v . ',';
            }
            if (is_array($v)) {
                if (is_numeric(current($v))) {
                    $in.="," . implode(",", $v) . ",";
                    continue;
                }elseif(is_array(current($v))){
                    $this->in($v);
                    continue;
                }else{
                    $in.=",'" . implode("','", $v) . "',";
                }
            }
        }
        $in = trim($in, ',');
        if (strchr($this->opt['where'], " WHERE ")) {
            $this->opt['where'] = str_replace(" WHERE ", '', $this->opt['where']);
        }
        if(empty($in)){
            $this->opt['where'] = " WHERE " . $this->opt['where'] ;
        }else{
            if(!empty($this->opt['where'])){
                if(!preg_match("/(AND|OR)\s*$/",$this->opt['where'])){
                    $this->opt['where'].=" AND ";
                }
            }
            $this->opt['where'] = " WHERE " . $this->opt['where'] . $field . " in(" . $in . ")";
        }
        
    }

    /**
     * 字段集
     * @param type $opt 
     */
    public function field($opt) {
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            return;
        }
        $field = array();
        foreach ($opt as $v) {
            if (empty($v)) {
                return;
            }
            if (is_string($v)) {
                $v = explode(",", $v);
            }
            if (!is_array($v)) {
                continue;
            }
            foreach ($v as $n) {
                if (preg_match("/count|max|min|avg/i", $n)) {
                    $field[].=$n;
                    continue;
                }
                $n = str_replace(C("DB_PREFIX"), "", $n);
                $n = strstr($n, '.') ? '`' . $this->db_prefix . str_replace(".", "`.`", $n) . '`' : '`' . $n . '`';
                $n = str_replace("|", '` AS `', $n);
                $field[].=$n;
            }
        }
        $this->opt['field'] = implode(",", $field);
    }

    /**
     * 优化表解决表碎片问题 
     */
    public function optimize($opt) {
        $opt = $this->formatArgs($opt);
        $table = $opt ? $opt[0] : $this->opt['table'];
        $table = ltrim($table, $this->db_prefix);
        $sql = "OPTIMIZE TABLE " . $this->db_prefix . $table;
        $this->exe($sql);
    }

    /**
     * limit 操作
     * @param type $opt
     * @return type 
     */
    public function limit($opt) {
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            error(strtoupper(__FUNCTION__) . L("mysql_limit_error"), false); //的参数不能为空，如果不清楚使用方式请查看HD手册学习
        }
        foreach ($opt as $v) {
            if (is_array($v)) {
                $this->limit($v);
            } else {
                $this->opt['limit'].= ',' . $v . ',';
            }
            if (strchr($this->opt['limit'], "LIMIT")) {
                $this->opt['limit'] = str_ireplace("LIMIT", '', $this->opt['limit']);
            }
            $this->opt['limit'] = trim($this->opt['limit'], ',');
        }
        $this->opt['limit'] = " LIMIT " . $this->opt['limit'];
    }

    /**
     * SQL 排序 ORDER
     * @param type $opt 
     */
    public function order($opt) {
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            error(strtoupper(__FUNCTION__) . L("mysql_order_error"), false); // 的参数，如果不清楚使用方式请查看HD手册学习
        }
        foreach ($opt as $k => $v) {
            if (is_array($v)) {
                $this->order($v);
            } else {
                if (is_numeric($k)) {
                    $this->opt['order'].= ',' . $v;
                } else {
                    $this->opt['order'].= ',' . $k . " " . $v . ',';
                }
            }
            if (strchr($this->opt['order'], "ORDER BY")) {
                $this->opt['order'] = str_ireplace("ORDER BY", '', $this->opt['order']);
            }
            $this->opt['order'] = trim($this->opt['order'], ',');
        }
        $this->opt['order'] = " ORDER BY " . $this->opt['order'];
    }

    /**
     * 分组操作
     * @param type $opt 
     */
    public function group($opt) {
        $opt = $this->formatArgs($opt);
        if ($opt === false) {
            error(strtoupper(__FUNCTION__) . L("mysql_group_error"), false); // 的参数，如果不清楚使用方式请查看HD手册学习
        }
        foreach ($opt as $v) {
            if (is_array($v)) {
                $this->group($v);
            } else {
                $this->opt['group'].= ',' . $v . ',';
            }
            if (strchr($this->opt['group'], "GROUP BY")) {
                $this->opt['group'] = str_ireplace("GROUP BY", '', $this->opt['group']);
            }
            $this->opt['group'] = trim($this->opt['group'], ',');
        }
        $this->opt['group'] = " GROUP BY " . $this->opt['group'];
    }

    /**
     * 分组条件having
     * @param type $opt 
     */
    public function having($opt) {
        $opt = $this->formatArgs($opt);
        foreach ($opt as $v) {
            if (is_array($v)) {
                $this->having($v);
            } else {
                $this->opt['having'].= ' AND ' . $v . ' AND ';
            }
            if (strchr($this->opt['having'], "HAVING")) {
                $this->opt['having'] = str_ireplace("HAVING", '', $this->opt['having']);
            }
            $this->opt['having'] = trim($this->opt['having'], ' AND ');
        }
        $this->opt['having'] = " HAVING " . $this->opt['having'];
    }

    /**
     * 验证字段是否全法
     * @param type $field 
     */
    protected function isField($field) {
        return is_string($field) && in_array($field, $this->opt['fieldArr']);
    }

    /**
     * 获得最后一条SQL
     * @return type 
     */
    public function get_last_sql() {
        return self::$lastSql;
    }

    /**
     * 获得所有SQL语句
     * @return type 
     */
    public function get_all_sql() {
        return debug::$SqlExeArr;
    }

    /**
     * SELECT结果缓存时间
     */
    public function cache($time = null) {
        $this->cache_time = (int) $time > 0 ? (int) $time[0] : -1;
    }

    /**
     * 获得数据库或表大小
     * @param void $opt 表名
     * @return type 
     */
    public function get_size($opt) {
        $tabArr = empty($opt) ? null : (is_array($opt[0]) ? $opt[0] : $opt); //表名
        $tabName = array();
        if (!is_null($tabArr)) {
            foreach ($tabArr as $v) {
                $tabName[] = strtolower(C("DB_PREFIX")) . strtolower($v);
            }
        }
        $sql = "show table status from " . C("DB_DATABASE");
        $row = $this->query($sql);
        $size = 0;
        foreach ($row as $v) {
            if (!empty($tabName)) {
                $size+=in_array(strtolower($v['Name']), $tabName) ? $v['Data_length'] + $v['Index_length'] : 0;
                continue;
            }
            $size+=$v['Data_length'] + $v['Index_length'];
        }
        return get_size($size);
    }

}