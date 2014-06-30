<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-23
 * Describe   : 登录控制器
 */

class authControl extends Control {

    private $auth;
    private $user;

    function __construct() {
        parent::__construct();
        $this->auth = new auth;
        $this->user = K('user');
    }

    function index() {
        $this->login();
    }

    private function _captcha_check($code) {
        return $this->auth->captcha_check($code);
    }

    function login() {
        if (!$this->auth->is_logged_in()) {
            //验证字段
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $code = isset($_POST['code']) ? $_POST['code'] : FALSE;
                // 如果尝试登录次数大于配置次数设置验证码规则
                if ($this->auth->is_max_login_attempts_exceeded() or C('AUTH_CAPTCHA_LOGIN') or $code!=FALSE) {
                    if (!$this->_captcha_check($_POST['code'])) {
                        $this->error(L('validata_error'), 'index');
                    }
                }

                $username = isset($_POST['name']) ? addslashes($_POST['name']) : FALSE;
                $password = isset($_POST['pwd']) ? $_POST['pwd'] : FALSE;
                $remember = isset($_POST['remember']) ? $_POST['remember'] : FALSE;
                if ($this->auth->auth_user_login($username, $password, $remember)) {
                    // 验证成功重定向至首页
                    //查找用户积分
                    $_SESSION['point'] = $this->_getUserPoint($_SESSION['uid']);

                    if($_SESSION['last_login']<strtotime(date('Y-m-d'))){//每天登录+积分
                        $point=getPointRule('firstLogin');//获得应扣取积分
                        deductPoint($point);//增加积分
                        $con=date('Y-m-d').' 第一次登录，(积分剩余：'.$_SESSION['point'].')';
                        writeOptLog($con,$point);//写入日志
                    }
                    if (in_array('3', $_SESSION['role']['rid'])) {//如果是企业用户
                        $this->success('登录成功', __WEB__ . '/index/company.html');
                    } else
                    if (in_array('4', $_SESSION['role']['rid'])) {//如果是个人用户
                        $this->success('登录成功', __WEB__ . '/index/profile.html');
                    } else {
                        $this->success('登录成功', __WEB__);
                    }
                } else {
                    //检查用户无法登录是否因为用户是被禁止的或没有用户
                    if ($this->auth->is_banned()) {
                        $this->error('对不起，你已被禁止登录，禁止原因：'.$this->auth->get_ban_reason(),__WEB__);
                    } else {
                        $this->error($this->auth->error);
                    }
                }
            } else {
                //默认不显示验证码，直到登录错误到了最大值
                $show_captcha = FALSE;
                // 如果登录错误大于配置文件中的最大登录错误次数或默认开启显示验证码则显示验证码
                if ($this->auth->is_max_login_attempts_exceeded() || C('AUTH_CAPTCHA_LOGIN')) {
                    $show_captcha = TRUE;
                }
                //加载登录页面
                $this->assign('show_captcha', $show_captcha);
                $this->display('login');
            }
        } else {
            $this->success('你已经登录了', __ROOT__);
        }
    }

    /* 获取注册用户类型，过滤调非普通用户和企业用户注册 */

    function getRegType($type) {
        $result = $type;
        if ($type != 'company' && $type != 'user') {
            $result = 'user';
        }
        return $result;
    }

    /**
     * 注册用户 
     */
    function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['register'])) {
           
            if (!isset($_POST['agree']) || $_POST['agree'] != 1) {
                $this->error('注册失败，你没有同意注册协议！');
            }
            $_POST['rid'] = 4;//普通用户
            if ($_POST['id'] == 'company') {
                $_POST['rid'] = 3;//企业用户
            }
            $data = array(
                'username' => $_POST['name'],
                'password' => $_POST['pwd'],
                're-password' => $_POST['re-pwd'],
                'rid' => $_POST['rid'],
                'email' => $_POST['email']
            );
            if (isset($_POST['code'])) {
                $data['validata-code'] = $_POST['code'];
            }
            $code = isset($_SESSION['code']) ? $_SESSION['code'] : '';
            $this->user->user_model->validate = array(
                array("username", "user:5,19", "用户名格式错误 ", 2),
                array("password", "length:5,21", "密码长度为6-20 ", 2),
                array("re-password", "confirm:pwd", "两次密码不一致 ", 2),
                array("email", "email", "Email格式错误 ", 2),
                array("validata-code", "eq:{$code}", "验证码错误", 1), //有则验证
            );
            if (!$this->user->user_model->validate($data)) {
                $this->error($this->user->user_model->error);
            }
            if ($this->user->userExist($_POST['name'])) {
                $this->error('用户名已经存在！');
            }
            if ($this->user->emailExist($_POST['email'])) {
                $this->error('Email已经存在！');
            }
            
            $data['created'] = time();
            $data['last_ip'] = ip_get_client();
            if ($this->auth->register($data)) {//注册成功
                if (C('AUTH_EMAIL_ACTIVATE')) {//如果开启了使用EMAIL验证注册。
                    $this->success('恭喜你，注册成功。请检查您的电子邮件来激活您的帐户。', __WEB__, 5);
                }
                $this->success('恭喜你注册成功，即将跳转到登录页面。<a href="' . __WEB__ . '/login">马上登录</a>', __WEB__ . '/login');
            }else{
                $this->error('注册失败！请仔细检查你的注册资料。还未能解决？<a href="'.__WEB__.'/index/index/feedback/type/4">提交反馈</a>');
            }
        } else {
            if (!$this->auth->is_logged_in() && C('ALLOW_REGISTER')) {
                $show_captcha = C('AUTH_REG_CODE');
                $get_type = empty($_GET['type']) ? 'user' : $_GET['type'];
                $type = $this->getRegType($get_type);
                $this->assign('type', $type);
                $this->assign('show_captcha', $show_captcha); //如果显示验证码
                $this->display();
            } else if ($this->auth->is_logged_in()) {
                $this->error('注册时请先注销登录，点击<a href="' . __CONTROL__ . '/logout">注销</a>', __WEB__, 5);
            } else {
                $this->error('悲剧了，网站禁止注册！', __WEB__, 10);
            }
        }
    }

    function activate() {
        $username = $_GET['username'];
        $key = $_GET['key'];
        if ($this->auth->activate($username, $key)) {
            $this->success('恭喜你，您的帐户已成功激活。', 'index');
        }
        $this->error('您的激活码是不正确的。请再次检查您的电子邮件。', __WEB__);
    }

    //验证Email
    function authEmail() {
        $db = K('email_activate');
        $uid=intval($_GET['user']);
        $key=$db->getAuthItem(array('uid'=>$uid,'activation_key'=>$_GET['key']));
        if (!empty($key)) {
            $db->authEmailSuccess($uid,$key['email']);
            $db->delAuthItem($uid);//删除email_activate表中的验证信息
            $this->success('恭喜你！邮箱验证成功。', __WEB__);
        }else{
            $this->error('您的邮箱验证码是不正确的。请再次检查您的电子邮件，或登录后重新发送验证邮件。', __WEB__,10);
        }
    }

    /**
     * Ajax查找用户是否存在
     * @param type $username 
     */
    function userExist() {
        $username = $_POST['name'];
        if ($this->user->userExist($username)) {
            echo 1;
            exit;
        }
        echo 0;
        exit;
    }

    function emailExist() {
        $email = $_POST['email'];
        if ($this->user->emailExist($email)) {
            echo 1;
            exit;
        }
        echo 0;
        exit;
    }

    /**
     *  验证码 
     */
    function validateCode() {
        $code = new code();
        $code->show();
    }

    /**
     * 注销登录 
     */
    function logout() {
        setcookie(C('AUTH_AUTOLOGIN_COOKIE_NAME'), '', time() - 100000, '/'); //删除cookie
        $this->session_destroy();
        $this->success('注销成功！', __ROOT__);
    }

    /**
     * 查找用户积分
     * @param type $uid
     */
    private function _getUserPoint($uid) {
        $db = M('user_point');
        $point = $db->where('uid=' . $uid)->find();
        return $point['point'];
    }

    /**
     * 忘记密码
     */
    // public function forget_passwd()
    // {
    //     if($_SERVER['REQUEST_METHOD']=='POST'){
    //         if($_GET['action']=='user'){
    //             dump($_POST);
    //         }
    //     }
    //     $this->display();
    // }

}

