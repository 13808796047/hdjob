<?php
/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Describe   : 前台首页
 */
class indexControl extends Control {

    function index() {
        $config = array(
            'AREACODE'=>$_GET['areacode'],
            'STICKET'=>$_GET['sticket'],
            'PORTALTYPE'=>$_GET['portaltype'],
            'COLUMNID' =>$_GET['columnid'],
            'EXT '=>$_GET['ext'],
            'VERSION'=>$_GET['version'],
            'USERSSIONID'=>$_GET['usessionid'],
            'UA'=>$_GET['ua'],
            'RESOURCEID'=>$_GET['resourceid'],
            'BACKURL'=>$_GET['backurl'],
            );

            $data="<?php
            if(!defined('PATH_HD'))exit;\nreturn ".var_export($config,true).";\n?>";
            file_put_contents(PATH_ROOT.'/config/inc.php', $data);
    
   
        $areacode = C('AREACODE');
        $sticket = C('STICKET');
        $portaltype = C('PORTALTYPE') ;
        $columnid = C('COLUMNID') ;
        $ext = C('EXT') ;
        $version = C('VERSION');
        $usessionid = C('USERSSIONID');
        $ua = C('UA') ;
        $resourceid = C('RESOURCEID') ;
        $backurl = C('BACKURL');
       $ch = curl_init();
        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, "http://apps.wxcs.cn/ajax/requestHead.do?version=$version&columnid=$columnid&ua=$ua&ext=$ext&portaltype=$portaltype&backurl=$backurl&resourceid=$resourceid&areacode=$areacode&usessionid=$usessionid");

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 抓取URL并把它传递给浏览器
        
        file_put_contents(header.'.html',curl_exec($ch));
        curl_setopt($ch, CURLOPT_URL, "http://apps.wxcs.cn/ajax/requestBottom.do?version=$version&columnid=$columnid&ua=$ua&ext=$ext&portaltype=$portaltype&backurl=$backurl&resourceid=$resourceid&areacode=$areacode&usessionid=$usessionid");
        file_put_contents(footer.'.html',curl_exec($ch));
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);

        $this->display();
    }

    //城市导航
    function citymap()
    {
        $db=M('city');
        $citys=$db->cache(86400*10)->where('is_open=1')->order('ucfirst')->findall();
        $citys_fmt=array();
        foreach ($citys as $value) {
            $citys_fmt[$value['ucfirst']][]=$value;
        }
        $this->assign('citys_format',$citys_fmt);
        $this->display('citymap');
    }

    //城市专栏招聘
    function cityColumn()
    {
        $db=M('city');
        $cond=array(
            'pinyin'=>$_GET['name'],
            'is_open'=>1//如果开启了主站
        );
        $city=$db->field('id,name')->where($cond)->find();
        if(!$city){
            $this->error('对不起，未找到城市信息。','index');
        }
        $this->assign('city',$city);
        $this->display('city');
    }
    //企业主页
    function company() {
        if (!isset($_GET['id']) or $_GET['id']==1) {
            $this->error('没有该企业的信息');
        }
        $uid = intval($_GET['id']);//企业ID
        $db = M('company_info');
        $company = $db->where('uid=' . $uid)->find();
        if (!$company) {
            $this->error('没有该企业的信息');
        }
        $cond = array('uid' =>$uid ,'expiration_time>'.time(),'verify=1','state=1');
        $recruits = $db->table('recruit')->where($cond)->field('recruit_id,start_time,recruit_name')->findall();
        $data=new data('recruit');
        $company=$data->convert($company);
        $this->assign('company', $company);
        $this->assign('recruits',$recruits);
        if(empty($company['tpl_style'])){
            $company['tpl_style']='skyblue';//默认风格
        }
        if(!empty($_GET['style']) && $uid==$_SESSION['uid']){//企业预览模板
            $company['tpl_style'] = $_GET['style'];
        }
        $this->display(PATH_ROOT . '/templates/company_tpl/' . $company['tpl_style'] . '/index');
    }
    
    //意见反馈
    function feedback(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $_POST['content']=htmlspecialchars($_POST['content']);
            $_POST['title']=strip_tags($_POST['title']);
            $_POST['name']=strip_tags($_POST['name']);
            $_POST['email']=strip_tags($_POST['email']);
            $_POST['type']=intval($_POST['type']);
            $_POST['created']=time();
            $db=M('feedback');
            if($db->insert()){
                $this->success('反馈成功！','index');
            }
        }
        $type=array(
            1=>'建议',
            2=>'咨询',
            3=>'举报',
            4=>'求助'
        );
        if(!isset($_GET['type'])){
            $_GET['type']=1;
        }
        $this->assign('type',$type);
        $this->display();
    }


}