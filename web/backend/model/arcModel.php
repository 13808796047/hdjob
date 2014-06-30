<?php
 /*
  * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
  * Link       : http://www.houdunwang.com
  * Encoding   : UTF-8
  * Author     : 张博
  * Email      : zhangbo1248@gmail.com
  * Created    : 2012-10-01
  * Describe   : 文章、栏目模型
  */
class arcModel extends Model
{
	public $arc;
	public $channel;
	public $arc_v;
	function __construct()
	{
		$this->arc=M('article');
		$this->channel=M('channel');
		$this->arc_v=V('article');
		$this->arc_v->view=array(
			'channel'=>array(
				'type'=>'inner',
				'on'=>'article.cid=channel.id',
			),
			'user'=>array(
				'type'=>'inner',
				'on'=>'article.uid=user.uid'
			)
		);
	}

	/**
	 * 取得栏目列表
	 * @param  mixed  $cond 条件
	 * @param  string $field 取出字段
	 * @param  string $order 排序方式
	 * @return array  栏目数组
	 */
	public function channels($cond=array(),$field=array(),$order='path,sort')
	{
		return $this->channel->field($field)->where($cond)->order($order)->findall();
	}
	/**
	 * 取得某个栏目信息
	 * @param mixed $cond 条件，一般为栏目ID
	 */
	public function channel($cond,$field=array())
	{
		return $this->channel->field($field)->where($cond)->find();
	}
	/**
	 * 	取得栏目的所有子栏目ID。
	 *	@param 	int	$cid 栏目ID
	 *	@return array 子栏目数组
	 */
	public function getAllSonCid($cid,$field='id')
	{
		// $id=array($cid);
		// $s_cid=$this->channel->field($field)->where('pid='.$cid)->findall();
	}

	/**
	 * 更新栏目信息
	 * @param mixed $cond 条件
	 * @param array $data 更新数据
	 */
	public function updateChannel($cond,$data)
	{
		return $this->channel->where($cond)->update($data)>=0;
	}

	/**
	 * 删除栏目
	 * @param int $cid 栏目id
	 */
	public function delChannel($cid)
	{
		return $this->channel->where("id=$cid OR pid=$cid")->del();
	}

	/**
	 * 添加文章
	 * @param array $data 文章数据
	 */
	function addArc($data)
	{
		return $this->arc->insert($data);
	}
	/**
	 * 所有文章
	 */
	public function arcs($cond=array(),$field='')
	{
		$nums=$this->arc->where($cond)->count();
		$page=new page($nums,6);
		$arc=array();
		$arc['arc']=$this->arc_v->where($cond)->field($field)->findall($page->limit());//文章列表
		$arc['page']=$page->show();//分页列表
		return $arc;
	}
	/**
	 * 文章内容
	 */
	public function arc($cond)
	{
		return $this->arc->where($cond)->find();
	}

	public function delArc($cond)
	{
		return $this->arc->in($cond)->del();
	}
	//更新文章
	public function updateArc($cond,$data)
	{
		$a=$this->arc->where($cond)->update($data)>=0;
		return $a;
	}
}
