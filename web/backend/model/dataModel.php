<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-2
 * Describe   : 
 */

class dataModel extends Model {

    private $data_model;
    private $data_cate_model;
    private $model_r_cate;
    private $model_category;
    private $field_type_model;
    public $model_field;
    private $model_v_field;
    private $linkage_model;
    private $linkage_cate_model;
    private $cache_dir;
    private $not_length;

    function __construct() {
        $this->not_length=array('INT','TINYINT','MEDIUMINT','MEDIUMTEXT','TEXT');
        $this->cache_dir = rtrim(C('CACHE_DIR'), '/') . '/';
        $this->data_model = M('model');
        $this->data_cate_model = M('model_category');
        $this->field_type_model = M('field_type');
        $this->model_field = M('model_field');
        $this->model_category = M('model_category');
        $this->linkage_model = M('linkage');
        $this->linkage_cate_model = M('linkage_category');
        $this->model_r_cate = R('model');
        $this->model_r_cate->join = array(
            "model_category" => array(//与XX表关联
                'type' => "has_many", //关联方式
                'foreign_key' => 'mcid', //关联字段
                'parent_key' => 'mcid', //关联字段
            ),
        );
        $this->model_v_field = V('model_field');
        $this->model_v_field->view = array(
            'field_type' => array(
                'join_type' => "INNER",
                "field" => 'title|ft_title',
                "on" => "model_field.field_type=field_type.name",
            ),
            'model' => array(
                'join_type' => "INNER",
                "field" => 'name|model_name,title|model_title',
                "on" => "model_field.dmid=model.dmid",
            ),
            'linkage_category' => array(
                'join_type' => "LEFT",
                "field" => 'cate_title|lacate_title',
                "on" => "model_field.lcgid=linkage_category.lcgid",
            )
        );
    }

