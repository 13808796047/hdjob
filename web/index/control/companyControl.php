<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-24
 * Describe   : 企业用户管理主页
 */

class companyControl extends myControl {

    private $company;
    private $recruit;
    private $resume;
    private $resume_model;
    private $email_activate;

    function __construct() {
        parent::__construct();
        $this->email_activate=K('email_activate');
        $this->company = K('company');
        $this->recruit = K('recruit');
        $this->resume = K('resume');
    }
    
    function index() {
        $company=$this->company->getCompanyDataById($_SESSION['uid'],'name,logo,license_verify');
        $_SESSION['company_name']=$company['name'];
        $_SESSION['license_verify']=$company['license_verify'];//

        $company['recruit_nums']=$this->company->recruitNums();

        $db=M('user');
        $u_info=$db->field('email_verify')->where('uid='.$_SESSION['uid'])->find();//查找email是否认证
        $company['email_verify']=$u_info['email_verify'];//Email认证

        $receives=$this->company->receiveNums();//收到的职位申请数目

        $delivers = $this->resume->receiveDelivers(array('company_id'=>$_SESSION['uid'],'is_view=0'));
        $this->assign('delivers',$delivers['deliver']);

        $this->assign('company', $company);
        $this->assign('receives', $receives);
        $this->display('company/index');
    }
    /*
     * 职位列表
     */
    function recruit() {
        $cond = array(
            'uid'=>$_SESSION['uid']
        );
        if(isset($_GET['state'])){
            $cond['state']=intval($_GET['state']);
        }
        $nums = isset($_GET['nums']) ? $_GET['nums'] : 15;
        $recruits = $this->company->recruitList($cond, $nums);
        $this->assign('point',abs(getPointRule('refresh_recruit')));
        $this->assign('recruits', $recruits);
        $this->display('company/recruit');
    }
    /**
     * 刷新职位
     */
    public function refreshRecruit()
    {
        $db=M('recruit');
        $cond='recruit_id='.intval($_POST['id']).' AND uid='.$_SESSION['uid'];
        $old=$db->field('recruit_name,refresh_date')->where($cond)->find();
        if(date('Y-m-d',$old['refresh_date'])==date('Y-m-d')){//已经刷新过了。
            $point=getPointRule('refresh_recruit');//获得应扣取积分
            if($_SESSION['point'] < abs($point)){
                $this->error('亲爱的用户，你的积分不够啦。请联系管理员充值');
            }
            if($db->where($cond)->update(array('refresh_date'=>time()))>=0){
                deductPoint($point);//扣取积分
                $con='刷新职位：--<a href="'.__APP__.'/search/jobs/id/'.intval($_POST['id']).'" target="_blank">'.$old['recruit_name'].'</a>';
                writeOptLog($con,$point);//写入日志
                echo 1;
                exit;
            }
        }else{
            if($db->where($cond)->update(array('refresh_date'=>time()))>=0){
                $con='刷新职位：--<a href="'.__APP__.'/search/jobs/id/'.intval($_POST['id']).'" target="_blank">'.$old['recruit_name'].'</a>';
                writeOptLog($con,0);//写入日志
                echo 1;
                exit;
            }
        }

        exit;
    }

    /**
     * 关闭招聘 
     */
    function closeRecruit() {
        if (isset($_POST['id'])) {
            $data = array(
                'state' => 0
            );
            $id=$_POST['id'];
            $cond=array(
                'uid'=>$_SESSION['uid']
            );
            if($this->company->recruit->where($cond)->in(array('recruit_id'=>$id))->update($data)>=0){
                echo 1;
                exit;
            }
        }
    }
    /**
     * 开启招聘
     */
    function enableRecruit() {
        if (isset($_POST['id'])) {
            $data = array(
                'state' => 1
            );
            $cond=array('uid'=>$_SESSION['uid'] );
            echo $this->company->recruit->where($cond)->in(array('recruit_id'=>$id))->update($data) >= 0;
            exit;
        }
    }

    //收到的投递简历
    function receiveApply() {
        $delivers = $this->resume->receiveDelivers('company_id='.$_SESSION['uid']);
        $this->assign('delivers',$delivers['deliver']);
        $this->assign('page',$delivers['page']);
        $this->display('company/receiveApply');
    }
    //设置用户投递的职位为已查看
    function setViewed(){
        if(!empty($_POST['id'])){
            $db=M('deliver');
            foreach ($_POST['id'] as $value) {
                $db->where(array('id'=>$value,'company_id'=>$_SESSION['uid']))->update(array('is_view'=>1));                
            }
            echo 1;
            exit;
        }
    }
    

