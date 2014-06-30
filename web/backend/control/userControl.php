<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-6-26
 * Describe   : 用户管理控制器
 */

class userControl extends myControl {

    private $user;

    function __construct() {
        parent::__construct();
        $this->user = K('index/user');
    }

    function userList() {
        $cond=array(
            'group'=>array(),
            'user'=>array()
        );
        if(isset($_GET['rid'])){
            $cond['group']='user_role.rid='.$_GET['rid'];
        }
        if(isset($_GET['created'])){
            $cond['user']['created']=array(
                'gt'=>strtotime($_GET['created']),
                'lt'=>time()
            );
        }
        if(isset($_GET['username'])){
            $cond['user'][]='username like "%'.$_GET['username'].'%"';
        }
        if(isset($_GET['email'])){
            $cond['user']['email']=$_GET['email'];
        }
        if(isset($_GET['banned'])){
            $cond['user']['banned']=$_GET['banned'];
        }
        $role_list = $this->user->roleList(array(),'rid,title,pid,state');
        $users = $this->user->userList($cond);
        $this->assign('users', $users['user']);
        $this->assign('page', $users['page']);
        $this->assign('role_list', $role_list);
        $this->display();
    }

    /**
     * 添加用户 
     */
    function addUser() 
    {
        $role_list = $this->user->roleList('state=1');
        if ($_SERVER['REQUEST_METHOD']=='POST') {
            if ($this->user->userExist($_POST['username'])) {
                $this->error('用户名已经存在！');
            }
            if (!empty($_POST['email']) && $this->user->emailExist($_POST['email'])) {
                $this->error('Email已经存在！');
            }
            $auth_class=new auth();
            C('AUTH_EMAIL_ACTIVATE',FALSE);//关闭电子邮件激活
            if ($auth_class->register($_POST)) {
                $this->success('添加成功');
            }
        }
        $this->assign('role_list', $role_list);
        $this->display();
    }
    public function viewUserInfo()
    {
        if($_GET['type']=='cu'){//企业用户
            $userinfo=$this->user->companyInfo($_GET['id']);
        }
        if($_GET['type']=='pu'){//个人用户
            $userinfo=$this->user->userInfo($_GET['id']);
        }
        $this->assign('userinfo',$userinfo);
        $this->display('viewUserInfo_'.$_GET['type']);
    }
    /**
     * 修改用户信息表单
     */
    public function editUserInfoForm()
    {
        $userinfo=$this->user->userInfo($_GET['id']);
        $this->assign('userinfo',$userinfo);
        $this->display('editUserInfo');
    }
    /**
     * 修改用户信息
     */
     public function editUserInfo()
    {
            $cond='uid='.$_GET['id'];
            if(empty($_POST['password'])){
                unset($_POST['password']);
            }else{
                $_POST['password']=md5_d($_POST['password']);
            }
            $_POST['user_point']=array('point'=>$_POST['point']);
            if($this->user->updateUserinfo($cond,$_POST)){//更新用户信息,user、point表
                go('userList');
            }
    }
    //禁止用户
    public function banUser()
    {
        if($this->user->banUser(array('uid'=>$_POST['id']),$_POST['type'])){
            echo 1;
            exit;
        }
    }
    //删除用户
    public function delUser()
    {
        if($this->user->delUser(array('uid'=>$_POST['id']))){
            echo 1;
            exit;   
        }
    }

    /**
     * 添加角色 
     */
    function addRole() 
    {
        if (isset($_POST['addRole'])) {
            $this->user->addRole($_POST);
            $this->success('角色添加成功,别忘了配置角色权限噢。','roleList');
        }
        $roles=$this->user->roleList();
        $this->assign('roles',$roles);
        $this->display();
    }
    /**
     * 配置用户组权限
     */
    public function configPermission()
    {
        $db=M('node');
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $action='insert';
            if($db->table('access')->count($_GET['rid'])){
                $action='update';
            }
            $_POST['permissions']=json_encode($_POST['permissions']);
            $db->table('access')->$action();
            $this->success('权限修改成功。','roleList');
        }
        $authModel=new authModel;
        $permissions=$authModel->get_permissions(array($_GET['rid']));
        $nodes=$db->order('sort,nid')->findall();
        $nodes=formatLevelData2($nodes,array('nid','pid'));
        $this->assign('nodes',$nodes);
        $this->assign('permissions',$permissions);
        $this->display();
    }
    /**
     * 配置用户角色
     */
    public function configUserRole()
    {
        $db=M('user_role');
        $db->where('uid='.$_POST['uid'])->del();
        foreach ($_POST['rid'] as $value) {
            $db->insert(array('uid'=>$_POST['uid'],'rid'=>$value));
        }
        go('userList');
    }
    /**
     * 节点列表
     */
    public function nodeList()
    {
        $db=M('node');
        $nodes=$db->order('sort,nid')->findall();
        $nodes=formatLevelData2($nodes,array('nid','pid'));
        $this->assign('nodes',$nodes);
        $this->display();
    }
    /**
     * 角色列表
     */
    public function roleList()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            foreach ($_POST['sort'] as $key => $value) {
                //角色排序
                $this->user->updateRole(array('rid'=>$key),array('sort'=>$value));
            }
            go('roleList');
        }
        $roles=$this->user->roleList();
        $this->assign('roles',$roles);
        $this->display();
    }
    /**
     * 删除角色
     */
    public function delRole()
    {
        if($this->user->delRole($_POST,'is_sys=0')){
            echo 1;
            exit;
        }
        echo 0;
        exit;
    }
    /**
     * 禁止/开启用户角色
     */
    public function banRole()
    {
        $this->user->updateRole($_POST,array('state'=>$_GET['state']));
        echo 1;
        exit;
    }
    public function addNode()
    {
        $db=M('node');
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $_POST['name']='/'.trim($_POST['name'],'/').'/';
            if($db->insert()){
                $this->success('添加节点成功。','nodeList');
            }
        }
        $nodes=$db->order('sort,nid')->findall();
        $nodes=formatLevelData2($nodes,array('nid','pid'));
        $this->assign('nodes',$nodes);
        $this->display();
    }
    /**
     * 删除节点
     */
    public function delNode()
    {
        $db=M('node');
        if($db->in(node_son_id($_POST['id']))->del()){
            echo 1;
            exit;
        }
    }
    /**
     * 修改节点
     */
    public function editNode()
    {
        $db=M('node');
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $db->where('nid='.$_GET['nid'])->update();
            $this->success('修改节点信息成功','nodeList');
        }
        $node=$db->where('nid='.$_GET['nid'])->find();
        $nodes=$db->field('nid,title,pid')->order('sort,nid')->findall();
        $nodes=formatLevelData2($nodes,array('nid','pid'));
        $this->assign('nodes',$nodes);
        $this->assign('node',$node);
        $this->display();
    }

}