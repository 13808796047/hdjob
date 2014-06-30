<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: RBAC.php      2012-1-28 下午07:53:14
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
final class rbac extends HDPHP {

    public $error; //错误信息

    /**
     * 验证用户信息
     * 将合法用户权限信息写入$_SESSION
     * 如果$superadmin有值时
     * 如果用户表中存在与$superadmin相同的用户
     * 此用户为超级管理员 在$_SESSION中记录
     * @param string $username        用户名
     * @param string $password        密码
     * @param string $superadmin      超级管理员帐号
     * @param string $writeSESSION    是否写入SESSION 默认写入
     * @return boolean                     写入成功或失败
     */

    public function rbac_user_login($username, $password, $superadmin = '', $fieldUserName, $fieldPassword) {
        $superadmin = empty($superadmin) ? C("RBAC_ADMIN") : $superadmin;
        if (!C("USER_TABLE")) {
            error(L("rbac_rbac_user_login1"));
        }
        $table_user = C('DB_PREFIX') . trim(C("USER_TABLE"), C('DB_PREFIX')); //验证有无前缀得到用户表
        $db = M($table_user, true);
        $user = $db->find("$fieldUserName='$username'");
        if (!$user) {
            $this->error = L("rbac_rbac_user_login2");
            return false;
        }
        if ($user[$fieldPassword] != $password) {
            $this->error = L("rbac_rbac_user_login3");
            return false;
        }
        $db->table(C("ROLE_USER_TABLE"));
        $sql = "SELECT * FROM " . C("DB_PREFIX") . C("ROLE_TABLE") . ' AS r,' .
                C("DB_PREFIX") . C('ROLE_USER_TABLE') . ' AS r_u WHERE r_u.rid = r.rid AND uid = \'' . $user['uid'] . "'";
        $userRoleInfo = $db->query($sql); //获得用户组信息
        $_SESSION['username'] = $user['username'];
        $_SESSION[C("RBAC_AUTH")] = $user['uid'];
        $_SESSION['role'] = $userRoleInfo[0]['title'];
        $_SESSION['rid'] = $userRoleInfo[0]['rid'];
        if (strtoupper($user['username']) == strtoupper($superadmin)) {//是否判断超管理员
            $_SESSION[C("RBAC_ADMIN")] = 1; //登录成功
            return true;
        }
        if (!$_SESSION['rid']) {//
            $this->error = L("rbac_rbac_user_login4");
            return false;
        }
        $this->get_access(); //获得权限写入SESSION
        return true;
    }

    /**
     * 将rbac信息保存到$_SESSION中
     * $_SESSION['RBAC'] 权限数据组
     */
    private function get_access() {
        //清空原RBAC数据
        if (isset($_SESSION['RBAC'])) {
            $_SESSION['RBAC'] = '';
        }
        //数据库权限
        $sql = "SELECT  n.nid AS n_nid, n.name AS n_name, 
      n.title AS n_title,n.level AS n_level, n.pid AS n_pid
FROM " . C("DB_PREFIX") . C("NODE_TABLE") . " AS n
INNER JOIN " . C("DB_PREFIX") . C("ACCESS_TABLE") . " AS a ON n.nid = a.nid
INNER JOIN " . C("DB_PREFIX") . C("ROLE_TABLE") . " AS r ON r.rid = a.rid
WHERE " . $_SESSION['rid'] . " = a.rid
AND n.state =1
AND r.state =1
ORDER BY n.level";
        $db = M("user");
        $info = $db->query($sql);
        if (!$info)
            return false;
        $access = array();
        foreach ($info as $v) {
            if ($v['n_level'] == 1) {
                $access[$v['n_name']] = array();
                $pid = $v['n_nid'];
                foreach ($info as $n) {
                    if ($n['n_pid'] == $pid) {
                        $pid2 = $n['n_nid'];
                        $access[$v['n_name']][$n['n_name']] = array();
                        foreach ($info as $j) {
                            if ($j['n_pid'] == $pid2) {
                                $access[$v['n_name']][$n['n_name']][] = $j["n_name"];
                            }
                        }
                    }
                }
            }
        }
        $_SESSION["RBAC"] = $access; //将权限保存到SESSION
        return true;
    }

