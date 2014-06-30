<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-6-27
 * Describe   : 用户（组）管理模型
 */

class userModel extends Model {

    public $user_r_model;
    private $role_model;
    private $user_role_model;
    private $user_temp_model;
    public $user_model;

    function __construct() {
        $this->user_r_model = R('user');
        $this->user_model = M('user');
        $this->user_temp_model = M('user_temp');
        $this->user_r_model->join = array(
            "role" => array(
                "type" => "MANY_TO_MANY", //关联类型多对多关联
                "relation_table_parent_key" => "uid", //主表 user关联字段
                "relation_table_foreign_key" => "rid", //关联表 role关联字段
                "relation_table" => "user_role", //多对多的中间表
                //'where'=>'role.rid=1'
            ),
        );
        $this->role_model = M('role');
        $this->user_role_model = M('user_role');
    }

    function roleList($cond=array(),$field=array()) {
        $role_list = $this->role_model->field($field)->where($cond)->order('sort')->findall();
        return $role_list;
    }

    /**
     * 添加用户
     * @param type $user_data POST数据
     * @return boolean 
     */
    function addUser($user_data = '', $table = 'user') {
        if ($user_data == '') {
            $user_data = $_POST;
        }
        $table.='_model';
        $uid = $this->$table->insert($user_data);
        if ($table == 'user_temp_model' && $uid) {
            return TRUE;
        }
        if ($table == 'user_model' && $uid) {
            $user_role = array();
            $user_role['uid'] = $uid;
            $user_role['rid'] = $user_data['rid'];
            $ur_id = $this->user_role_model->insert($user_role);
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 更新角色信息
     */
    public function updateRole($cond,$data)
    {
        return $this->role_model->in($cond)->update($data)>=0;
    }
    /**
     * 删除角色
     */
    public function delRole($id,$cond=array())
    {
        $db=R('role');
        $db->join=array(
            'access'=>array(
                'type'=>HAS_ONE,
                'foreign_key'=>'rid'
            )
        );
        return $db->where($cond)->in($id)->del();
    }

    /**
     * 添加用户角色
     */
    function addRole($role_data) {
        $rid = $this->role_model->insert($role_data);
        if ($rid) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function userList($cond=array()) {
        if(!empty($cond['group'])){//如果有用户组条件
            $db=V('user');
            $db->view=array(
                'user_role'=>array(
                    'type'=>'inner',
                    'on'=>'user.uid =user_role.uid',
                ),
                'role'=>array(
                    'type'=>'inner',
                    'on'=>'user_role.rid=role.rid'
                )
            );
            $nums=$db->where($cond['user'],$cond['group'])->count();
            $page=new page($nums,12);
            $users=array();
            $users['user']=$db->where($cond['user'],$cond['group'])->findall();
            $users['page']=$page->show();
        }else{
            $nums=$this->user_r_model->where($cond['user'])->count();
            $page=new page($nums,12);
            $users=array();
            $users['user']=$this->user_r_model->where($cond['user'])->order('created desc')->findall($page->limit());
            $users['page']=$page->show();
        }
        return $users;
    }

    /**
     * 判断用户是否存在
     * @param type $username 用户名
     * @return boolean 存在返回TRUE
     */
    function userExist($username) {
        $result = $this->user_model->where(array('username' => $username))->find();
        $result2 = $this->user_temp_model->where(array('username' => $username))->find();
        if ($result || $result2) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 判断Email是否存在
     * @param type $username 用户名
     * @return boolean 存在返回TRUE
     */
    function emailExist($email) {
        $result = $this->user_model->where(array('email' => $email))->find();
        $result2 = $this->user_temp_model->where(array('email' => $email))->find();
        if ($result || $result2) {
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 禁止用户
     * @param mixed $cond 条件
     * @param int $type  禁止类型,1:禁止,0：解禁
     */
    public function banUser($cond,$type=1)
    {
        return $this->user_model->in($cond)->update(array('banned'=>$type))>=0;
    }
    /**
     * 企业资料
     */
    public function companyInfo($uid)
    {
        $db=R('user');
        $db->join = array(
            'company_info'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid',
                "other" => TRUE
            ),
            'user_point'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid',
                "other" => TRUE
            )
        );
        $userinfo=$db->where('uid='.$uid)->join('company_info|user_point')->find();
        return $userinfo;
    }

    public function updateUserinfo($cond,$data)
    {
        $db=R('user');
        $db->join = array(
            'user_point'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid',
                "other" => TRUE
            )
        );
        return $db->where($cond)->join('user_point')->update($data)>=0;
    }
    /**
     * 用户资料
     */
    public function userInfo($uid,$field=array())
    {
        $db=R('user');
        $db->join = array(
            'user_info'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid',
                "other" => TRUE
            ),
            'user_point'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid',
                "other" => TRUE
            )
        );
        return $db->field($field)->where('uid='.$uid)->join('user_info|user_point')->find();
    }
    public function userInfo_V($uid,$field=array())
    {
        $db=V('user');
        $db->view = array(
            'user_info'=>array(
                'type'=>'INNER',
                'on'=>'user.uid=user_info.uid',
            )
        );
        return $db->field($field)->where('user.uid='.$uid)->find();
    }
    /**
     * 删除用户
     */
    function delUser($id)
    {
        $db=R('user');
        $db->join = array(
            "role" => array(
                "type" => "MANY_TO_MANY", //关联类型多对多关联
                "relation_table_parent_key" => "uid", //主表 user关联字段
                "relation_table_foreign_key" => "rid", //关联表 role关联字段
                "relation_table" => "user_role", //多对多的中间表
                "other" => TRUE
            ),
            'user_info'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid'
            ),
            'company_info'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid'
            ),
            'user_point'=>array(
                'type'=>'HAS_ONE',
                'foreign_key'=>'uid'
            )
        );
        $db->in($id)->del();
        return TRUE;
    }

}