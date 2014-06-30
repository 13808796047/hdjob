<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-8-14 下午12:16:04
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
class Model extends HDPHP {

    protected $table_full; //全表名
    protected $table; //不带前缀表名
    public $tableName; //表名  通过table方法得到的
    public $field; //表字段集
    public $join = array(); //多表关联
    public $db; //数据库连接驱动
    public $data = array(); //SQL 操作参数
    public $validate = null; //验证规则
    public $error; //验证不通过的错误信息
    public $map = array(); //字段映射

    /**
     * 构造函数
     * @param type $tableName
     * @param type $full    如果为真将为全表名
     */

    public function __construct($table = null, $full = null, $driver = null) {
        $this->tableName = $this->get_table($table, $full); //初始化默认表
        $this->db = dbFactory::factory($driver, $this->tableName); //获得数据库引擎 
    }

    /**
     * 魔术方法  设置模型属性如表名字段名等
     * @param type $name
     * @param type $value 
     */
    public function __set($var, $value) {
        $var = strtolower($var);
        if (in_array($var, array('view', 'join'))) {
            return;
        }
        $property = array_keys($this->db->opt);
        if (in_array($var, $property)) {
            $this->$var($value);
        } else {
            $this->data[$var] = $value;
        }
    }

    /**
     * 字段映射 
     */
    private function field_map() {
        if (empty($this->map)) {
            return;
        }
        foreach ($this->map as $k => $v) {
            if (isset($_POST[$k])) {
                $_POST[$v] = $_POST[$k];
                unset($_POST[$k]);
            }
        }
    }

    /**
     * 调用驱动方法
     * @param type $func
     * @param type $args
     * @return type 
     */
    public function __call($func, $args) {
        if (!method_exists($this, $func)) {
            error(L("model__call_error") . $func, false); //模型中不存在方法
        }
    }

    /**
     * 参数经过以下几个步骤，由高到低
     * 1检测有无参数，有参数采用
     * 2如果参数是否为对象，如果是则检测有无data属性，有data属性则用data属性做为参数
     * 3检测对象有无data属性如果有将data属性设为参数
     * @param type $args    void
     * @return array
     */
    protected function get_args($args) {
        if (isset($args[0]) && is_object($args[0]) && property_exists($args[0], 'data')) {//对模型对象形式传参 $db->data="id>2"
            $obj = $args[0];
            $args = array($obj->data);
            $this->data = array();
        }
        if (empty($args) && !empty($this->data)) {//没有传递参数，
            $args = array($this->data);
            $this->data = array();
        }
        return $args;
    }

    //设置操作表
    protected function get_table($table = null, $full = false, $init = 0) {
        if (is_null($table)) {
            $table = null;
        } elseif (!empty($this->table_full)) {
            $table = $this->table_full;
        } elseif (!empty($this->table)) {
            $table = C("DB_PREFIX") . $this->table;
        } elseif (!empty($table)) {
            if ($full == true) {
                $table = $table;
            } else {
                $table = C("DB_PREFIX") . $table;
            }
        } else {
            $table = C("DB_PREFIX") . CONTROL;
        }
        return $table;
    }

    /**
     * 临时更改操作表
     * @param type $table   表名
     * @param type $full    是否带表前缀
     * @return Model 
     */
    public function table($table, $full = false) {
        if (!$full) {
            $table = C("DB_PREFIX") . $table;
        }
        $this->db->table($table);
        return $this;
    }