    /**
     * 添加模型分类
     * @param type $cate_data 分类的数据
     */
    function addCate($cate_data) {
        $mcid = $this->data_model->insert($cate_data);
        if ($mcid) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 查找模型是否存在
     * @param type $model_name
     * @return boolean 
     */
    function modelExist($model_name) {
        $result = $this->data_model->where(array('name' => $model_name))->find();
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 添加模型
     * @param type $model_data 模型数据
     * @return boolean 
     */
    function addModel($model_data) {
        $insert_id = $this->data_model->insert($model_data);
        if ($insert_id) {
            $this->createModelTable($model_data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 修改模型数据
     * @param type $condtion 修改条件
     * @param type $data 更新数据
     * @return type bool
     */
    function editModel($condtion, $data) {
        $result = $this->data_model->where($condtion)->update($data);
        return $result >= 0;
    }

    function addModelCate($cate_data) {
        $insert_id = $this->model_category->insert($cate_data);
        if ($insert_id) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 创建模型表 
     */
    function createModelTable($table_data) {
        $create_sql = 'CREATE TABLE `' . trim(C('DB_PREFIX'), '_') . "_{$table_data['name']}`({$table_data['pri_name']} INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT)ENGINE=MyISAM";
        $this->data_model->exe($create_sql);
    }

    /**
     * 模型列表
     * @return type 
     */
    function modelList() {
        $this->model_r_cate->join['model_category']['field'] = 'cate_title';
        $model = $this->model_r_cate->order('dmid')->findall();
        return $model;
    }

    function delModel($dmid) {
        if ($this->data_model->del($dmid)) {
            return TRUE;
        }
    }

    /**
     * 模型分类列表
     */
    function cateList() {
        $cate_list = $this->data_cate_model->findall();
        return $cate_list;
    }

    function linkageCateList() {
        $linkage_cate = $this->linkage_cate_model->findall();
        return $linkage_cate;
    }

    /**
     * 联动数据排序
     * @param type $data 排序POST数据
     */
    function sortLinkage($data) {
        foreach ($data as $key => $value) {
            $this->linkage_model->where(array('laid' => $key))->update(array('sort' => $value));
        }
    }

    /**
     * 字段类型 
     */
    function getFieldType() {
        $field_type = $this->field_type_model->findall();
        return $field_type;
    }

    /**
     * 过滤验证规则
     * @param type $rule_array 
     */
    function buildValidateRule($rule_array) {
        foreach ($rule_array as $rule_item_k => $rule_item) {
            if (trim($rule_item, ' ') == '') {
                unset($rule_array[$rule_item_k]);
            }
        }
        return $rule_array;
    }

    /**
     * 添加字段
     * @param type $field_data 
     */
    function addField($field_data) {
        if (isset($field_data['attached'])) {
            $field_data['attached'] = array_filter($field_data['attached'], 'trim'); //去掉附属字段为空的数组索引
        }
        //更新hd_field_struct中的数据结构
        $field_data['rule'] = $this->buildValidateRule($field_data['rule']);
        //创建表字段
        $this->createTableField($field_data);
        //数据插入hd_model_field表
        $field_data['rule'] = json_encode($field_data['rule']);
        if (isset($field_data['attached'])) {
            $field_data['attached'] = json_encode($field_data['attached']);
        }
        if (isset($field_data['setting'])) {
            $field_data['setting'] = json_encode($field_data['setting']);
        }
        $field_data['field_null'] = isset($field_data['rule']['required']) ? 1 : 0;
        $insert_id = $this->model_field->insert($field_data);

        //删除缓存的hd_model_field表中的数据
        $this->setModelFieldCache($field_data['dmid'], TRUE);

        return $insert_id;
    }

    /**
     * 更新模型表字段中的数据
     * @param mixed $condition
     * @param array $data 
     */
    function updateModelField($condition, $data) {
        return $this->model_field->where($condition)->update($data);
    }

    /**
     * 删除附属字段SQL
     * @param array $field 字段列表
     */
    function delAttachedField($table, $field) {
        $db = M($table, true);
        if (!empty($field)) {
            foreach ($field as $value) {
                if (in_array($value, $db->db->opt['fieldArr'])) {//确保表中存在该字段
                    $sql = "ALTER TABLE " . $table . " DROP `{$value}`";
                    $this->data_model->exe($sql);
                }
            }
        }
    }

    /**
     * 修改模型字段和数据库表字段
     * @param array $data POST的数据
     * @return bool 
     */
    function editField($data) {
        $sql_syntax = $this->buildSQLSyntax($data);
        $table = rtrim(C('DB_PREFIX'), '_') . "_{$data['table_name']}";
        //组合SQL语句,对表结构修改
        $sql = "ALTER TABLE " . $table . " CHANGE `{$data['old_field_name']}` `{$data['field_name']}` {$sql_syntax['type']}{$sql_syntax['length']} {$sql_syntax['unsigned']} {$sql_syntax['null']}";
        $this->data_model->exe($sql);
        if (isset($data['attached'])) {
            $data['attached'] = array_filter($data['attached'], 'trim'); //去掉没有附属字段的数组索引
            //处理联动类型新加了或删除了附属字段
            $old_field = $this->model_field->field('attached')->where(array('mfid' => $data['mfid']))->find();
            $old_attached = json_decode($old_field['attached'], true);
            $del_field = array_diff($old_attached, $data['attached']);
            $add_field = array_diff($data['attached'], $old_attached);
            //删除附属字段
            $this->delAttachedField($table, $del_field);
            //创建附属字段
            if (!empty($add_field)) {
                foreach ($add_field as $value) {
                    $sql = "ALTER TABLE " . $table . " ADD `{$value}` INT(10) UNSIGNED, ADD INDEX(`$value`)";
                    $this->data_model->exe($sql);
                }
            }
        }
        //如果索引发生了变化，修改表索引
        if ($data['join_index'] != $data['old_join_index']) {
            if ($data['join_index']) {
                $sql = "ALTER TABLE " . $table . " ADD INDEX(`{$data['field_name']}`)";
            } else {
                $sql = "ALTER TABLE " . $table . " DROP INDEX `{$data['field_name']}`";
            }
            $this->data_model->exe($sql);
        }
        //更新hd_field_struct中模型字段的结构
        $data['rule'] = $this->buildValidateRule($data['rule']);
        

        //更新hd_model_field表中的信息
        $data['field_null'] = isset($data['rule']['required']) ? 1 : 0;
        $data['rule'] = json_encode($this->buildValidateRule($data['rule']));
        if (isset($data['attached'])) {
            $data['attached'] = json_encode($data['attached']);
        }
        if (isset($data['setting'])) {
            $data['setting'] = json_encode($data['setting']);
        }
        //更新缓存的hd_model_field表中的数据
        $this->updateModelField(array('mfid' => $data['mfid']), $data) >= 0;
        $this->setModelFieldCache($data['dmid'], TRUE);
        return true;
    }

    /**
     * 更新hd_field_strcut中的信息
     * @param type $condition 条件
     * @param type $data 数据
     * @return type bool
     */
    function updateFieldStruct($condition, $data) {
        return $this->field_struct->where($condition)->update($data) >= 0;
    }

    /**
     * 删除字段
     * @param type $cond 
     */
    function delField($cond) {
        $this->delTableField($cond);
        $result = $this->model_field->del($cond['mfid']);
        if ($result) {
            //重新缓存模型字段
            $this->setModelFieldCache($cond['dmid'], TRUE);

            return TRUE;
        }
        return FALSE;
    }

    /**
     * 删除表字段(执行ALTER TABLE ** DROP **) 
     */
    function delTableField($cond) {
        //$db->db->opt['fieldArr']
        $db = M($cond['table']);
        $fieldArr = $db->db->opt['fieldArr'];
        if (in_array($cond['field_name'], $fieldArr)) {
            $sql = "ALTER TABLE " . trim(C('DB_PREFIX'), '_') . "_{$cond['table']} DROP {$cond['field_name']}";
            $db->exe($sql);
        }
        //如果是联动类型删除附属字段
        $data = $this->model_field->field('attached,field_type')->where(array('mfid' => $cond['mfid']))->find();
        if ($data['field_type'] == 'linkage') {
            $attached = json_decode($data['attached'], true);
            for ($i = 0; $i < count($attached); $i++) {
                $sql = "ALTER TABLE " . trim(C('DB_PREFIX'), '_') . "_{$cond['table']} DROP {$attached[$i]}";
                $db->exe($sql);
            }
        }
    }

    /**
     * 字段排序
     */
    function sortField($data, $field, $field_w) {
        foreach ($data as $key => $value) {
            $this->model_field->where(array($field_w => $key))->update(array($field => $value));
        }
    }

    /**
     * 字段列表 
     */
    function fieldList($model_cate_id) {
        $field = $this->model_v_field->where($model_cate_id)->join('field_type')->order('sort')->findall();
        return $field;
    }

    /**
     * 查找字段是否存在
     * @param type $field_name 查找的字段名
     */
    function fieldExist($field_name) {
        $result = $this->data_model->where(array('name' => $model_name))->find();
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 创建数据表字段SQL 
     */
    function createTableField($data) {
        $syntax = $this->buildSQLSyntax($data);

        $table = trim(C('DB_PREFIX'), '_') . "_{$data['table_name']}";
//组合SQL语句
        $sql = 'ALTER TABLE ' . $table . " ADD `{$data['field_name']}` {$syntax['type']}{$syntax['length']} {$syntax['unsigned']} {$syntax['null']} {$syntax['index']}";
        //联动模型处理
        if ($data['field_type'] == 'linkage') {
            //创建附属字段
            if (!empty($data['attached'])) {
                $attached_nums = count($data['attached']);
                for ($i = 0; $i < $attached_nums; $i++) {
                    $sql_attached = "ALTER TABLE " . $table . " ADD `{$data['attached'][$i]}` INT(10) UNSIGNED, ADD INDEX(`{$data['attached'][$i]}`)";
                    $this->data_model->exe($sql_attached);
                }
            }
        }
        $this->data_model->exe($sql);
    }

    /**
     * 创建、修改字段时的一些SQL条件语法
     * @param type $data 
     */
    function buildSQLSyntax($data) {
        $syntax = array();
//取得处理后的字段类型
        $setting = isset($data['setting']) ? $data['setting'] : '';
        $syntax['type'] = $this->exeFieldType($data['field_type'], $setting);
        $syntax['unsigned'] = '';
        if (!empty($data['setting']['unsigned']) || ($data['field_type'] == 'linkage' && !isset($data['setting']['checkbox'])) || $data['field_type'] == 'input_int') {
            $syntax['unsigned'] = 'UNSIGNED';
        }
//处理字段长度
        $syntax['length'] = empty($data['length']) ? '' : '(' . $data['length'] . ')';
//处理字段是否为空
        $syntax['null'] = isset($data['rule']['required']) ? 'NOT NULL' : 'NULL';

//处理字段索引
        $syntax['index'] = '';
        if ($data['join_index']) {
            $syntax['index'] = ",ADD INDEX(`{$data['field_name']}`)";
        }
        return $syntax;
    }

    /**
     * 获取某个字段的信息
     * @param type $condition 
     */
    function getFieldInfo($condition = array()) {
        $field_info = $this->model_field->where($condition)->find();
        return $field_info;
    }

    /**
     * 取得联动数据,一级一级显示
     */
    function getLinkageData($condition) {
        $data = array();
        $nums = $this->linkage_model->where($condition)->count();
        $page = new page($nums, 10, 6); //传入总记录数，用于计算出分页
        $data['page'] = $page->show(); //显示分页页码
        $cache_name = 'linkage_' . $condition['lcgid'] . '_' . $condition['pid']; //配置缓存名称
        $cache_dir = $this->cache_dir . 'linkage';
        $data['data'] = $this->linkage_model->where($condition)->order('sort')->findall($page->limit());
        return $data;
    }

    /**
     * 全部显示，用于添加数据时
     * @param type $condition 
     */
    function getLinkageDataAll($condition, $field = 'laid,title,pid,path') {
        $cache_name = 'linkage_all_' . $condition['lcgid'];
        if (!cache_exists($cache_name, NULL, $this->cache_dir . 'linkage')) {
            $data = $this->linkage_model->field($field)->where($condition)->order('sort')->findall();
            cache_set($cache_name, $data, NULL, $this->cache_dir . 'linkage');
            return $data;
        } else {
            return cache_get($cache_name, $this->cache_dir . 'linkage');
        }
    }

    /**
     * 获取联动数据
     * @param type $condition 
     */
    function getCateLinkage($condition=array(), $field = 'laid,title,pid,sort') {
        $cache_name = 'LinkageCateData_' . $condition['lcgid'];
        if (!cache_exists($cache_name, NULL, $this->cache_dir . 'linkage')) {
            $data = $this->linkage_model->field($field)->where($condition)->order('lcgid,sort')->findall();
            cache_set($cache_name, $data, NULL, $this->cache_dir . 'linkage');
            return $data;
        } else {
            return cache_get($cache_name, $this->cache_dir . 'linkage');
        }
    }

    /**
     * 获取所有的联动字段
     * @param type $lcgid 联动类别id，默认为空，即查找所有
     */
    function getLinkageField($condition = array(), $field = 'mfid,title') {
        return $this->model_field->field($field)->where($condition)->findall();
    }

    /**
     * 获取某个模型不重复的联动数据ＩＤ和风格
     * @param type $model
     * @param type $field 
     */
    function getModelLcgidUnique($model, $field, $group) {
        return $this->model_field->field($field)->where('lcgid != "0" AND dmid="' . $model . '"')->group($group)->findall();
    }

    /**
     * 删除联动数据
     * @param type $condition 联动数据ID号
     */
    function delLinkageData($condition) {
        $result = $this->linkage_model->del($condition);
        return $result;
    }

    /**
     * 设置字段的缓存,缓存目录caches/model/field/，缓存数据为hd_model_field的数据
     * @param type $id
     * @return type 
     */
    function setModelFieldCache($id, $del = FALSE) {
        $table = $this->data_model->find($id);
        $table = $table['name'];
        $cache_path = PATH_ROOT . '/caches/model/field/m_' . $table . '.php';
        if ($del) {
            unlink($cache_path);
        }
        if (file_exists($cache_path)) {
            return include $cache_path;
        }
        $data = $this->model_field->where(array('dmid' => $id))->order('sort asc')->findall();
        $new_data = array();
        //将switch类型的表单选项处理为数组
        foreach ($data as $value) {
            if ($value['field_type'] == 'switch') {
                $value_data = explode("\n", $value['data']);
                $switch = array();
                foreach ($value_data as $data_array) {
                    $temp = explode('=', $data_array);
                    $switch[trim($temp[0])] = trim($temp[1]);
                }
                $value['data'] = $switch;
            }
            $value['setting'] = json_decode($value['setting'], true);
            $new_data[$value['field_name']] = $value;
        }
        dir_create(dirname($cache_path));
        file_put_contents($cache_path, '<?php if(!defined("PATH_HD")){exit("禁止访问");} return ' . var_export($new_data, TRUE) . ';');
        return include $cache_path;
    }

    /**
     * 根据ID获取联动的名称
     * @param type $id 
     */
    function getLinkageName($id) {
        $result = $this->linkage_model->field('laid', 'title')->where(array('laid' => $id))->find();
        return $result;
    }

    /**
     * 删除联动分类(同时会删除所有联动数据) 
     */
    function delLinkageCate($condition) {
        $this->linkage_cate_model->del($condition);
        $this->delLinkageData(array('lcgid' => $condition));
    }

    /**
     * 添加联动数据 
     */
    function addLinkageData($linkage_data) {
        $id = $this->linkage_model->insert($linkage_data);
        if ($id) {
            return TRUE;
        }
    }

    function addLinkageCate($cate_data) {
        $id = $this->linkage_cate_model->insert($cate_data);
        if ($id) {
            return TRUE;
        }
    }

    /**
     * 处理新增、修改字段时的类型 
     */
    function exeFieldType($field_type, $setting) {
        $type = '';
        if ($field_type == 'switch') {
            $type = $setting['fieldtype'];
            return $type;
        }
        if ($field_type == 'linkage' && isset($setting['checkbox'])) {
            $type = 'VARCHAR';
            return $type;
        }
        switch ($field_type) {
            case 'input_int':$type = 'INT';
                break;
            case 'input_varchar':$type = 'VARCHAR';
                break;
            case 'input_char':$type = 'CHAR';
                break;
            case 'textarea':$type = 'VARCHAR';
                break;
            case 'editor':$type = 'mediumtext';
                break;
            case 'int_type':$type = 'INT';
                break;
            case 'datetime':$type = 'DATETIME';
                break;
            case 'decimal':$type = 'DECIMAL';
                break;
            case 'float':$type = 'FLOAT';
                break;
            case 'double':$type = 'DOUBLE';
                break;
            case 'linkage':$type = 'INT';
                break;
            case 'switch_tinyint':$type = 'TINYINT';
                break;
            default:$type = 'VARCHAR';
                break;
        }
        return $type;
    }

}

