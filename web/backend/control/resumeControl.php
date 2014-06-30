<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-9-8
 * Describe   : 后台简历管理
 */

class resumeControl extends myControl {
	private $resume;
	public function __construct()
	{
		parent::__construct();
		$this->resume=M('resume');
	}
	
	/**
	 * 简历列表
	 */
	public function resumeList()
	{
		$db=V('resume');
		$db->view=array(
			'user_info'=>array(
				'type'=>'inner',
				'on'=>'resume.uid=user_info.uid',
				'field'=>'name'
			)
		);
		$cond=array();
		if(isset($_GET['resume_name'])){
			$cond[]='resume_name like "%'.$_GET['resume_name'].'%"';
		}
		if(isset($_GET['name'])){
			$cond[]='name like "%'.$_GET['name'].'%"';
		}
		if(isset($_GET['created'])){
            $cond['created']=array(
                'gt'=>strtotime($_GET['created']),
                'lt'=>time()
            );
        }
        if(isset($_GET['updated'])){
            $cond['updated']=array(
                'gt'=>strtotime($_GET['updated']),
                'lt'=>time()
            );
        }
        if(isset($_GET['verify'])){
            $cond['verify']=$_GET['verify'];
        }
        $nums=$db->where($cond)->count();
        $page = new page($nums,13);
		$resumes=$db->where($cond)->findall($page->limit());
		$this->assign('resumes',$resumes);
		$this->assign('page',$page->show());
		$this->display();
	}

	/**
	 * 审核简历
	 */
	public function verifyResume()
	{
		C('DEBUG',1);
		$data=array();
		if($_POST['type']=='verify-unpass'){//不通过
			$data['verify']=0;
		}else{
			$data['verify']=1;
		}
		$this->resume->in(array('resume_id'=>$_POST['resume_id']))->update($data);
		echo 1;
		exit();
	}

	/**
	 * 删除简历
	 */
	public function delResume()
	{
		$db=M('model');
		$resume_table=$db->field('name')->where('mcid=2')->findall();//查找简历模型中的表
		$db->table('resume')->in(array('resume_id'=>$_POST['resume_id']))->del();
		foreach ($resume_table as $value) {
			$db->table($value['name'])->in(array('resume_id'=>$_POST['resume_id']))->del();
		}
		echo 1;
		exit;
	}
}