    /**
     * 设置字段
     * 示例：$db->field("username,age")->limit(6)->all();
     */
    public function field() {
        $opt = func_get_args();
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * SQL中的WHERE规则
     * 示例：$db->where("username like '%向军%')->count();
     */
    public function where() {
        $opt = $this->get_args(func_get_args());
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * 执行查询操作结果不缓存
     * 示例：$db->cache(30)->all();
     */
    public function cache() {
        $opt = func_get_args();
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    //SQL中的LIKE规则
    public function like() {
        $opt = $this->get_args(func_get_args());
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * GROUP语句定义
     * 示例：$db->having("id>2","age<20")->group("age")->all();
     */
    public function group() {
        $opt = $this->get_args(func_get_args());
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * HAVING语句定义
     * 示例：$db->having("id>2","age<20")->group("age")->all();
     */
    public function having() {
        $opt = $this->get_args(func_get_args());
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * ORDER 语句定义
     * 示例：$db->order("id desc")->all();
     */
    public function order() {
        $opt = $this->get_args(func_get_args());
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * LIMIT 语句定义
     * 示例：$db->limit(10)->all("sex=1");
     */
    public function limit() {
        $opt = $this->get_args(func_get_args());
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * IN 语句定义
     * 示例：$db->in(1,2,3)->all();
     */
    public function in() {
        $opt = $this->get_args(func_get_args());
        if (empty($opt))
            return $this;
        call_user_func(array($this->db, __FUNCTION__), $opt);
        return $this;
    }

    /**
     * 删除记录
     * 示例：$db->del("uid=1");
     */
    public function del() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this, 'delete'), $opt);
    }

    /**
     * 慎用  会删除表中所有数据
     * $db->delall();
     */
    public function delall() {
        $opt = $this->get_args(func_get_args());
        $this->db->where('1=1');
        return call_user_func_array(array($this, 'delete'), $opt);
    }

    /**
     * 删除记录
     * 示例：$db->delete("uid=1");
     */
    public function delete() {
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 执行一个SQL语句  有返回值 
     * 示例：$db->query("select title,click,addtime from hd_news where uid=18");
     */
    public function query() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 执行一个SQL语句  没有有返回值 
     * 示例：$db->exe("delete from hd_news where id=16");
     */
    public function exe() {
        $opt = func_get_args();
        return call_user_func_array(array($this->db, 'exe'), $opt);
    }

    /**
     * 查找满足条件的一条记录
     * 示例：$db->find("id=188")
     */
    public function find() {
        $opt = func_get_args();
        $this->db->opt['limit'] = " LIMIT 1";
        $result = call_user_func_array(array($this, 'select'), $opt);
        return $result ? current($result) : $result;
    }

    /**
     * 查找满足条件的一条记录
     * 示例：$db->one("id=188")
     */
    public function one() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this, 'find'), $opt);
    }

    /**
     * 查找满足条件的所有记录
     * 示例：$db->findall("sex=1")
     */
    public function findall() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this, 'select'), $opt);
    }

    /**
     * 查找满足条件的所有记录
     * 示例：$db->all("age>20")
     */
    public function all() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this, 'select'), $opt);
    }

    //查找记录
    public function select() {
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    //添加数据
    public function save() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this, 'update'), $opt);
    }