    /**
     * 获得所有节点列表 
     * @param int  $role_id    组ID    如果传值将获得当前组的所有节点信息
     * 如果没有传递参数 $role_id  则将获得所有节点及权限信息
     */
    public function get_node_list($role_id = "") {
        $where = !empty($role_id) ? ' WHERE a.rid=' . $role_id : '';
        $nodeTable = C("DB_PREFIX") . C("NODE_TABLE");
        $roleTable = C("DB_PREFIX") . C('ACCESS_TABLE');
        $sql = " select n.nid as n_nid,n.name as n_name,n.title as n_title,n.pid as n_pid,n.level as n_level,
            n.state as n_state,n.sort as n_sort, a.nid as a_nid from $nodeTable as n 
            left join (select * from $roleTable as a  $where) as a on a.nid=n.nid order by n.level,n.sort";
        $db = M();
        $info = $db->query($sql);
        $node = array();
        if (!$info)
            return false;
        foreach ($info as $v) {
            if ($v['n_level'] == 1) {
                $node[$v['n_nid']] = array();
                $node[$v['n_nid']]['name'] = $v['n_name'];
                $node[$v['n_nid']]['title'] = $v['n_title'];
                $node[$v['n_nid']]['level'] = $v['n_level'];
                $node[$v['n_nid']]['nid'] = $v['n_nid'];
                $node[$v['n_nid']]['sort'] = $v['n_sort'];
                $node[$v['n_nid']]['access'] = $v['a_nid'];
                foreach ($info as $m) {
                    if ($m['n_pid'] == $v['n_nid']) {
                        $node[$v['n_nid']]['node'][$m['n_nid']]['name'] = $m['n_name'];
                        $node[$v['n_nid']]['node'][$m['n_nid']]['title'] = $m['n_title'];
                        $node[$v['n_nid']]['node'][$m['n_nid']]['level'] = $m['n_level'];
                        $node[$v['n_nid']]['node'][$m['n_nid']]['nid'] = $m['n_nid'];
                        $node[$v['n_nid']]['node'][$m['n_nid']]['sort'] = $m['n_sort'];
                        $node[$v['n_nid']]['node'][$m['n_nid']]['access'] = $m['a_nid'];
                        foreach ($info as $c) {
                            if ($c['n_pid'] == $m['n_nid']) {
                                $node[$v['n_nid']]['node'][$m['n_nid']]['node'][$c['n_nid']]['name'] = $c['n_name'];
                                $node[$v['n_nid']]['node'][$m['n_nid']]['node'][$c['n_nid']]['title'] = $c['n_title'];
                                $node[$v['n_nid']]['node'][$m['n_nid']]['node'][$c['n_nid']]['level'] = $c['n_level'];
                                $node[$v['n_nid']]['node'][$m['n_nid']]['node'][$c['n_nid']]['nid'] = $c['n_nid'];
                                $node[$v['n_nid']]['node'][$m['n_nid']]['node'][$c['n_nid']]['sort'] = $c['n_sort'];
                                $node[$v['n_nid']]['node'][$m['n_nid']]['node'][$c['n_nid']]['access'] = $c['a_nid'];
                            }
                        }
                    }
                }
            }
        }

        return $node;
    }

    /**
     * 得到所有RBAC 用户角色节点 信息列表
     */
    public function get_role_list() {
        //数据库权限
        $sql = "SELECT  n.nid AS n_nid, n.name AS n_name, 
            n.title AS n_title,n.level AS n_level, n.pid AS n_pid
FROM " . C("DB_PREFIX") . C("NODE_TABLE") . " AS n
INNER JOIN " . C("DB_PREFIX") . C("ACCESS_TABLE") . " AS a ON n.nid = a.nid
INNER JOIN " . C("DB_PREFIX") . C("ROLE_TABLE") . " AS r ON r.rid = a.rid
WHERE n.state =1
AND r.state =1
ORDER BY n.level";
        $db = M();
        $info = $db->query($sql);
        if (empty($info))
            return;
        $access = array();
        foreach ($info as $v) {
            if ($v['n_level'] == 1) {
                $access[$v['n_name']] = array();
                $pid = $v['n_nid'];
                foreach ($info as $i => $n) {
                    if ($n['n_pid'] == $pid) {
                        $pid2 = $n['n_nid'];
                        $access[$v['n_name']][$n['n_name']] = array();
                        foreach ($info as $i => $j) {
                            if ($j['n_pid'] == $pid2) {
                                $access[$v['n_name']][$n['n_name']][$j["n_name"]]['n_name'] = $j["n_name"];
                                $access[$v['n_name']][$n['n_name']][$j["n_name"]]['n_nid'] = $j["n_nid"];
                                $access[$v['n_name']][$n['n_name']][$j["n_name"]]['n_title'] = $j["n_title"];
                                $access[$v['n_name']][$n['n_name']][$j["n_name"]]['n_pid'] = $j["n_pid"];
                                $access[$v['n_name']][$n['n_name']][$j["n_name"]]['n_level'] = $j["n_level"];
                            }
                        }
                    }
                }
            }
        }
        return $access;
    }

    /**
     * 验证用户访问权限
     * @return boolean 
     */
    public function rbac_check_access() {
        //不验证标识
        if (!empty($_SESSION[C("RBAC_ADMIN")]))
            return true;
        //不需要验证
        if ($this->no_auth()) {
            return true;
        }
        //没有登录
        if (!isset($_SESSION[C('RBAC_AUTH')])) {
            return false;
        }
        if (C("RBAC_TYPE") == 1) {//时时认证
            $this->get_access();
        }
        if ($this->no_auth()) {//不需要验证的方法 例app/control/method
            return true;
        }
        if (!isset($_SESSION['RBAC'])) {//如果不存在RBAC内容 不验证返回
            return false;
        }
        $access = $_SESSION['RBAC'];
        if (!is_array($access)) {
            return false;
        }
        if (array_key_exists(APP, $access)) {
            if (array_key_exists(CONTROL, $access[APP])) {
                if (in_array(METHOD, $access[APP][CONTROL])) {
                    return true;
                }
            }
        }
        return false;
    }

    //检测不需要验证的方法
    public function no_auth() {
        $no_auth = C("NO_AUTH");
        if (empty($no_auth) || !is_array($no_auth)) {
            return false;
        }
        $auth = array(
            "app" => array(), "control" => array(), "method" => array()
        );
        foreach ($no_auth as $v) {
            $arr = preg_split("/\||\//", $v);
            if (count($arr) != 2) {
                error("配置项:NO_AUTH 设置错误必须为[控制器/方法]格式", false);
            }
            $auth['control'][] = strtolower($arr[0]);
            $auth['method'][] = strtolower($arr[1]);
        }
        return in_array(strtolower(CONTROL), $auth['control']) && in_array(strtolower(METHOD), $auth['method']);
    }

}