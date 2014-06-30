<?php
 /*
  * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
  * Link       : http://www.houdunwang.com
  * Encoding   : UTF-8
  * Author     : 张博
  * Email      : zhangbo1248@gmail.com
  * Created    : 2012-10-01
  * Describe   : 文章、栏目管理
  */

class contentControl extends myControl{
	private $channel;
	private $arc;
	function __construct(){
		parent::__construct();
		$this->channel=M('channel');
		$this->arc=K('arc');
	}
	private function _getChannelTpl()
	{
		$dirs=(dir::tree_dir(PATH_ROOT.'/templates/'));
		$tpl=array();
		foreach ($dirs as $value) {
			if(file_exists($value['dirpath'].'/config.php')){
				$config=include $value['dirpath'].'/config.php';
				array_push($tpl, array('name'=>$config['name'],'dirname'=>$config['dirname']));
			}
		}
		return $tpl;
	}
	public function channel()
	{
		if($_SERVER['REQUEST_METHOD']=='POST'){
			//如果是单网页并且生成html
			//如果栏目生成html
			if($_POST['setting']['cate_html'] || ($_POST['type']==2 && $_POST['setting']['con_html'])){
				$_POST['href']=__ROOT__.'/html/'.$_POST['pinyin'];
			}
			$_POST['setting']=json_encode($_POST['setting']);
			if($id=$this->channel->insert()){
				if($_POST['type']==2){//如果是单网页，添加内容到文章表
					$arc=array(
						'title'=>$_POST['title'],
						'cid'=>$id,//栏目id
					);
					if($_POST['setting']['con_html']){//如果内容生成HTML
						$arc['href']=__ROOT__.'/html/'.$_POST['pinyin'];	
					}
					$page_id=$this->arc->addArc($arc);
					if(!$_POST['setting']['con_html']){
						$page_href=array(
							'href'=>__WEB__.'/content/index/page/id/'.$page_id
						);
						$this->arc->updateArc('cid='.$page_id,$page_href);
					}
				}
				$data=array();
				if(!$_POST['setting']['cate_html']){//如果没有开启生成静态,则url映射到控制器中
					$data['href']=__WEB__.'/content/index/lists/cid/'.$id;
				}
				if($_POST['type']==2 && !$_POST['setting']['con_html']){
					$data['href']=__WEB__.'/content/index/page/id/'.$page_id;
				}
				if($_POST['pid']==0){//处理栏目path
					$data['path']=$id;
				}else{
					$pid=$this->channel->field('path')->where('id='.$_POST['pid'])->find();//查找pid,path值
					$data['path']=$pid['path'].','.$id;
				}
				$this->arc->updateChannel('id='.$id,$data);
				$this->success("添加栏目成功");
			}
		}
		$type=array(
			1=>'分类栏目',
			2=>'单网页'
		);
		$channels=$this->arc->channels();
		$this->assign('channels',$channels);
		$this->assign('type',$type);
		$this->assign('tpl',$this->_getChannelTpl());
		$this->display();
	}
	//获取栏目模板
	public function getChannelTpl($json=true)
	{
		$ctpl=include PATH_ROOT.'/templates/'.$_GET['style'].'/config.php';
		$info=$ctpl['folder']['/content'];
		$info_arr=array(
			'index'=>'<option value="">请选择</option>',
			'list'=>'<option value="">请选择</option>',
			'content'=>'<option value="">请选择</option>',
			'page'=>'<option value="">请选择</option>'
		);
		foreach ($info as $key => $value) {
			$prefix=substr($key, 0,4);
			switch ($prefix) {
				case 'inde':
					$info_arr['index'].='<option value="'.$key.'">'.$key.' | '.$value.'</option>';
					break;
				case 'list':
					$info_arr['list'].='<option value="'.$key.'">'.$key.' | '.$value.'</option>';
					break;
				case 'cont':
					$info_arr['content'].='<option value="'.$key.'">'.$key.' | '.$value.'</option>';
					break;
				case 'page':
					$info_arr['page'].='<option value="'.$key.'">'.$key.' | '.$value.'</option>';
				break;
			}
		}
		if($json){
			echo json_encode($info_arr);
			exit;
		}else{
			return $info_arr;
		}
	}
	function editChannel(){
		$cond=array(
			'id'=>$_GET['id']
		);
		if ($_SERVER['REQUEST_METHOD']=='POST') {
			if(isset($_POST['setting']['cate_html']) && $_POST['setting']['cate_html']){//处理栏目url
				$_POST['href']='/html/'.$_POST['pinyin'];
			}else{
				$_POST['href']='/content/index/lists/cid/'.$_GET['id'];//动态读取
			}
			if(isset($_POST['type']) && $_POST['type']==2){//如果是单网页
				if($_POST['setting']['con_html']){//如果内容生成HTML
					$page_href=array('href'=>'/html/'.$_POST['pinyin']);	
					$_POST['href']='/html/'.$_POST['pinyin'];
				}
				$page_id=$_GET['id'];
				if(!$_POST['setting']['con_html']){
					$page_href=array(
						'href'=>'/content/index/page/id/'.$page_id
					);
					$_POST['href']='/content/index/page/id/'.$page_id;
				}
				$this->arc->updateArc('cid='.$page_id,$page_href);
			}
			$_POST['setting']=json_encode($_POST['setting']);

			if($_POST['pid']==0){//处理栏目path
				$_POST['path']=$_GET['id'];
			}else{
				$pid=$this->arc->channel('id='.$_POST['pid'],'path');//查找pid的path值
				$_POST['path']=$pid['path'].','.$_GET['id'];
			}
			if($this->arc->updateChannel($cond,$_POST)){
				go('channel');
			}
		}
		$channel=$this->arc->channel($cond);
		$channel['setting']=json_decode($channel['setting'],true);
		$_GET['style']=$channel['style'];
		$tpl_s=$this->getChannelTpl(false);
		$channels=$this->arc->channels(array(),'title,id,pid,path');
		$this->assign('channels',$channels);
		$this->assign('channel',$channel);
		$this->assign('tpl',$this->_getChannelTpl());
		$this->assign('tpl_s',$tpl_s);
		$this->display();
	}
	function delChannel(){
		if($this->arc->delChannel($_POST['id'])){
			echo 1;
			exit;
		}
	}
	public function sortChannel()
	{
		foreach ($_POST['sort'] as $key => $value) {
			$this->arc->updateChannel('id='.$key,array('sort'=>$value));
		}
		go('channel');
	}
	public function arcList()
	{
		$channels=$this->channel->field('id,pid,title,type')->findall();
		$this->assign('channels',formatLevelData2($channels,array('id','pid')));
		$this->display();
	}
	//栏目的全部文章列表
	public function channelArc()
	{		
		$cond=array('cid'=>$_GET['cid']);
		$field='article.id,article.title,updated,article.state,cid,author,username,channel.title|channel_name,article.href';
		$arcs=$this->arc->arcs($cond,$field);
		$this->assign('arcs',$arcs['arc']);
		$this->assign('page',$arcs['page']);
		$this->display();
	}
	//单网页
	public function page()
	{
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if(empty($_POST['keywords'])){//生成关键字
				$_POST['keywords']=implode(',',array_keys(string::split_word($_POST['title'])));
			}
			if(empty($_POST['summary'])){//生成描述
				$_POST['summary']=mb_substr(strip_tags($_POST['contents']),0,100,'utf-8');
			}else{
				$_POST['summary']=strip_tags($_POST['summary']);
			}
			if($this->arc->updateArc('id='.$_GET['id'],$_POST)){
				$channel=$this->arc->channel('id='.$_GET['cid'],'setting,pinyin,style,con_tpl,index_tpl,href');
				$channel['setting']=json_decode($channel['setting'],true);
				if($channel['setting']['con_html']){//如果生成文章静态HTML
					$tpl=PATH_ROOT.'/templates/'.$channel['style'].'/content/'.$channel['index_tpl'];//内容页模板
					$path=PATH_ROOT.'/html/'.$channel['pinyin'];
					$this->generate_page_html($_GET['id'],$path,$tpl);//更新单网页
				}
				$this->success('网页更新成功！');
			}
		}
		$arc=$this->arc->arc('cid='.$_GET['cid']);
		$this->assign('arc',$arc);
		$this->display();
	}

	/**
	 * 生成单网页模板
	 */
	public function generate_page_html($id,$path,$tpl)
	{
		$content=$this->arc->arc('cid='.$id,'contents,title,keywords|seo_keywords,summary|seo_desc');
		$this->assign('content',$content);
		$content=$this->display($tpl,0,'text/html','utf-8',false);
		if(!file_exists($path)){
			dir_create($path);
		}
		$path.="/index.html";
		file_put_contents($path, $content);
	}
	//更新列表页静态html
	public function updateList()
	{
		$channel=$this->arc->channel('id='.$_GET['cid']);
		$channel['setting']=json_decode($channel['setting'],true);
		if($channel['setting']['cate_html']){//如果列表页生成html
			$p_num=15;//每页数目
			$list_tpl=PATH_ROOT.'/templates/'.$channel['style'].'/content/'.$channel['list_tpl'];//列表页模板
			$this->assign('channel',$channel);
			$content=$this->display($list_tpl,0,'text/html','utf-8',false);
			$content=preg_replace("#".__METH__."/cid/{$_GET['cid']}/page/(\d+)#", __ROOT__.$channel['href'].'/index_'.'$1'.'.html',$content);
			$content=str_replace("<a class='now'>1</a>",'<a href="'.__ROOT__.$channel['href'].'/index.html">1</a>' , $content);
			echo $content;
			exit;
		}
	}

	public function delArc()
	{
		$arcs=$this->arc->arc->field('href,id')->in($_POST)->findall();
		foreach ($arcs as $value) {
			if(substr($value['href'],0,5)=='/html'){
				unlink(PATH_ROOT.$value['href']);//删除静态文件
			}
			$this->arc->delArc($value['id']);
		}
		echo 1;
		exit;
	}
	//关闭文章
	public function closeArc()
	{
		foreach ($_POST['id'] as $value) {
			$this->arc->updateArc('id='.$value,array('state'=>0));
		}
		echo 1;
		exit;
	}
	public function enableArc()
	{
		foreach ($_POST['id'] as $value) {
			$this->arc->updateArc('id='.$value,array('state'=>1));
		}
		echo 1;
		exit;
	}
	//修改文章
	public function editArc()
	{
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if(empty($_POST['keywords'])){//生成关键字
				$_POST['keywords']=implode(',',array_keys(string::split_word($_POST['title'])));
			}
			
			if(empty($_POST['summary'])){//生成描述
				$_POST['summary']=mb_substr(strip_tags($_POST['contents']),0,100,'utf-8');
			}else{
				$_POST['summary']=strip_tags($_POST['summary']);
			}
			$_POST['created']=strtotime($_POST['created']);
			$_POST['updated']=time();

			if($this->arc->updateArc('id='.$_GET['id'],$_POST)){
				//更新静态页
				$this->_decide_generate_html($_POST['cid'],$_GET['id']);
				$this->success('文章更新成功！');
			}
		}
		$channels=$this->arc->channels(array(),'title,id,pid,path');
		$this->assign('channels',$channels);
		$arc=$this->arc->arc('id='.$_GET['id']);
		$this->assign('arc',$arc);
		$this->display();
	}
	/**
	 * 判断是否需要生成HTML,如果需要则添加或更新html
	 * @param int $cid 栏目ID
	 * @param int $id  文章ID
	 * @param bool 是否需要生成内容静态页
	 */
	private function _decide_generate_html($cid,$arc_id)
	{
		$channel=$this->arc->channel('id='.$cid,'setting,pinyin,style,con_tpl');
		$channel['setting']=json_decode($channel['setting'],true);
		if($channel['setting']['con_html']){//如果生成文章静态HTML
			$path="/html/{$channel['pinyin']}/".date('Ymd',$_POST['created']);
			$tpl=PATH_ROOT.'/templates/'.$channel['style'].'/content/'.$channel['con_tpl'];//内容页模板
			$this->generate_arc_html($arc_id,$path,$tpl);//添加或更新静态html
			return array('path'=>$path,'tpl'=>$tpl);
		}
	}

	//添加文章
	public function addArc()
	{
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if(empty($_POST['keywords'])){//生成关键字
				$_POST['keywords']=implode(',',array_keys(string::split_word($_POST['title'])));
			}
			if(empty($_POST['summary'])){//生成描述
				$_POST['summary']=mb_substr(strip_tags($_POST['contents']),0,100,'utf-8');
			}else{
				$_POST['summary']=strip_tags($_POST['summary']);
			}
			if(empty($_POST['created'])){//处理创建时间
				$_POST['created']=time();
			}else{
				$_POST['created']=strtotime($_POST['created']);
			}
			$_POST['uid']=$_SESSION['uid'];
			$_POST['updated']=$_POST['created'];
			$data=array();
			if($id=$this->arc->addArc($_POST)){
				// $channel=$this->arc->channel('id='.$_POST['cid'],'setting,pinyin,style,con_tpl');
				// $channel['setting']=json_decode($channel['setting'],true);
				// if($channel['setting']['con_html']){//如果生成文章静态HTML
				// 	$path="/html/{$channel['pinyin']}/".date('Ymd',$_POST['created']);
				// 	$tpl=PATH_ROOT.'/templates/'.$channel['style'].'/content/'.$channel['con_tpl'];//内容页模板
				// 	$this->generate_arc_html($id,$path,$tpl);
				// 	$data['href']=$path."/arc_{$id}.html";
				// }else{
				// 	$data['href']='/content/index/arc/id/'.$id;
				// }
				if($c_info=$this->_decide_generate_html($_POST['cid'],$id)){
					$data['href']=__ROOT__.$c_info['path']."/arc_{$id}.html";
				}else{
					$data['href']=__WEB__.'/content/index/arc/id/'.$id;
				}
				$this->arc->updateArc('id='.$id,$data);//更新文章的href
				$this->success('添加文章成功！',__CONTROL__.'/channelArc/cid/'.$_POST['cid']);
			}
		}
		$channels=$this->arc->channels(array(),'title,id,pid,path');
		$this->assign('channels',$channels);
		$this->display();
	}
	/**
	 * 生成文章静态HTML
	 * @param int $id 文章ID
	 * @param string $path 文章生成路径
	 * @param string $tpl  文章内容页的模板
	 */
	public function generate_arc_html($id,$path,$tpl)
	{
		$path=PATH_ROOT.$path;
		$arc=$this->arc->arc('id='.$id);
		$this->assign('arc',$arc);
		$content=$this->display($tpl,0,'text/html','utf-8',false);
		if(!file_exists($path)){
			dir_create($path);
		}
		$path.="/arc_{$id}.html";
		file_put_contents($path, $content);
	}

	/**
	 * 更新单页
	 */
	public function updatePage()
	{
		$db=M('channel');
		$pages=$db->where('type=2 and href!=""')->findall();
		foreach ($pages as $value) {
			$setting=json_decode($value['setting'],true);
			if($setting['con_html']){//如果单网页生成静态
				$tpl=PATH_ROOT.'/templates/'.$value['style'].'/content/'.$value['index_tpl'];//内容页模板
				$path=PATH_ROOT.'/html/'.$value['pinyin'];
				echo '正在更新:'.$value['title'].'<br />';
				$this->generate_page_html($value['id'],$path,$tpl);
			}
		}
		$this->success('单网页更新成功。','channel');
	}


	public function feedback()
	{
		$type=array(
            1=>'建议',
            2=>'咨询',
            3=>'举报',
            4=>'求助'
        );
        $this->assign('type',$type);
		$db=M('feedback');
		$nums=$db->count();
		$page=new page($nums,15);
		$fbs=$db->order('process')->findall($page->limit());
		$this->assign('fbs',$fbs);
		$this->assign('type',$type);
		$this->assign('page',$page->show());
		$this->display();
	}
	public function replyFeedback()
	{
		$db=M('feedback');
		$db->in($_POST)->update(array('process'=>1));
		echo 1;
		exit;
	}
	public function delFeedback()
	{
		$db=M('feedback');
		$db->in($_POST)->del();
		echo 1;
		exit;
	}
}