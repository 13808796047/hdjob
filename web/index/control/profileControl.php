<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-24
 * Describe   : 
 */

class profileControl extends Control {

    private $auth;
    private $email_activate;
    private $user;
    private $resume;

    function __construct() {
        $this->email_activate=K('email_activate');
        $this->auth = new auth;
        if (!$this->auth->is_logged_in()) {
            if (ajax_request()) {
                echo json_encode(array('status' => 0, 'msg' => '请登录后操作'));
                exit;
            } else {
                $this->error(L('please_login'), 'index/auth/index');
            }
        }
        if (!$this->auth->check_uri_permissions()) {
            $this->error($this->auth->error);
        }
        $this->user=K('user');
        $this->resume=K('resume');
    }

    function index() {
        $user=$this->user->userInfo_V($_SESSION['uid'],'name,email_verify,avatar');
        $user['resumeNums']=$this->resume->resumeNums($_SESSION['uid']);
        $user['interviews']=$this->resume->interViewNums('uid='.$_SESSION['uid'].' AND created>='.strtotime(date('Y-m-d')));
        $user['favorites']=$this->resume->favoriteNums($_SESSION['uid']);
        $user['delivers']=$this->resume->deliverNums($_SESSION['uid']);
        $interviews=$this->resume->interview(array());
        $this->assign('interviews',$interviews);
        $this->assign('user',$user);
        $this->display('profile');
    }

    /*
     * 创建新简历
     */

