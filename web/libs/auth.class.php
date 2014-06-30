<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-4
 * Describe   : 权限验证
 */
class auth {

    private $auth_model;
    private $_banned;
    private $_ban_reason;
    public $error;

    function __construct() {
        $this->auth_model = new authModel; //载入权限验证模型        
        $this->autologin(); //检测是否有自动登录
    }

    /**
     * 用户登录
     * @param type $login 登录POST参数
     * @param type $super_user 超级用户名
     */
    function auth_user_login($login, $password, $remember = 0, $super_user = 'hdroot') {
        $result = FALSE; //
        $login_mode_func = 'get_user_by_username';
        if (!empty($login) AND !empty($password)) {
            if (C('AUTH_USERNAME_LOGIN') && C('AUTH_EMAIL_LOGIN')) {//用户名和Email都可以登录
                $login_mode_func = 'get_login';
            } else if (C('AUTH_EMAIL_LOGIN')) {//使用EMAIL登录
                $login_mode_func = 'get_user_by_email';
            } else {//使用用户名登录
                $login_mode_func = 'get_user_by_username';
            }
            $login_mode_func_temp = $login_mode_func . '_temp';
            $query = $this->auth_model->$login_mode_func($login);
            if ($query) {
                if ($query['banned'] > 0) {
                    // 设置用户禁用
                    $this->_banned = TRUE;
                    $this->_ban_reason = $query['ban_reason'];
                } else {//匹配密码
                    if (md5_d($password) == $query['password']) {
                        $this->_auth_set_session($query);
                        if ($query['newpass']) {
                            // 清除重置密码
                            $this->auth_model->clear_newpass($query['uid']);
                        }
                        if ($remember) {
                            // 创建自动登录
                            $this->_auth_create_autologin($query['uid']);
                        }

                        // 设置上次登录ip和时间
                        $this->_set_last_ip_and_last_login($query['uid']);
                        // 清楚登录尝试
                        $this->_clear_login_attempts();
                        $result = TRUE;
                    } else {//密码错误，设置错误次数
                        $this->_increase_login_attempt();
                        $this->error = L('username_or_password_error');
                    }
                }
            } else if ($this->auth_model->$login_mode_func_temp($login)) {//查找用户是否已注册，但是未激活
                $this->error = sprintf(L('user_not_activation'), $login);
            } else {
                // 增加登录尝试
                $this->_increase_login_attempt();
                // 设置错误信息
                $this->error = L('login_username_not_exist'); //用户不存在
            }
        } else {
            $this->error = L('no_username_or_pwd');
        }
        return $result;
    }
    

    /**
     * 注册用户 
     * @param type $data 注册的信息 用户名密码神马之类的。
     */
    function register($data) {
        $result = FALSE;
        $new_user = $data;
        $new_user['password'] = md5_d($data['password']);
        $new_user['last_ip'] = ip_get_client();
        // 发送电子邮件来激活用户
        if (C('AUTH_EMAIL_ACTIVATE')) {
            // 添加激活密钥到new_user array
            $new_user['activation_key'] = token();

            // 在数据库中创建的临时用户,用户仍然未激活。
            $insert = $this->auth_model->create_temp($new_user);
        } else {
            // 创建用户
            $insert = $this->auth_model->create_user($new_user);
            // 增加用户积分/创建用户资料……
            $this->_user_activated($insert,$new_user['rid']);
        }
        if ($insert) {
            // 原始密码
            $new_user['password'] = $data['password'];

            $result = $new_user;

            // 按照配置文件中的信息发送电子邮件
            // 如果用户需要使用电子邮件激活帐户
            if (C('AUTH_EMAIL_ACTIVATE')) {
                // 创建Email
                $from = C('email_username');
                $subject = sprintf(L('auth_activate_subject'), C('WEB_NAME'));
                // 激活链接
                $new_user['activate_url'] = __WEB__ . '/index/auth/activate/username/' . $new_user['username'] . '/key/' . $new_user['activation_key'];

                // 触发事件，并获得电子邮件的内容
                $new_user['expire'] = (C('EMAIL_ACTIVATE_EXPIRE') / 3600) . '小时';
                $content = getEmailTpl('register_active', $new_user);

                // 发送激活链接到邮件
                $this->_send_email($new_user['email'], $from, $content['subject'], $content['content']);
            } else {
                // 没有开启邮箱验证注册，但是注册后会发送账户信息
                if (C('EMAIL_ACCOUNT_INFO')) {
                    $from = C('email_username');
                    $subject = sprintf(L('auth_account_subject'), C('WEB_NAME'));
                    $content = getEmailTpl('register_info', $new_user);
                    $this->_send_email($new_user['email'], $from, $content['subject'], $content['content']);
                }
            }
        }

        return $result;
    }