//添加数据
    public function update() {
        if ($this->validate() === false) {//自动验证
            return false;
        }
        $opt = $this->get_args(func_get_args());
        if (empty($opt)) {
            if (!empty($_POST)) {
                $opt = array($_POST);
            } else {
                error(L("model_update_error"), false);
            }
        }
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

//插入数据
    public function insert() {
        $this->field_map(); //字段映射
        if ($this->validate() === false) {//自动验证
            return false;
        }
        $opt = $this->get_args(func_get_args());
        if (empty($opt)) {
            if (!empty($_POST)) {
                $opt = array($_POST);
            } else {
                error(L("model_insert_error"), false); //悲剧了。。。INSERT参数不能为空！
            }
        }
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

//插入数据
    public function replace() {
        if ($this->validate() === false) {//自动验证
            return false;
        }
        $opt = $this->get_args(func_get_args());
        if (empty($opt)) {
            if (!empty($_POST)) {
                $opt = array($_POST);
            } else {
                error(L("model_replace_error"), false); //悲剧了。。。INSERT参数不能为空！
            }
        }
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

//插入数据
    public function add() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this, 'insert'), $opt);
    }

    /**
     * 检索最大值
     * 参数可以传入SQL条件
     * 示例：$db->max("sex=1") 
     */
    public function max() {
        if (method_exists($this, "get_join")) {
            $this->get_join();
            $this->join_model = true;
        }
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 检索最小值 
     * 参数可以传入SQL条件
     * 示例：$db->min("sex='girl'")
     */
    public function min() {
        if (method_exists($this, "get_join")) {
            $this->get_join();
            $this->join_model = true;
        }
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 求平均址 
     * 参数可以传入SQL条件
     * 示例：$db->avg("id>100")
     */
    public function avg() {
        if (method_exists($this, "get_join")) {
            $this->get_join();
            $this->join_model = true;
        }
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 统计记录数 
     * 参数可以传入SQL条件
     * 示例：$db->count("age>20")
     */
    public function count() {
        if (method_exists($this, "get_join")) {
            $this->get_join();
            $this->join_model = true;
        }
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 优化表解决表碎片问题 
     */
    public function optimize() {
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 字段值增加
     * 示例：$db->dec("price","id=20",188)
     * 将id为20的记录的price字段值增加188
     * @return type 
     */
    public function inc() {
        $opt = $this->get_args(func_get_args());
        if (count($opt) != 3) {
            error("inc方法参数不正确，示例：\$db->dec('price','id=20',188)", false);
        }
        $this->where(array($opt[1])); //条件
        $sql = "UPDATE " . $this->db->opt['table'] . " SET " . $opt[0] . '=' . $opt[0] . '+' . $opt[2] . $this->db->opt['where'];
        return $this->exe($sql);
    }

    public function field_filter() {
        $opt = $this->get_args(func_get_args());
        $opt = $opt ? $opt : array($_GET);
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    /**
     * 减少字段值
     * 示例：$db->dec("total","id=4",8)
     * 将id为4的记录的total字段值减8
     * @return type 
     */
    public function dec() {
        $opt = $this->get_args(func_get_args());
        if (count($opt) != 3) {
            error("DEC方法参数不正确，示例：\$db->dec('total','id=4',8)", false);
        }
        $this->where(array($opt[1])); //条件
        $sql = "UPDATE " . $this->db->opt['table'] . " SET " . $opt[0] . '=' . $opt[0] . '-' . $opt[2] . $this->db->opt['where'];
        return $this->exe($sql);
    }

    /**
     * 获得受影响的记录数
     */
    public function get_affected_rows() {
        return $this->db->get_affected_rows();
    }

    /**
     * 获得最后插入的ID
     */
    public function get_insert_id() {
        return $this->db->get_insert_id();
    }

    /**
     * 获得最后一条SQL
     */
    public function get_last_sql() {
        return $this->db->get_last_sql();
    }

    /**
     * 获得所有SQL
     */
    public function get_all_sql() {
        return $this->db->get_all_sql();
    }

//获得MYSQL版本
    public function get_version() {
        return $this->db->get_version();
    }

    //获得数据库或表大小
    public function get_size() {
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    //自动提交模式true开启false关闭
    public function autocommit() {
        $opt = $this->get_args(func_get_args());
        return call_user_func(array($this->db, __FUNCTION__), $opt);
    }

    //提供一个事务
    public function commit() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this->db, __FUNCTION__), $opt);
    }

    //回滚事务
    public function rollback() {
        $opt = $this->get_args(func_get_args());
        return call_user_func_array(array($this->db, __FUNCTION__), $opt);
    }

    //字段验证
    public function validate($data = null) {
        if (!is_null($data)) {
            $_POST = array_merge($_POST, $data);
        }
        if (is_null($this->validate)) {
            return true;
        }

        if (!is_array($this->validate)) {
            error(L("model_validate_error"), false); //验证规则定义错误，是不是打错了，看后盾帮助手册学习一下吧
        }
        foreach ($this->validate as $v) {
            $type = isset($v[3]) ? $v[3] : 1; //1 为默认验证方式    有POST这个变量就验证 
            $name = $v[0]; //POST变量值
            $msg = $v[2]; //错误时的提示内容
            switch ($type) {
                case 1://有post这个变量就验证
                    if (!isset($_POST[$name])) {
                        continue 2;
                    }
                    break;
                case 2: // 必须验证
                    if (!isset($_POST[$name])) {
                        $this->error = $msg;
                        return false;
                    }
                    break;
                case 3://不为空验证                    
                    if (!isset($_POST[$name]) || empty($_POST[$name])) {
                        continue 2;
                    }
                    break;
            }
            $method = explode(":", $v[1]);
            $func = $method[0];
            $args = isset($method[1]) ? str_replace(" ", '', $method[1]) : '';
            if (method_exists($this, $func)) {
                $res = call_user_func_array(array($this, $func), array($name, $_POST[$name], $msg, $args));
                if ($res === true) {
                    continue;
                }
                $this->error = $res;
                return false;
            } elseif (function_exists($func)) {
                $res = $func($name, $_POST[$name], $msg, $args);
                if ($res === true) {
                    continue;
                }
                $this->error = $res;
                return false;
            } else {
                $validate = new validate();
                $func = '_' . $func;
                if (method_exists($validate, $func)) {
                    $res = call_user_func_array(array($validate, $func), array($name, $_POST[$name], $msg, $args));
                    if ($res === true) {
                        continue;
                    }
                    $this->error = $res;
                    return false;
                }
            }
        }
        return true;
    }

}

?>
