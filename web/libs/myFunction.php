<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-9
 * Describe   : 自定义扩展函数
 */

function alert($msg) {
    echo '<script>alert("' . $msg . '");window.history.back();</script>';
}
//创建一个32位随机令牌码
function token()
{
    return md5(str_shuffle(chr(mt_rand(32, 126)) . uniqid() . microtime(TRUE)));
}
/**
 * 删除编译模板，格式:应用/控制器/方法
 * @param type $path 
 */
function delCompileTpl($path) {
    $path_array = explode('/', trim($path, '/'));
    $path_nums = count($path_array);
    switch ($path_nums) {
        case 1:$temp = APP_GROUP . '_G/' . $path_array[0];
            break;
        case 3:$temp = APP_GROUP . '_G/' . $path_array[0] . '_A/' . $path_array[1] . '_C/' . $path_array[2];
            break;

        default:
            break;
    }
    $path = PATH_TEMP . '/Applications/' . $temp;
    dir::del($path);
}

function array_filter_trim($value) {
    if (trim($value) == '') {
        return FALSE;
    }
    return TRUE;
}

/*
 * 格式化层级数据
 */

function formatLevelData($data, $pid = 0) {
    $arr = array();
    foreach ($data as $v) {
        if ($v['pid'] == $pid) {
            $arr[$v['laid']] = $v;
            $arr[$v['laid']]["data"] = formatLevelData($data, $v['laid']);
        }
    }
    return $arr;
}

/**
 * 格式化数据为层级化数据
 * @param type $data 需要格式化的
 * @param type $format 数据的原索引格式[主键字段名(例如id),层级字段名(例如pid)]
 * @param type $pid
 * @return type 
 */
function formatLevelData2($data, $format, $pid = 0) {
    $arr = array();
    foreach ($data as $v) {
        if ($v[$format[1]] == $pid) {
            $arr[$v[$format[0]]] = $v;
            $arr[$v[$format[0]]]["son_data"] = formatLevelData2($data, $format, $v[$format[0]]);
        }
    }
    return $arr;
}

/**
 * 将数组格式化为父子级关系（2级）
 * @param type $data 原数据
 * @param type $format 数据的原索引格式[主键字段名(例如id),层级字段名(例如pid)]
 * @return type 格式化后的数据 
 */
function formatParentData($data) {
    $data_num = count($data);
    $new_data = array();
    for ($i = 0; $i < $data_num; $i++) {
        if ($data[$i]['pid'] == 0) {
            $new_data['first'][$data[$i]['laid']] = $data[$i]['title'];
        } else {
            $new_data[$data[$i]['pid']][$data[$i]['laid']] = $data[$i]['title'];
        }
    }
    return $new_data;
}

/**
 * 将数组格式化为父子级关系（2级）
 * @param type $data 原数据
 * @param type $format 数据的原索引格式[主键字段名(例如id),层级字段名(例如pid),主要字段名(例如title、name)]
 * @return type 格式化后的数据 
 */
function formatParentData2($data,$format) {
    $data_num = count($data);
    $new_data = array();
    for ($i = 0; $i < $data_num; $i++) {
        if ($data[$i][$format[1]] == 0) {
            $new_data['first'][$data[$i][$format[0]]] = $data[$i][$format[2]];
        } else {
            $new_data["{$data[$i][$format[1]]}"][$data[$i][$format[0]]] = $data[$i][$format[2]];
        }
    }
    return $new_data;
}

function urlencode_array($var)
{
    if (is_array($var))
    {
        return array_map('urlencode_array', $var);
    }
    else
    {
        return urlencode($var);
    }
}
//不编码中文
function json_encode_cn($arr){
    return urldecode(json_encode(urlencode_array($arr)));
}

/**
 * 组合属性变量 
 */
function buildAttrVar($attr) {
    $str = '';
    $attr = str_replace(' ', '', $attr);
    if (isset($attr)) {
        if (strpos($attr, '$') === FALSE) {
            $str = $attr;
        } else {
            $preg = '/(\$[a-zA-Z_]\w*)(\[(\'|")\w+(\'|")\])*/';
            $str = preg_replace($preg, '<?php echo ${0};?>', $attr);
        }
    }
    return $str;
}

/**
 * 取得除了a c m 等的GET参数
 * @param type $unset 除了acm需要删除的参数
 */
function getCleanUriArg($unset = array()) {
    $get = $_GET;
    unset($get['a']);
    unset($get['c']);
    unset($get['m']);
    if (!empty($unset)) {
        foreach ($unset as $value) {
            unset($get[$value]);
        }
    }
    return $get;
}

/**
 * 取得Email模板
 * @param type $data 数据数组
 * @param type $type 模板类型
 * @return type 
 */
function getEmailTpl($type, $data = array()) {
    if (!isset($data['web_name'])) {
        $data['web_name'] = C('WEB_NAME');
    }
    if (!isset($data['web_host'])) {
        $data['web_host'] = __ROOT__;
    }
    $filename = PATH_ROOT . '/caches/email/' . $type . '.php';
    if (file_exists($filename)) {
        $tpl = include $filename;
    } else {
        $db = M('mail_tpl');
        $tpl = $db->field('subject,content')->where("type='$type'")->find();
    }
    foreach ($data as $key => $value) {
        $tpl['subject'] = str_replace('{' . $key . '}', $value, $tpl['subject']);
        $tpl['content'] = str_replace('{' . $key . '}', $value, $tpl['content']);
    }
    return $tpl;
}
/**
 * 写入用户操作日志
 * @param type $con     操作内容
 * @param type $point   积分变化
 * @param type $uid     用户ID
 */
function writeOptLog($con,$point=0,$uid=NULL){
    if(is_null($uid)){
        $uid=$_SESSION['uid'];
    }
    $data=array(
        'uid'=>$uid,
        'content'=>$con,
        'point'=>$point,
        'created'=>time(),
        'ip'=>ip_get_client(),//操作ip
        'username'=>$_SESSION['username']
    );
    $db=M('opt_log');
    $db->insert($data);
}
/**
 * 获取某个积分的规则
 * @param type $type 积分的调用名称
 */
function getPointRule($type){
    $db=K('backend/point_rule');
    $data=$db->getPointRule($type);
    return $data['operator'];
}
/**
 * 扣除/增加用户积分。减少SESSION和数据库
 * @param int $point 扣除的积分
 * @param int $user 扣除的用户ID
 */
function deductPoint($point,$user=NULL){
    if(is_null($user)){
        $user=$_SESSION['uid'];
    }
    $db=M('user_point');
    $db->inc('point','uid='.$user,$point);
    $_SESSION['point']+=$point;
}
/**
 * 获取节点的所有子节点ID
 * @param int $nid 节点ID
 * @param array $field 主键字段名(如：nid、id)、父字段名(如：pid)。默认：array('nid','pid');
 * @param $data 默认的数据,一般不传
 * @return 子节点数组
 */
function node_son_id($nid,$field=array('nid','pid'),$data=NULL){
    $db=M('node');
    if($nid!==FALSE){
        $n=$db->field($field[0])->where($field[0].'='.$nid)->findall();
    }else{
        $n=$data;
    }
    $nodes=array($field[0]=>array());
    foreach ($n as $value) {
            $v=$db->field($field[0])->where($field[1].'='.$value[$field[0]])->findall();
            if($v){
                $a=node_son_id(FALSE,$field,$v);
                foreach ($a[$field[0]] as $v_a) {
                    $nodes[$field[0]][]=$v_a;
                }
            }
            $nodes[$field[0]][]=$value[$field[0]];
    }
    return $nodes;
}