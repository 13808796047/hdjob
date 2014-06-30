<?php
class email_activateModel extends Model{
	public $email_activate;

	function __construct(){
		$this->email_activate=M('email_activate');
	}
	//删除已存在的验证数据
    function delAuthItem($uid=array()){
    	if(empty($uid)){
    		$uid=$_SESSION['uid'];
    	}
        $db=M('email_activate');
        return $db->where('uid='.$uid)->del();
    }
    //添加验证信息
    function addAuthItem($data){
    	return $this->email_activate->insert($data);
    }

    function getAuthItem($con){
    	return $this->email_activate->where($con)->find();
    }
    function authEmailSuccess($uid,$email){
    	$db=M('user');
    	return $db->where('uid='.$uid)->update(array('email'=>$email,'email_verify'=>1));
    }
}
?>