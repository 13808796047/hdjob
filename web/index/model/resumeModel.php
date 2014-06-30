<?php
class resumeModel extends Model{

	public $resume;
	public $resume_basic;
	public $resume_edu;
	public $resume_append;
	public $work_exp;
	public $interview;
	public $favorite;
	public $deliver;

	function __construct(){
		$this->resume=M('resume');
		$this->resume_basic=M('resume_basic');
		$this->resume_edu=M('resume_edu');
		$this->resume_append=M('resume_append');
		$this->work_exp=M('work_exp');
		$this->interview=M('interview');
		$this->favorite=M('favorite');
		$this->deliver=M('deliver');
	}
	function getResume($cond,$field=array()){
		return $this->resume->field($field)->where($cond)->find();
	}
	/**
	 * 获取所有的简历
	 */
	public function getResumes($cond)
	{
		$resumes=array();
		$db=V('resume');
        $db->view=array(
            'template'=>array(
                'type'=>'inner',
                'on'=>'resume.style=template.dir_name',
                'field'=>'name|style_name'
            )
        );
        $nums=$db->where($cond)->count();
        $page=new page($nums,15);
        $resumes['resumes']=$db->where($cond)->findall($page->limit());
        $resumes['page']=$page->show();
        return $resumes;
	}
	function getResumeBasic($cond){
		return $this->resume_basic->where($cond)->find();
	}
	function getResumeExp($cond){
		return $this->work_exp->where($cond)->find();
	}
	function getResumeEdu($cond){
		return $this->resume_edu->where($cond)->find();
	}
	function getResumeAppend($cond){
		return $this->resume_append->where($cond)->find();
	}
	public function incViews($cond)
	{
		$this->resume->inc('views',$cond,1);
	}
	/**
	 * 更新简历
	 * @param string $table 更新表名
	 * @param mixed  $cond 	更新条件
	 * @param array  $data 	更新数据
	 */
	public function updateResume($table,$cond,$data)
	{
		return $this->$table->where($cond)->update($data)>=0;
	}
	public function interViewNums($cond)
	{
		return $this->interview->where($cond)->count();
	}
	public function favoriteNums($uid)
	{
		return $this->favorite->where('uid='.$uid.' AND type=1')->count();
	}
	public function deliverNums($uid)
	{
		return $this->deliver->where('uid='.$uid)->count();
	}
	/**
	 * 获得用户的简历数量
	 */
	public function resumeNums($uid)
	{
		return $this->resume->where('uid='.$uid)->count();
	}
	/**
	 * 查看面试邀请
	 */
	public function interview($cond,$field=array())
	{
		return $this->interview->field($field)->where($cond)->findall();
	}
	/**
	 * 企业收到的投递简历
	 */
	public function receiveDelivers($cond,$field='',$order='sendtime desc')
	{
		$data=array();
		$db = V('deliver');
        $db->view=array(
            'resume_basic'=>array(
            'type'=>"INNER",
            'on'=>'deliver.resume_id=resume_basic.resume_id',
            'field'=>'name'
            )
         );
        $nums=$db->field($field)->where($cond)->count();
        $page=new page($nums,13);
        $data['deliver']=$db->field($field)->where($cond)->order($order)->findall($page->limit());
        $data['page']=$page->show();
        return $data;
	}
}
?>