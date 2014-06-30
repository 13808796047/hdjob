<?php
 /*
  * Description:内容中心
  * Email zhangbo1248@gmail.com
  * Copyright (C) 2012 zhangbo
  */

class indexControl extends control
{
	private $arc;

	public function __construct()
	{
		parent::__construct();
		$this->arc=K('backend/arc');
	}

	public function index()
	{
		$this->display('content/home');
	}

	public function arc()
	{
		$arc=$this->arc->arc(intval($_GET['id']));
		$c_info=$this->arc->channel($arc['cid']);//获得栏目的内容页模板		
		$tpl=PATH_ROOT.'/templates/'.$c_info['style'].'/content/'.$c_info['con_tpl'];
		$this->assign('arc',$arc);
		$this->display($tpl);
	}

	public function lists()
	{
		$c_info=$this->arc->channel(intval($_GET['cid']),'seo_desc,seo_keyword,style,list_tpl');//获得栏目的内容页模板
		$tpl=PATH_ROOT.'/templates/'.$c_info['style'].'/content/'.$c_info['list_tpl'];
		$this->assign('channel',$c_info);
		$this->display($tpl);
	}
	/**
	 * 单网页
	 */
	public function page()
	{
		$arc=$this->arc->arc('cid='.intval($_GET['id']));
		$c_info=$this->arc->channel($arc['cid']);//获得栏目的内容页模板		
		$tpl=PATH_ROOT.'/templates/'.$c_info['style'].'/content/'.$c_info['index_tpl'];
		$this->assign('content',$arc);
		$this->display($tpl);

	}
}