    private function _send_email($tomail, $toname, $title, $body) {
        $email = new mail();
        $email->send($tomail, $toname, $title, $body);
    }

    //创建用户资料成功，增加用户积分
    function _user_activated($uid,$role=4) {
        $table='user_info';
        if($role==3){//企业用户
            $table='company_info';
        }
        $db=M($table);
        $db->insert(array('uid'=>$uid));
        $point=getPointRule('newUser');
        $db->table('user_point')->insert(array('uid'=>$uid,'point'=>$point));
    }

    //修改密码
    function change_password($data){
        $result=FALSE;
        $info=$this->auth_model->get_user_by_id($_SESSION['uid']);
        if(md5_d($data['old_pwd'])==$info['password']){
            if($this->auth_model->set_user($_SESSION['uid'],array('password'=>md5_d($data['password'])))>=0){
                $result=TRUE;
            }
        }else{
            $this->error='原密码错误！';
        }
        return $result;
    }

    //激活用户
    function activate($username, $key) {
        $result = FALSE;
        if (C('AUTH_EMAIL_ACTIVATE')) {
            //删除到期的激活
            $this->auth_model->delExpireActivate();
        }
        // 激活用户
        if ($userinfo = $this->auth_model->activate_user_info($username, $key)) {
            // 创建用户。 插入user表和user_role表
            $userinfo['email_verify']=1;//同时Email已经验证通过
            if ($uid=$this->auth_model->create_user($userinfo)) {
                $this->_user_activated($uid,$userinfo['rid']);//创建用户资料，增加用户积分
                //删除临时表中的数据
                $this->auth_model->delete_temp_user($userinfo['id']);

                $result = TRUE;
            }
        }

        return $result;
    }
    //自动登录
    function autologin() {
        $result = FALSE;
        $auto = isset($_COOKIE[C('AUTH_AUTOLOGIN_COOKIE_NAME')]) ? $_COOKIE[C('AUTH_AUTOLOGIN_COOKIE_NAME')] : FALSE;
        if (!$this->is_logged_in() && $auto) {
            $auto = unserialize($auto);
            if (isset($auto['key_id']) AND $auto['key_id'] AND $auto['user_id']) {

                $query = $this->auth_model->get_key($auto['key_id'], $auto['user_id']);

                if ($query) {
                    $this->_set_last_ip_and_last_login($auto['user_id']);//设置登录IP和时间
                    $this->_auth_set_session($query);
                    $this->_auto_cookie($auto);
                    $result = TRUE;
                }
            }
        }
        return $result;
    }

    //增加登录错误次数
    private function _increase_login_attempt() {
        if (C('AUTH_COUNT_LOGIN_ATTEMPTS') AND !$this->is_max_login_attempts_exceeded()) {
            $this->auth_model->increase_attempt(ip_get_client());
        }
    }

    //是否超过最大登录尝试次数
    function is_max_login_attempts_exceeded() {
        return ($this->auth_model->check_attempts(ip_get_client())) >= C('AUTH_MAX_LOGIN_ATTEMPTS');
    }

    private function _set_last_ip_and_last_login($uid) {
        $login_log = array(
            'last_ip' => ip_get_client(),
            'last_login' => time()
        );
        $this->auth_model->set_user($uid, $login_log);
    }

    //清楚登录尝试
    private function _clear_login_attempts() {
        if (C('AUTH_COUNT_LOGIN_ATTEMPTS')) {
            $this->auth_model->clear_attempts(ip_get_client());
        }
    }

    /**
     * 创建自动登录
     * @param type $uid 
     */
    private function _auth_create_autologin($uid) {
        $result = FALSE;

        //如果用户想要记住登录
        $user = array(
            'key_id' => substr(token(), 0, 16),
            'user_id' => $uid
        );
        // 先清楚用户之前的自动登录信息
        $this->auth_model->prune_keys($user['user_id']);
        if ($this->auth_model->store_key($user['key_id'], $user['user_id'])) {
            //设置用户自动登录cookie
            $this->_auto_cookie($user);
            $result = TRUE;
        }

        return $result;
    }

    private function _auto_cookie($data) {

        $cookie = array(
            'name' => C('AUTH_AUTOLOGIN_COOKIE_NAME'),
            'value' => serialize($data),
            'expire' => C('AUTH_AUTOLOGIN_COOKIE_LIFE')
        );
        setcookie($cookie['name'], $cookie['value'], time() + $cookie['expire'], '/');
    }