    function createNewRes() {
        $db = M('template');
        $style = $db->field('name,dir_name,point')->where('type=1 AND state=1')->findall();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['resume_name']=strip_tags($_POST['resume_name']);
            $_POST['uid'] = $_SESSION['uid'];
            $_POST['created'] = time(); //简历创建时间
            $_POST['updated'] = $_POST['created']; //简历更新时间
            if($_POST['avatar_type']==0){//不使用头像
                $_POST['avatar']='';
            }
            if($_FILES['avatar']['error']==0){//有头像被上传，使用上传的头像
                C('UPLOAD_IMG_DIR','');
                C('THUMB_ENDFIX','');//只生成头像缩略图
                $upload=new upload(PATH_ROOT.'/uploads/resume_avatars',array('jpg','jpeg','png','gif'),3145728,0,1,array(100,100,4));
                $info=$upload->upload();
                if($info){
                    $_POST['avatar']=$info[0]['thumb'];
                }
            }
            if(C('VERIFY_RESUME')){//如果开启了审核职位:0未通过,1通过，2审核中
                $_POST['verify']=2;
            }else{
                $_POST['verify']=1;
            }
            $id = $db->table('resume')->insert($_POST);
            if ($id) {
                go(__CONTROL__ . '/createResume/resume_id/' . $id);
            }
        }
        //缺少简历数目判断。
        $db=M('user_info');
        $info=$db->field('avatar')->where('uid='.$_SESSION['uid'])->find();
        $this->assign('avatar', $info['avatar']);
        $this->assign('style', $style);
        $this->display('profile/createNewRes');
    }

    /**
     * 创建简历基本资料 
     */
    function createResume() {
        $db = M('user_info');
        $field = $db->where('uid=' . $_SESSION['uid'])->find();
        $this->assign('field', $field);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['uid'] = $_SESSION['uid'];
            $_POST['work_type'] = implode('#', $_POST['work_type']);
            $field=new field(6);
            $_POST=$field->filterField($_POST);
            $result = $db->table('resume_basic')->insert($_POST);
            if ($result) {
                go(__CONTROL__ . '/createResumeEdu/resume_id/' . $_POST['resume_id']);
            }
        }
        $this->display('profile/createResume');
    }

    /**
     * 教育和工作 
     */
    function createResumeEdu() {
        $db = M('resume_edu');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $field=new field(7);
            $_POST=$field->filterField($_POST);
            $field=new field(12);
            $_POST=$field->filterField($_POST);
            parse_str($_POST['resume_edu'], $_POST['resume_edu']);
            $_POST['uid'] = $_SESSION['uid'];
            $_POST['job_desc'] = strip_tags($_POST['job_desc']);
            $_POST['job_start'] = strtotime($_POST['job_start']);
            $_POST['job_end'] = strtotime($_POST['job_end']);
            $resume_edu = $_POST['resume_edu'];
            $resume_edu['edu_start'] = strtotime($resume_edu['edu_start']);
            $resume_edu['edu_end'] = strtotime($resume_edu['edu_end']);
            $resume_edu['resume_id'] = $_POST['resume_id'];
            $resume_edu['uid'] = $_SESSION['uid'];
            $db->insert($resume_edu);
            unset($_POST['resume_edu']);
            $result = $db->table('work_exp')->insert($_POST);
            if ($result) {
                go(__CONTROL__ . '/createResumeAppend/resume_id/' . $_POST['resume_id']);
            }
        }
        $this->display('profile/createResumeEdu');
    }

    /**
     * 附加信息 
     */
    function createResumeAppend() {
        $db = M('resume_append');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['uid'] = $_SESSION['uid'];
            $_POST['content'] = strip_tags($_POST['content']);
            $result = $db->insert($_POST);
            if ($result) {
                $this->success('新建简历成功！', __CONTROL__ . '/resume');
            }
        }
        $this->display('profile/createResumeAppend');
    }

    /**
     * 管理简历 
     */
    function resume() {
        $resumes=$this->resume->getResumes('uid=' . $_SESSION['uid'].' AND template.type=1');
        $this->assign('resumes', $resumes['resumes']);
        $this->assign('page', $resumes['page']);
        $this->display('profile/resume');
    }
    /**
     * 刷新简历
     */
    public function refreshResume()
    {
        $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_GET['id']));
        $resume=$this->resume->getResume($cond,'updated');
        if($resume['updated']>=strtotime(date('Y-m-d'))){
            $this->error('对不起，你今天已经刷新过简历了，明天再来吧！');
        }
        if($this->resume->updateResume('resume',$cond,array('updated'=>time()))){
            $this->success('恭喜你，简历刷新成功！');
        }
    }

    function del() {
        $db = M('resume');
        $condition = 'resume_id=' . intval($_POST['id']) . ' AND uid=' . $_SESSION['uid'];
        if ($db->where($condition)->del()) {
            $db->table('resume_basic')->where($condition)->del();
            $db->table('resume_edu')->where($condition)->del();
            $db->table('work_exp')->where($condition)->del();
            $db->table('resume_append')->where($condition)->del();
            echo 1;
            exit;
        }
        echo 0;
        exit;
    }

    /**
     * 删除职位收藏 
     */
    function delFav() {
        $db = M('favorite');
        if ($db->where('uid=' . $_SESSION['uid'] . ' AND id=' . $_POST['id'])->del()) {
            echo 1;
            exit;
        }
        echo 0;
        exit;
    }

    function viewResume() {
        if(in_array(3, $_SESSION['role']['rid'])){//企业查看用户简历
            $cond = 'resume_id=' . intval($_GET['id']);
        }else{
            $cond = 'resume_id=' . $_GET['id'] . ' AND uid=' . $_SESSION['uid'];
        }
        $this->resume->incViews($cond);//增加查看次数
        $resume = array();
        $resume['resume'] = $this->resume->getResume($cond);
        if ($resume['resume']) {
            $data = new data('resume_basic');
            $resume['basic'] = $data->convert($this->resume->getResumeBasic($cond));

            $data = new data('resume_edu');
            $resume['edu'] = $data->convert($this->resume->getResumeEdu($cond));

            $data = new data('work_exp');
            $resume['exp'] = $data->convert($this->resume->getResumeExp($cond));

            $resume['append'] = $this->resume->getResumeAppend($cond);
            $point = abs(getPointRule('download_resume'));
            $this->assign('resume', $resume);
            $this->assign('point', $point);
            $this->display(PATH_ROOT . '/templates/resume_tpl/' . $resume['resume']['style'] . '/resume');
        } else {
            echo '未找到简历信息!';
        }
    }
    /**
     * 修改简历信息()
     */
    function editResumeInfoBasic(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            $_POST['work_type'] = implode('#', $_POST['work_type']);
            $db=M('resume_basic');
            $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_POST['resume_id']));
            if(!$db->where($cond)->count()){
                $_POST['uid'] = $_SESSION['uid'];
                $db->insert($_POST);
            }else{
                $this->resume->updateResume('resume_basic',$cond,$_POST);
            }
            go(__CONTROL__.'/editResumeInfoEdu/id/'.intval($_POST['resume_id']));
        }
        $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_GET['id']));
        $field=$this->resume->getResumeBasic($cond);
        $this->assign('field',$field);
        $this->display('profile/edit_r_resume_basic');
    }
    function editResumeInfoEdu(){
         if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            $_POST['edu_start']=strtotime($_POST['edu_start']);
            $_POST['edu_end']=strtotime($_POST['edu_end']);
            $db=M('resume_edu');
            $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_POST['resume_id']));
            if(!$db->where($cond)->count()){
                $_POST['uid'] = $_SESSION['uid'];
                $db->insert($_POST);
            }else{
               $this->resume->updateResume('resume_edu',$cond,$_POST);
            }
            go(__CONTROL__.'/editResumeInfoExp/id/'.intval($_POST['resume_id']));
        }
        $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_GET['id']));
        $field=$this->resume->getResumeEdu($cond);
        $field['edu_start']=date('Y-m-d',$field['edu_start']);
        $field['edu_end']=date('Y-m-d',$field['edu_end']);
        $this->assign('field',$field);
        $this->display('profile/edit_r_resume_edu');
    }
    function editResumeInfoExp(){
         if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            $_POST['job_start']=strtotime($_POST['job_start']);
            $_POST['job_end']=strtotime($_POST['job_end']);
            $db=M('work_exp');
            $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_POST['resume_id']));
            if(!$db->where($cond)->count()){
                $_POST['uid'] = $_SESSION['uid'];
                $db->insert($_POST);
            }else{
                $this->resume->updateResume('work_exp',$cond,$_POST);
            }
                go(__CONTROL__.'/editResumeInfoAppend/id/'.intval($_POST['resume_id']));
        }
        $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_GET['id']));
        $field=$this->resume->getResumeExp($cond);
        $field['job_start']=date('Y-m-d',$field['job_start']);
        $field['job_end']=date('Y-m-d',$field['job_end']);
        $this->assign('field',$field);
        $this->display('profile/edit_r_resume_exp');
    }
    function editResumeInfoAppend(){
         if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            $db=M('resume_append');
            $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_POST['resume_id']));
            if(!$db->where($cond)->count()){
                $_POST['uid'] = $_SESSION['uid'];
                $db->insert($_POST);
            }else{
               $this->resume->updateResume('resume_append',$cond,$_POST);
            }
            $this->success('简历修改成功','resume');
        }
        $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_GET['id']));
        $field=$this->resume->getResumeAppend($cond);
        $this->assign('field',$field);
        $this->display('profile/edit_r_resume_append');
    }

    /**
     * 修改简历 
     */
    function editResume() {
        $db=M('resume');
        $cond=array('uid'=>$_SESSION['uid'],'resume_id'=>intval($_GET['id']));
        $info=$db->where($cond)->find();//简历资料
        if($_SERVER['REQUEST_METHOD']=='POST'){
            unset($_POST['uid']);
            if($_POST['avatar_type']==0){//不使用头像
                $_POST['avatar']='';
            }
            if(($_POST['avatar_type']==1 || $_POST['avatar_type']==2) && substr($info['avatar'],0,22)=='uploads/resume_avatars'){
                unlink(PATH_ROOT.'/'.$info['avatar']);//删除之前的头像文件
            }
            if(!empty($_FILES) && $_FILES['avatar']['error']==0){//有头像被上传，使用上传的头像
                C('UPLOAD_IMG_DIR','');
                C('THUMB_ENDFIX','');//只生成头像缩略图
                $upload=new upload(PATH_ROOT.'/uploads/resume_avatars',array('jpg','jpeg','png','gif'),3145728,0,1,array(100,100,4));
                $u_info=$upload->upload();
                if($u_info){
                    $_POST['avatar']=$u_info[0]['thumb'];
                }
            }
            if($db->where($cond)->update($_POST)>=0){
                go(__CONTROL__.'/editResumeInfoBasic/id/'.intval($_GET['id']));
            }
        }
        $style = $db->table('template')->field('name,dir_name,point')->where('type=1 AND state=1')->findall();//简历风格
        $avatars=$db->table('user_info')->field('avatar')->where('uid='.$_SESSION['uid'])->find();//用户头像
        $this->assign('avatar', $avatars['avatar']);
        $this->assign('info',$info);
        $this->assign('style',$style);
        $this->display('profile/editResume');
    }

    /**
     * 申请招聘 
     */
    function applyRecruit() {
        $db = M('resume');
        $resume = $db->field('resume_name,resume_id')->where('uid=' . $_SESSION['uid'])->findall();
        echo json_encode($resume);
        exit;
    }

    /**
     * 投递简历 
     */
    function deliver() {
        $db = M('deliver');
        $data['sendtime'] = time();
        $data['uid'] = $_SESSION['uid'];
        foreach ($_POST['recruit_id'] as $value) {
            if (!$db->where('recruit_id=' . $value[0] . ' AND sendtime >=' . strtotime(date('Y-m-d')))->count()) {
                $data['recruit_id'] = $value[0];
                $data['company_name'] = trim($value[1]);
                $data['recruit_name'] = trim($value[2]);
                $data['company_id'] = $value[3];
                $data['resume_id'] = $_POST['resume_id'];
                $data['resume_name'] = trim($_POST['resume_name']);
                $db->insert($data);
            }
        }
        echo json_encode(array('status' => 1, 'msg' => '恭喜你，简历投递成功！'));
        exit;
    }

    /**
     * 投递记录 
     */
    function postLog() {
        $db = M('deliver');
        $nums=$db->where('uid=' . $_SESSION['uid'])->count();
        $page=new page($nums,15);
        $delivers = $db->where('uid=' . $_SESSION['uid'])->order('sendtime DESC')->findall($page->limit());
        $this->assign('delivers', $delivers);
        $this->assign('page', $page->show());
        $this->display('profile/postLog');
    }

    /**
     * 收藏职位 
     */
    function favorite() {
        $db = M('favorite');
        foreach ($_POST as $value) {
            if (!$db->where('recruit_id=' . $value[0])->count()) {
                $data = array(
                    'recruit_id' => $value[0],
                    'company_name' => $value[1],
                    'created' => time(),
                    'uid' => $_SESSION['uid'],
                    'job_name' => $value[2],
                    'company_id' => $value[3],
                    'type'=>1
                );
                $db->insert($data);
            }
        }
        echo json_encode(array('status' => 1, 'msg' => '职位收藏成功'));
        exit;
    }

    /**
     * 查看收藏夹 
     */
    function viewFav() {
        $db = M('favorite');
        $nums=$db->where('uid=' . $_SESSION['uid'] .' AND '.' type=1')->count();
        $page=new page($nums,15);
        $favorites = $db->where('uid=' . $_SESSION['uid'] .' AND '.' type=1')->findall($page->limit());
        $this->assign('favorites', $favorites);
        $this->assign('page', $page->show());
        $this->display('profile/viewFav');
    }

    //谁下载了我的简历
    function downloadlog() {
        $db = M('resume_download');
        $nums=$db->where('uid=' . $_SESSION['uid'])->count();
        $page=new page($nums,15);
        $downloads = $db->field('resume_name,company_name,created,company_id')->where('uid=' . $_SESSION['uid'])->order('created desc')->findall($page->limit());
        $this->assign('downloads', $downloads);
        $this->assign('page', $page->show());
        $this->display('profile/downloadLog');
    }

    //面试邀请
    function interview() {
        $db = M('interview');
        $con = 'uid=' . $_SESSION['uid'];
        $count = $db->where($con)->count();
        $page = new page($count,15);
        $views = $db->where($con)->findall($page->limit());
        $this->assign('views', $views);
        $this->assign('page', $page->show());
        $this->display('profile/interview');
    }

    //个人资料
    function info() {
        $db = M('user_info');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db->where('uid=' . $_SESSION['uid'])->update($_POST);
        }
        $field = $db->where('uid=' . $_SESSION['uid'])->find();
        $this->assign('field', $field);
        $this->display('profile/info');
    }
    public function password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(trim($_POST['old_pwd'])==''){
                $this->error('必须输入旧密码');
            }
            $length=strlen($_POST['password']);
            if($length>20 || $length<6){
                $this->error('密码长度必须在6到20位之间！');
            }
            if($_POST['password']!=$_POST['re_password']){
                $this->error('两次密码不一样。');
            }
            if($this->auth->change_password($_POST)){
                setcookie(C('AUTH_AUTOLOGIN_COOKIE_NAME'), '', time() - 100000, '/'); //删除cookie
                $this->session_destroy();
                $this->success('密码修改成功',__WEB__.'/login');
            }
            $this->error($this->auth->error);
        }
        $this->display('profile/password');
    }

    //头像
    function avatars() {
        $db=M('user_info');
        $db_info=$db->field('avatar')->where('uid='.$_SESSION['uid'])->find();
        $avatar=$db_info['avatar'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            C('UPLOAD_IMG_DIR','');//图片没有附文件夹
            C('THUMB_ENDFIX','');//只生成头像缩略图（即覆盖原文件）
            $upload=new upload(PATH_ROOT.'/uploads/avatars',array('jpg','jpeg','png','gif'),3145728,0,1,array(100,100,4));
            $info=$upload->upload();
            if($info){
                $db->where('uid='.$_SESSION['uid'])->update(array('avatar'=>$info[0]['thumb']));
                unlink(PATH_ROOT.'/'.$avatar);//删除之前的头像文件
                $this->success('头像修改成功','avatars');
            }else{
                $this->error($upload->error);
            }
        }
        $this->assign('avatar',$avatar);
        $this->display('profile/avatars');
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

    
    //重新发送验证邮件
    function reAuth(){
        $this->email_activate->delAuthItem();//删除之先存入的验证信息
        $this->_buildAuthItem($_POST,$data);
        if ($this->_sendAuthEmail($_POST['email'],$data['activation_key']) && $this->email_activate->addAuthItem($data)) {
            $this->success('已成功发送激活邮件，请登录邮箱验证你的Email','proAuth');
        }else{
            $this->error('邮件发送失败，请与网站管理员联系','proAuth');
        }
    }

    private function _buildAuthItem($post,&$data){
        $activation_key = token();
        $data = array(
                'uid' => $_SESSION['uid'],
                'activation_key' => $activation_key,
                'created' => time(),
                'email' => $post['email']
        );
    }

    //安全认证
    function proAuth() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->user->emailExist($_POST['email'])) {//如果不存在
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
        $this->display('profile/proAuth');
    }

}

