<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-9-4
 * Describe   : 
 */

class companyControl extends myControl {

    private $spread_cate;
    private $spread;

    function __construct() {
        parent::__construct();
        $this->spread_cate = M('spread_cate');
        $this->spread=M('spread');
    }

    function spreadList() {
        $db=V('spread');
        $db->view=array(
            'spread_cate'=>array(
                'type'=>'inner',
                'on'=>'spread.cate_id=spread_cate.id',
                'field'=>'cate_name'
            ),
            'recruit'=>array(
                'type'=>'left',
                'on'=>'spread.recruit_id=recruit.recruit_id',
                'field'=>'recruit_name,company_name'
            )
        );
        $spreads = $this->spread_cate->findall();
        $spread_lists=$db->findall();
        $this->assign('spreads', $spreads);
        $this->assign('spread_lists', $spread_lists);
        $this->display();
    }

    function findRecruit() {
        $db=M('recruit');
        if($_GET['way']=='company_name'){
            $cond='company_name like "%'.$_GET['value'].'%"';
        }
        if($_GET['way']=='uid'){
            $cond='uid='.(int)$_GET['value'];
        }
        if($_GET['way']=='recruit_name'){
            $cond='recruit_name like "%'.$_GET['value'].'%"';
        }
        if($_GET['way']=='recruit_id'){
            $cond='recruit_id='.(int)$_GET['value'];
        }
        $recruits=$db->field('recruit_id,recruit_name,company_name,uid,created,expiration_time,refresh_date')->where($cond)->findall();
        $this->assign('recruits', $recruits);
        $this->display();
        exit;
    }

    function addSpread() {
        $db=M('spread');
        $_POST['starttime']=time();
        $_POST['endtime']=strtotime($_POST['days'].'days');
        if($db->insert($_POST)){
            $this->success('添加推广成功。');
        }
    }
    public function delSpread()
    {
        $db=M('spread');
        if($db->in($_POST)->del()){
            echo 1;
            exit;
        }
    }

    /**
     * 修改推广
     */
    public function editSpread()
    {
        $endtime=$this->spread->field('days,endtime')->find($_GET['id']);
        $newtime=$endtime['endtime']+($_POST['days']*86400);
        $data=array();
        $data['endtime']=$newtime;
        $data['days']=$endtime['days']+$_POST['days'];
        $this->spread->where('id='.$_GET['id'])->update($data);
    }

    /**
     * 删除招聘
     */
    public function delRecruit()
    {
        $db=M('recruit');
        if($db->in($_POST)->del()){
            echo 1;
            exit;
        }
    }

    /**
     * 审核、开启关闭招聘
     */
    public function verifyRecruit()
    {
        $db=M('recruit');
        $data=array();
        if($_POST['type']=='enable'){
            $data['state']=1;
        }
        if($_POST['type']=='close'){
            $data['state']=0;
        }
        if($_POST['type']=='verify-unpass'){
            $data['verify']=0;
        }
        if($_POST['type']=='verify-pass'){
            $data['verify']=1;
        }
        $db->in(array('recruit_id'=>$_POST['recruit_id']))->update($data);
        echo 1;
        exit;
    }

    /**
     * 招聘列表
     */
    public function recruitList()
    {
        $db=M('recruit');
        //组合条件
        $cond=array();
        if(isset($_GET['recruit_name'])){
            $cond[]='recruit_name like "%'.$_GET['recruit_name'].'%"';
        }
        if(isset($_GET['company_name'])){
            $cond[]='company_name like "%'.$_GET['company_name'].'%"';
        }
        if(isset($_GET['created'])){
            $cond['created']=array(
                'gt'=>strtotime($_GET['created']),
                'lt'=>time()
            );
        }
        if(isset($_GET['refresh_date'])){
            $cond['refresh_date']=array(
                'gt'=>strtotime($_GET['refresh_date']),
                'lt'=>time()
            );
        }
        if(isset($_GET['state'])){
            if($_GET['state']==2){//已过期
                $cond[]='expiration_time <'.time();
            }else{
                $cond['state']=$_GET['state'];
            }
        }
        if(isset($_GET['verify'])){
            $cond['verify']=$_GET['verify'];
        }
        //组合条件
        $field='recruit_id,recruit_name,created,refresh_date,company_name,state,verify,expiration_time,views';
        $nums=$db->where($cond)->count();
        $page=new page($nums,15);
        $recruits=$db->field($field)->where($cond)->order('created desc')->findall($page->limit());
        $this->assign('recruits', $recruits);
        $this->assign('page', $page->show());
        $this->display();
    }

    /**
     * 营业执照审核
     */
    public function licenseverify()
    {
        $db=M('company_info');
        if($_SERVER['REQUEST_METHOD']=='POST'){
            C('DEBUG',1);
            $db->in(array('uid'=>$_POST['id']))->update(array('license_verify'=>$_POST['license_verify']));
            echo 1;
            exit();
        }
        $cond=array();
        if(isset($_GET['name'])){
            $cond[]='name like "%'.$_GET['name'].'%"';
        }
        if(isset($_GET['license_verify'])){
            $cond['license_verify']=$_GET['license_verify'];
        }
        $licenses=$db->field('uid,name,license,license_verify')->where($cond)->order('license_verify desc')->findall();
        $this->assign('licenses', $licenses);
        $this->display();
    }
    function addSpreadCate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->spread_cate->insert($_POST);
            $success = array('添加成功', 'spreadList?action=2');
            $error = array('添加失败');
            $this->success_error($result, $success, $error);
        }
    }

    function editSpreadCate() {
        $cate = $_GET['cate'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editSpread'])) {
            $result = $this->spread_cate->where('id=' . $cate)->update($_POST) >= 0;
            $success = array('修改成功', 'spreadList?action=2');
            $error = array('修改失败');
            $this->success_error($result, $success, $error);
        } else {
            $spread_cate = $this->spread_cate->find($cate);
            $this->assign('spread_cate', $spread_cate);
            $this->display();
        }
    }

    function delSpreadCate() {
        $cate = $_GET['cate'];
        $result = $this->spread_cate->del($cate);
        $success = array('删除成功', 'spreadList?action=2', 1);
        $error = array('删除失败');
        $this->success_error($result, $success, $error);
    }

}