    /**
     * 用户登录成功设置SESSION值
     * @param type $data 
     */
    private function _auth_set_session($data) {
        $role_data = $this->_get_role_data($data['uid']);
        $_SESSION = array(
            'uid' => $data['uid'],
            'username' => $data['username'],
            'last_ip' => $data['last_ip'],
            'last_login' => $data['last_login'],
            'logged_in' => TRUE,
            'point'=>0,
            'role' => array(
                'rid' => $role_data['role_id'],
                'rname' => $role_data['role_name'],
                'rtitle' => $role_data['role_title'],
                'parent_rid' => $role_data['parent_roles_id'],
                'parent_rname' => $role_data['parent_roles_name'],
                'parent_rtitle' => $role_data['parent_roles_title'],
            )
        );
        $_SESSION['hd_auth'] = array(
            'permission' => $role_data['permission'],
            'parent_permissions' => $role_data['parent_permissions'],
        );
    }

    private function _get_role_data($uid) {
        //初始化变量
        $data = array();
        $role_name = array();
        $role_id = array();
        $role_title = array();
        $parent_roles_id = array();
        $parent_roles_name = array();
        $parent_roles_title = array();
        $permission = array();
        $parent_permissions = array();

        $query = $this->auth_model->get_user_role_by_id($uid); //通过id获取用户角色
        if ($query) {
            foreach ($query as $key => $value) {
                $role_id[] = $value['rid'];
                $role_name[] = $value['rname'];
                $role_title[] = $value['title'];
                if ($value['pid'] > 0) {
                    $parent_roles_id[] = $value['pid'];
                    $finished = FALSE;
                    $parent_id = $value['pid'];
                    //获取所有的父级角色
                    while ($finished == FALSE) {
                        $i_query = $this->auth_model->get_role_by_rid($parent_id);
                        // 如果角色存在
                        if ($i_query) {
                            // 保存值
                            $i_role = $i_query;

                            // 如果角色没有父级角色
                            if ($i_role['pid'] == 0) {
                                // 取得最后的父级角色信息
                                $parent_roles_name[] = $i_role['rname'];
                                $parent_roles_title[] = $i_role['title'];
                                // 停止循环
                                $finished = TRUE;
                            } else {
                                // 改变parent id 开始下次循环
                                $parent_id = $i_role['pid'];
                                $parent_roles_id[] = $parent_id;
                                $parent_roles_name[] = $i_role['rname'];
                                $parent_roles_title[] = $i_role['title'];
                            }
                        } else {
                            //parent_id没有找到，删除最后的parent_roles_id
                            array_pop($parent_roles_id);
                            $finished = TRUE;
                        }
                    }
                }
            }
        }
        //获取用户的权限
        $permission = $this->auth_model->get_permissions_data($role_id);
        //获取用户父级角色权限
        if (!empty($parent_roles_id)) {
            $parent_permissions = $this->auth_model->get_permissions_data($parent_roles_id);
        }
        $data['role_name'] = $role_name;
        $data['role_id'] = $role_id;
        $data['role_title'] = $role_title;
        $data['parent_roles_id'] = $parent_roles_id;
        $data['parent_roles_name'] = $parent_roles_name;
        $data['parent_roles_title'] = $parent_roles_title;
        $data['permission'] = $permission;
        $data['parent_permissions'] = $parent_permissions;
        return $data;
    }

    /**
     * 检验验证码是否正确
     */
    function captcha_check($validate_code) {
        $result = TRUE;
        if (strtolower($validate_code) != strtolower($_SESSION['code'])) {
            $result = FALSE;
        }
        return $result;
    }

    function is_banned() {
        return $this->_banned;
    }

    function get_ban_reason() {
        return $this->_ban_reason;
    }

    /**
     * 用户是否登录 
     */
    function is_logged_in() {
        return isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : FALSE;
        //return FALSE;
    }

    /**
     * 数组中任意项是否在另外的数组中
     * @param type $needle
     * @param type $haystack 
     */
    function _array_in_array($needle, $haystack) {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        //合并允许访问的权限
        $haystack = array_unique(array_merge($haystack['permission'], $haystack['parent_permissions']));
        foreach ($needle as $value) {
            if (in_array($value, $haystack)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    //检查uri是否是不需要验证的方法
    private function _check_no_auth_uri($method) {
        $no_auth_method = C('NO_AUTH_METHOD');
        if (!is_array(C('NO_AUTH_METHOD'))) {
            $no_auth_method = array($no_auth_method);
        }

        if (in_array($method, $no_auth_method)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 检查访问权限 
     */
    function check_uri_permissions() {
        $app_name = '/' . APP . '/';
        $control = $app_name . CONTROL . '/';
        $method = $control . METHOD . '/';
        if ($this->_check_no_auth_uri($method) OR $this->_array_in_array(array('/', $method), $_SESSION['hd_auth'])) {
            return TRUE;
        } else {
            $this->error = L('no_permission');
        }
    }

}