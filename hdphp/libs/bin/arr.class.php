<?php
/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8 
 * Version                $Id: {name}      2012-7-25 15:28:07
 * @author                向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwangxj@gmail.com
 */
final class arr {
    /**
     * 递归操作数组创建树状等级数组(可用于递归栏目操作)
     * @param array $data           操作的数组
     * @param string $fieldPri      唯一键名，如果是表则是表的主键
     * @param string $fieldPid      父ID键名
     * @param number $pid           一级PID的值 
     * @param number $sid           子ID用于获得指定指ID的所有父ID栏目
     * @param number $type          操作方式1=>返回多维数组,2=>返回一维数组,3=>得到指定子ID(参数$sid)的所有父栏目
     * @return arary
     */
    static public function channel($data, $fieldPri = 'id', $fieldPid = 'pid', $pid = 0, $sid = '', $type = 1, $html = "&nbsp;", $level = 1) {
        if (!$data) {
            return array();
        }
        switch ($type) {
            case 1:
                $arr = array();
                foreach ($data as $v) {
                    if ($v[$fieldPid] == $pid) {
                        $arr[$v[$fieldPri]] = $v;
                        $arr[$v[$fieldPri]]["data"] = self::channel($data, $fieldPri, $fieldPid, $v[$fieldPri], $sid, $type);
                    }
                }
                return $arr;
            case 2:
                static $arr = array();
                static $id = -1;
                $id++;
                foreach ($data as $v) {
                    if ($v[$fieldPid] == $pid) {
                        $arr[$id] = $v;
                        $arr[$id]['level'] = $level;
                        $arr[$id]['html'] = str_repeat($html, $level);
                        self::channel($data, $fieldPri, $fieldPid, $v[$fieldPri], $sid, $type, $html, $level + 1);
                    }
                }
                return $arr;
            case 3:
                static $arr = array();
                foreach ($data as $v) {
                    if ($v[$fieldPri] == $sid) {
                        $arr[$v[$fieldPri]] = $v;
                        self::channel($data, $fieldPri, $fieldPid, $pid, $v[$fieldPid], $type, $html, $level + 1);
                    }
                }
                return array_reverse($arr);
        }
    }

}

?>