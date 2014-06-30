<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-29
 * Describe   : 
 */

class authModel extends Model {

    public $user;
    private $user_role;
    private $role;
    private $user_autologin;
    private $login_attempts;
    private $user_agent;
    private $user_temp;

    function __construct() {
        $this->user_agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];
        $this->user = M('user');
        $this->user_temp = M('user_temp');
        $this->role = M('role');
        $this->login_attempts = M('login_attempts');
        $this->user_autologin = M('user_autologin');
        $this->user_role = V('user_role');
        $this->user_role->view = array(
            'role' => array(//定义 user_info表规则
                'join_type' => "inner", //指定连接方式
                "field" => 'rid,rname,pid,title', //字段，email字段起别
                "on" => "user_role.rid=role.rid", //关联条件
            ),
        );
    }

    function get_role_by_rid($rid) {
        return $this->role->where(array('rid' => $rid))->find();
    }

    function get_login($login) {
        return $this->user->where("username = '$login' OR email = '$login'")->find();
    }
    function get_user_by_id($id) {
        return $this->user->where(array("uid" => $id))->find();
    }

    function get_user_by_email($email) {
        return $this->user->where(array("email" => $email))->find();
    }

    function get_user_by_username($username) {
        return $this->user->where(array("username" => $username))->find();
    }

    //user_temp
    function get_login_temp($login) {
        return $this->user_temp->where("username = '$login' OR email = '$login'")->find();
    }

    function get_user_by_email_temp($email) {
        return $this->user_temp->where(array("email" => $email))->find();
    }

    function get_user_by_username_temp($username) {
        return $this->user_temp->where(array("username" => $username))->find();
    }

    //user_temp结束
    function get_user_role_by_id($uid) {
        return $this->user_role->where("role.state=1")->where(array('uid' => $uid))->findall();
    }

    function set_user($user_id, $data) {
        return $this->user->where(array('uid' => $user_id))->update($data);
    }

    function clear_newpass($user_id) {
        $data = array(
            'newpass' => NULL,
            'newpass_key' => NULL,
            'newpass_time' => NULL
        );
        return $this->set_user($user_id, $data);
    }

    function get_permissions($roles_id) {
        $db = M('access');
        $data=$db->field('permissions')->in(array('rid'=>$roles_id))->findall();
        $permissions = array();
        if($data){
            foreach ($data as $value) {
                $permissions=array_merge($permissions,json_decode($value['permissions'],true));
            }
        }
        return $permissions;
    }

    //获取访问权限节点
    function get_permissions_data($roles_id) {
        return $this->get_permissions($roles_id);
    }

    //自动登录
    function prune_keys($uid) {
        $data = array(
            'user_id' => $uid,
            'user_agent' => substr($this->user_agent, 0, 149),
            'last_ip' => ip_get_client()
        );
        return $this->user_autologin->where($data)->delete();
    }

    function store_key($key, $user_id) {
        $user = array(
            'key_id' => md5_d($key),
            'user_id' => $user_id,
            'user_agent' => substr($this->user_agent, 0, 149),
            'last_ip' => ip_get_client(),
            'last_login' => time()
        );
        $this->user_autologin->insert($user);
        return $this->user_autologin->get_affected_rows();
    }

    function get_key($key, $user_id) {
        $db=V('user');
        $db->view=array(
            'user_autologin'=>array(
                'type'=>'inner',
                'on'=>'user_autologin.user_id=user.uid'
            )
        );
        $result =$db->field('user.uid,user.username,user.last_ip,user.last_login')
                    ->where("user.uid=$user_id AND user_autologin.key_id='" . md5_d($key) . "'")
                    ->find();
        //$result = $this->user_autologin->query($sql);
        return $result;
    }

    //尝试错误登录次数
    function increase_attempt($ip_address) {
        $data = array(
            'ip_address' => $ip_address,
            'time' => time()
        );
        $this->login_attempts->insert($data);
    }

    function check_attempts($ip) {
        $resule = $this->login_attempts->where(array('ip_address' => $ip))->findall();
        return $this->login_attempts->get_affected_rows();
    }

    function clear_attempts($ip) {
        $this->login_attempts->where(array('ip_address' => $ip))->delete();
    }

    function create_temp($data) {
        $data['created'] = time();
        return $this->user_temp->insert($data);
    }

    function create_user($data) {
        $data['created'] = time();
        $data['last_login']=time();
        $data['last_ip']=ip_get_client();
        $id = $this->user->insert($data);
        if ($id) {
            $this->user_role->insert(array('uid' => $id, 'rid' => $data['rid']));
            return $id;
        }
        return FALSE;
    }

    /**
     * 删除到期的激活账户信息 
     */
    function delExpireActivate() {
        $this->user_temp->where('created <' . time() - C('EMAIL_ACTIVATE_EXPIRE'))->del();
    }

    function activate_user_info($username, $key) {
        return $this->user_temp->where("username='$username' AND activation_key='$key'")->find();
    }

    function delete_temp_user($id) {
        $this->user_temp->del($id);
    }

}