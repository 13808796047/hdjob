<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-8-24
 * Describe   : 查找、替换、生成一些数据
 */

class data {

    private $data_model;
    private $db_prefix; //数据库前缀
    public $fields; //模型的字段结构数组
    private $data; //原始数据

    /**
     * 
     * @param type $model 模型名
     */

    function __construct($model = 'recruit') {
        $this->data_model = K('backend/data');
        $this->db_prefix = rtrim(C('DB_PREFIX'), '_') . '_';
        $this->fields = include PATH_ROOT . '/caches/model/field/m_' . $model . '.php';
    }

    function convert($data) {
        $this->data = $data;
        foreach ($this->fields as $key => $value) {
            if (!isset($data[$key])) {
                continue;
            }
            $method = 'convert' . ucfirst($value['field_type']);
            if (method_exists($this, $method)) {
                $this->$method($key, $data[$value['field_name']]);
            }
        }
        return $this->data;
    }

    /**
     * 转换选项数据
     * @param type $field
     * @param type $value 
     */
    function convertSwitch($field, $value) {
        if (strpos($value, '#') !== FALSE) {
            $value_arr = explode('#', $value);
            $new_v = '';
            foreach ($value_arr as $v) {
                if(isset($this->fields[$field]['data'][$v])){
                    $new_v.= $this->fields[$field]['data'][$v] . '、';
                }
            }
            $this->data[$field] = rtrim($new_v, '、');
        } else {
            if(isset($this->fields[$field]['data'][$value])){
                $this->data[$field] = $this->fields[$field]['data'][$value];
            }
        }
    }

    /**
     * 取得联动分类或城市的缓存数据值
     * @param string $type city or linkage
     * @return 换成数据数组
     */
    private function _getLinkageCache($type='linkage')
    {
        $field=array('laid','title');
        if($type=='city'){
            $field=array('id','name');
        }
        $cache=array();
        $path=PATH_ROOT.'/caches/linkage';
        $file=$path.'/'.$type.'.php';
        if(file_exists($file)){
            $cache=include $file;
        }else{
            dir::create($path);
            $db = M($type);
            $linkages = $db->field(implode(',', $field))->findall();
            foreach ($linkages as $value) {
                $cache[$value[$field[0]]]=$value[$field[1]];
            }
            file_put_contents($file, '<?php if(!defined("PATH_HD")){exit;}return '.var_export($cache,TRUE).';');
        }
        return $cache;
    }

    /**
     * 转换联动数据
     * @param type $field
     * @param type $value 
     */
    function convertLinkage($field, $value) {
        $attached = json_decode($this->fields[$field]['attached'], TRUE);
        if (strpos($value, '#') !== FALSE) {
            $id = explode('#', $value);
        } else {
            $id = array($value);
        }
        //查找附属字段的
        foreach ($attached as $value) {
            if (strpos($this->data[$value], '#') !== FALSE) {
               $id = array_merge($id,explode('#', $this->data[$value]));
            } else {
                $id[] = $this->data[$value];
            }
        }
        $type='linkage';
        if($this->fields[$field]['lcgid']=='city'){//是城市数据,从城市表中取得数据转换
            $type='city';
        }
        $linkage=$this->_getLinkageCache($type);

        $cn = '';
        $delimiter = ' - ';
        foreach ($id as $value) {
            if(isset($linkage[$value])){
                $cn.=$linkage[$value] . $delimiter;
            }
        }
        $this->data[$field] = rtrim($cn, $delimiter);
    }

    //将所有的分类数据写入JS文件，形如：var city={},var linkage1={},var linkage2={}
    function writeCatesToJs() {
        C('DEBUG', 0);
        $db=M('linkage_category');
        $lcgid=$db->field('lcgid')->findall();
        $city=$db->table('city')->field('id,pid,name')->findall();
        $str='var city='.json_encode_cn(formatParentData2($city,array('id','pid','name'))).',';//城市的数据
        foreach ($lcgid as $value) {
            $condition = array(
                'lcgid' => $value['lcgid']
            );
            $result = $this->data_model->getCateLinkage($condition,'laid,title,pid');
           $str.='linkage_'.$value['lcgid'].' = '.json_encode_cn(formatParentData($result)).',';
        }
        $str=rtrim($str,',').';';
        $file_name = PATH_ROOT . '/caches/js/linkage_data.js';
        file_put_contents($file_name, $str);
    }

}