    /**
     * 企业首页风格
     */
    function template() {
        $db = M('template');
        $company = $this->company->getCompanyDataById($_SESSION['uid'], 'tpl_style');
        $templates = $db->where('state=1 AND type=2')->findall();
        $this->assign('templates', $templates);
        $this->assign('company', $company);
        $this->display('company/template');
    }
    /**
     * 添加企业推广 
     */
    function addSpread() {
        $db = M('spread_cate');
        $cate_id = isset($_GET['cate']) ? intval($_GET['cate']) : 1;
        $cates = $db->find($cate_id);
        if (!$cates) {
            $cates = $db->find();
        }
        if($cates['state']==0){
            $this->error('对不起，系统关闭了该功能。你可以与网站管理员<a href="'.__WEB__.'/index/index/feedback/type/2">联系</a>。');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['days'] = intval($_POST['days']);
            $_POST['recruit_id'] = intval($_POST['recruit_id']);
            $_POST['starttime'] = time();
            $_POST['uid'] = $_SESSION['uid'];
            $_POST['endtime'] = strtotime($_POST['days'] . 'days', time());
            $_POST['cate_id'] = $cate_id;

            $point = $cates['cate_point'] * $_POST['days']; //总共扣取积分
            $db = M('spread');
            $has=$db->where(array('recruit_id'=>$_POST['recruit_id'],'cate_id'=>$cate_id,'uid'=>$_SESSION['uid'],'endtime'=>array('gt'=>time())))->find();//判断是否重复推广职位。
            if($has){
                $this->error('此职位正在推广中。');
            }
            if ($_SESSION['point'] < $point) {//积分不够
                $this->error('亲爱的用户，你的积分不够啦。先去<a href="">充值</a>吧！');
                exit;
            }
            $point*=-1;
            $result = $db->insert($_POST);
            if ($result) {
                $recruit_name=$db->table('recruit')->field('recruit_name')->where('recruit_id='.$_POST['recruit_id'])->find();
                deductPoint($point);//扣取积分
                $con=$cates['cate_name'].'：<a href="'.__APP__.'/search/jobs/id/'.$_POST['recruit_id'].'" target="_blank">'.$recruit_name['recruit_name'].'</a>，推广'.$_POST['days'].'天，(积分剩余：'.$_SESSION['point'].')';//操作说明
                writeOptLog($con,$point);//写入日志
                //记录用户日志
                $this->success('推广成功。', 'recruit');
            }
        }
        $condition = array(
            'uid' => $_SESSION['uid'],
            'expiration_time' => array(
                'gt' => time()
            )
        );
        $recruits = $db->table('recruit')->where($condition)->field('recruit_id,recruit_name')->findall();
        $this->assign('cates', $cates);
        $this->assign('recruits', $recruits);
        $this->display('company/addSpread');
    }

    /**
     * 企业资料 
     */
    function data() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            $cond = array('uid' => $_SESSION['uid']);
            if(empty($_POST['company_industry'])){
                $this->error('必须选择公司行业');
            }
            $_POST['name']=strip_tags($_POST['name']);
            $_POST['street']=strip_tags($_POST['street']);
            $_POST['contact_person']=strip_tags($_POST['contact_person']);
            $_POST['link_email']=strip_tags($_POST['link_email']);
            $_POST['contact_tel']=strip_tags($_POST['contact_tel']);
            $_POST['desc']=strip_tags($_POST['desc']);
            if ($this->company->updateCompanyData($_POST, $cond)>=0) {
                $this->success('更新资料成功', 'data');
            }
        }
        $company = $this->company->getCompanyDataById($_SESSION['uid']);
        $this->assign('company', $company);
        $this->display('company/data');
    }

    //设置企业主页风格
    function setStyle() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            $result = $this->company->updateCompanyData($_POST, array('uid' => $_SESSION['uid'])) >= 0;
            if ($result) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }
    }

    //修改简历
    function editRecruit() {
        $db = M('recruit');
        $cond = array(
            'uid' => $_SESSION['uid']
        );
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            unset($_POST['uid']);
            $cond['recruit_id'] = $_POST['id'];
            /* 发布时间 */
            if ($_POST['issue_type'] == '1') {
                $_POST['start_time'] = time();
            } else {
                $_POST['start_time'] = strtotime($_POST['start_time']);
            }
            /* 计算到期时间 */
            $_POST['expiration_time'] = $_POST['start_time'] + $_POST['effective_time'] * 24 * 60 * 60;
            if (empty($_POST['seo_desc'])) {//职位简要
                $_POST['seo_desc'] = mb_substr(strip_tags($_POST['job_desc']), 0, 60, 'UTF-8');
            }
            //处理SEO关键字（截取职位名称）
            $_POST['seo_keywords'] = implode(',', array_keys(string::split_word($_POST['recruit_name'])));
            $_POST['job_desc'] = htmlspecialchars($_POST['job_desc']);
            $_POST['company_desc'] = htmlspecialchars($_POST['company_desc']);
            $point=getPointRule('editRecruit');//获得应扣取积分
            if($_SESSION['point'] < abs($point)){
                $this->error('亲爱的用户，你的积分不够啦。请联系管理员充值');
            }
            deductPoint($point);////扣取积分
            $con='修改简历--<a href="'.__APP__.'/search/jobs/id/'.$_POST['id'].'" target="_blank">'.$_POST['recruit_name'].'</a> 
            花费：'.$point.'积分';
            writeOptLog($con,$point);//写入日志
            $result = $db->where($cond)->update($_POST) >= 0;
            $success = array('修改成功', 'index');
            $error = array('修改失败');
            $this->success_error($result, $success, $error);
        } else {
            $cond['recruit_id'] = $_GET['id'];
            $default = $db->where($cond)->find();
            $this->assign('field', $default);
            $this->display('company/editRecruit');
        }
    }

    /**
     * 企业发布招聘信息
     */
    function issueRecruit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['uid'] = $_SESSION['uid'];
            /* 发布时间 */
            if ($_POST['issue_type'] == '1') {
                $_POST['start_time'] = time();
            } else {
                $_POST['start_time'] = strtotime($_POST['start_time']);
            }
            /* 计算到期时间 */
            $_POST['expiration_time'] = $_POST['start_time'] + $_POST['effective_time'] * 24 * 60 * 60;
            if(C('VERIFY_RECRUIT')){//如果开启了审核职位:0未通过,1通过，2审核中
                $_POST['verify']=2;
            }else{
                $_POST['verify']=1;
            }
            if (empty($_POST['seo_desc'])) {//职位简要
                $_POST['seo_desc'] = mb_substr(strip_tags($_POST['job_desc']), 0, 80, 'UTF-8');
            }
            $field=new field(5);
            $_POST=$field->filterField($_POST);

            $_POST['created']=time();//添加时间
            //处理SEO关键字（截取职位名称）
            $_POST['seo_keywords'] = implode(',', array_keys(string::split_word($_POST['recruit_name'])));
            $_POST['refresh_date'] = $_POST['start_time'];
            $point=abs(getPointRule('issueRecruit'));//获得应扣取积分
            $point*=$_POST['effective_time'];
            if($_SESSION['point'] < $point){
                $this->error('亲爱的用户，你的积分不够啦。请联系管理员充值');
            }
            $result = $this->recruit->addRecruit($_POST);
            if ($result) {
                deductPoint(-$point);//扣取积分
                $con='发布一个职位信息--<a href="'.__APP__.'/search/jobs/id/'.$result.'" target="_blank">'
                .$_POST['recruit_name'].'</a> 有效期：'.$_POST['effective_time'].'天，共花费：'.$point.'积分。';
                writeOptLog($con,-$point);//写入日志
                $this->success('发布招聘成功', 'index');
            } else {
                $this->error('发布招聘失败');
            }
        }
        $this->display('company/issueRecruit');
    }
    /**
     * 删除招聘
     */
    function delRecruit(){
        if(isset($_POST['id'])){
            $db=M('recruit');
            $cond=array(
                'uid'=>$_SESSION['uid'],
            );
            if($db->where($cond)->in(array('recruit_id'=>$_POST['id']))->del()){
                echo 1;
                exit;
            }
            echo 0;
            exit;
        }
    }
    /**
     * 收藏简历
     */
    public function favorite()
    {
        $db = M('favorite');
        foreach ($_POST as $value) {
            if (!$db->where('recruit_id=' . $value[0])->count()) {
                $data = array(
                    'recruit_id' => $value[0],//简历ID
                    'company_name' => $value[1],//创建者
                    'created' => time(),//收藏时间
                    'company_id' => $_SESSION['uid'],//企业的ID
                    'job_name' => $value[2],//简历名称
                    'uid' => $value[3],//简历用户的ID
                    'type'=>2
                );
                $db->insert($data);
            }
        }
        echo json_encode(array('status' => 1, 'msg' => '简历收藏成功'));
        exit;
    }
    /**
     * 查看收藏简历
     */
    public function viewFav()
    {
        $db = M('favorite');
        $nums=$db->where('type=2 AND company_id='.$_SESSION['uid'])->count();
        $page=new page($nums,15);
        $favorites=$db->where('type=2 AND company_id='.$_SESSION['uid'])->order('created desc')->findall($page->limit());
        $this->assign('favorites',$favorites);
        $this->assign('page',$page->show());
        $this->display('company/viewFav');
    }
    /**
     * 删除收藏
     */
    public function delFav()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            $db = M('favorite');
            if($db->where('type=2 AND company_id='.$_SESSION['uid'])->in($_POST)->del()){
                echo 1;
                exit;
            }
        }
    }

    //操作日志
    function optLog(){
        $logs=$this->company->optLog('uid='.$_SESSION['uid']);
        $this->assign('logs',$logs['log']);
        $this->assign('page',$logs['page']);
        $this->display('company/optLog');
    }

    /**
     * 下载简历
     */
    function downloadResume(){
        $resume_id=intval($_GET['id']);
        $db=M('company_info');
        $com=$db->field('name')->where('uid='.$_SESSION['uid'])->find();
        $data=array(
            'company_name'=>$com['name'],
            'company_id'=>$_SESSION['uid'],
            'resume_id'=>$resume_id,
            'created'=>time()
        );
        $resume=$db->table('resume')->field('resume_name,uid')->find($resume_id);
        $data['resume_name']=$resume['resume_name'];
        $data['uid']=$resume['uid'];
        $point=getPointRule('download_resume');//获得应扣取积分
        if(abs($point)>$_SESSION['point']){
            $this->error('亲爱的用户，你的积分不够啦。请联系管理员充值。');
        }
        $result=$db->table('resume_download')->insert($data);
        if($result){
            deductPoint($point);////扣取积分
            $con='下载一份简历--<a href="'.__APP__.'/profile/viewResume/id/'.$resume_id.'" target="_blank">'.$data['resume_name'].'</a>';
            writeOptLog($con,$point);//写入日志
            go(__APP__.'/profile/viewResume/id/'.$_GET['id']);
        }
    }


    //和profile中的功能重复_buildAuthItem,_sendAuthEmail,reAuth
     private function _buildAuthItem($post,&$data){
        $activation_key = token();
        $data = array(
                'uid' => $_SESSION['uid'],
                'activation_key' => $activation_key,
                'created' => time(),
                'email' => $post['email']
        );
    }

     //发送验证邮件
    private function _sendAuthEmail($email,$activation_key){
        $mail = new mail();
        $body = getEmailTpl('authEmail',array('activate_url'=>__APP__.'/auth/authEmail/user/'.$_SESSION['uid'].'/key/'.$activation_key));
        if($mail->send($email, C('WEB_NAME') . '用户', $body['subject'], $body['content'])){
            return TRUE;
        }
        return FALSE;
    }

    function reAuth(){
        $this->email_activate->delAuthItem();//删除之先存入的验证信息
        $this->_buildAuthItem($_POST,$data);
        if ($this->_sendAuthEmail($_POST['email'],$data['activation_key']) && $this->email_activate->addAuthItem($data)) {
            $this->success('已成功发送激活邮件，请登录邮箱验证你的Email','proAuth');
        }else{
            $this->error('邮件发送失败，请与网站管理员联系','proAuth');
        }
    }
    /**
     * 修改密码
     */
    public function password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db=M('user');
            $userinfo=$db->find($_SESSION['uid']);
            if($userinfo['password']!=md5_d($_POST['old_pwd'])){
                $this->error('原始密码错误，修改用户密码失败。');
            }
            $db->validate = array(
                array("pwd", "length:5,21", "密码长度为6-20 ", 2),
                array("re_pwd", "confirm:pwd", "两次密码不一致 ", 2),
            );
            if(!$db->validate()){
                $this->error($db->error);
            }
            if($db->where('uid='.$_SESSION['uid'])->update(array('password'=>md5_d($_POST['pwd'])))>=0){
                session_destroy();
                $this->success('密码修改成功，请重新登录。','index');
            }
        }
        $this->display('company/password');
    }
    //Email认证
    function proAuth(){
        $user = K('user');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$user->emailExist($_POST['email'])) {//如果不存在
                $this->_buildAuthItem($_POST,$data);
                if ($this->_sendAuthEmail($_POST['email'],$data['activation_key']) && $this->email_activate->addAuthItem($data)) {
                    $this->success('已成功发送激活邮件，请登录邮箱验证你的Email','proAuth');
                }else{
                    $this->error('邮件发送失败，请与网站管理员联系','proAuth');
                }
            } else {
                $this->error('Email已经存在','proAuth');
            }
            return false;
        }
        $db = M('user');
        $verify = FALSE; //是否已经验证通过
        $activating=FALSE;//是否正在激活
        $info = $db->field('email,email_verify')->where('uid=' . $_SESSION['uid'])->find();
        $activate_info=$db->table('email_activate')->where('uid='.$_SESSION['uid'])->find();
        if($activate_info){
            $activating=TRUE;//已经发送验证邮件但是未验证。
        }
        $email = $info['email'];
        if ($info['email_verify']==1) {
            $verify = TRUE;
        }
        $this->assign('verify', $verify);
        $this->assign('email', $email);
        $this->assign('activating', $activating);
        $this->display('company/proAuth');
    }
    //营业执照认证
    function account(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            C('UPLOAD_IMG_DIR','');
            C('WATER_ON',false);
            C('THUMB_ENDFIX','');//只生成头像缩略图，不保存原图
            $data=array(
                'license_verify'=>2//正在审核中
            );
            $upload=new upload('uploads/company_license',array('jpg','png','gif'),2465792,0,1,array(520,300),5);
            $up_info=$upload->upload();
            if($up_info){
                $data['license']=$up_info['0']['thumb'];
                $this->company->updateCompanyData($data,'uid='.$_SESSION['uid']);//更新企业营业执照为正在审核中
                $this->success('营业执照上传成功，请等待管理员的审核！');
            }else{
                $this->error($upload->error,'licenseAuth');
            }
        }
        $license=$this->company->getCompanyDataById($_SESSION['uid'],'license,license_verify');
        $this->assign('license',$license);
        $this->display('company/account');
    }
    /**
     * 企业logo
     */
    public function logo()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            C('UPLOAD_IMG_DIR','');//图片没有附文件夹
            C('THUMB_ENDFIX','');//只生成头像缩略图（即覆盖原文件）
            $upload=new upload(PATH_ROOT.'/uploads/company_logo',array('jpg','jpeg','png','gif'),3145728,0,1,array(100,100,4));
            $up_info=$upload->upload();
            if($up_info){
                $this->company->updateCompanyData(array('logo'=>$up_info['0']['thumb']),'uid='.$_SESSION['uid']);//更新企业营业执照为正在审核中
                $this->success('企业LOGO修改成功！');
            }else{
                $this->error($upload->error);
            }
        }
        $info=$this->company->getCompanyDataById($_SESSION['uid'],'logo');
        $this->assign('info',$info);
        $this->display('company/logo');
    }

    //邀请面试
    function interView(){
        $db=M('interview');
        $interviews=$db->where('company_id='.$_SESSION['uid'])->order('created DESC')->findall();
        $this->assign('interviews',$interviews);
        $this->display('company/interView');
    }
    //发起面试邀请(供查看简历时ajax调用)
    function addInterview(){
        $_POST['created']=time();
        $_POST['company_id']=$_SESSION['uid'];
        $company=$this->company->getCompanyDataById($_SESSION['uid'],'name');
        $_POST['company_name']=$company['name'];
        $db=M('interview');
        $con='created >'.strtotime(date('Y-m-d')).' AND recruit_id='.$_POST['recruit_id'].' AND uid='.$_POST['uid'];
        if($db->where($con)->count()){//如果今天已经邀请面试，则不添加数据
            echo 1;
            exit;
        }
        $point=getPointRule('interview');//获得应扣取积分
        if($_SESSION['point'] < abs($point)){
                $this->error('亲爱的用户，你的积分不够啦。请联系管理员充值');
        }
        $result=$db->insert($_POST);
        if($result){
            deductPoint($point);////扣取积分
            $con='邀请用户'.$_POST['username'].'面试 <a href="'.__WEB__.'/index/search/jobs/id/'.$_POST['recruit_id'].'" target="_blank">'.$_POST['recruit_name'].'</a>';
            writeOptLog($con,$point);//写入日志
            echo 1;
            exit;
        }
        echo 0;
        exit;
    }
    /**
     * 招聘列表，供邀请面试时ajax调用 
     */
    function recruitItem(){
        echo json_encode($this->company->recruitItem());
        exit;
    }

}
