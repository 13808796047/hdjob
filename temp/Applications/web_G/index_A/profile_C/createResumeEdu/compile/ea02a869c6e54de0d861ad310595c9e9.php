<?php if(!defined("PATH_HD"))exit;?>
<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>个人中心---<?php echo $_CONFIG['web_name'];?></title>
    <meta name="keywords" content="PHP培训,PHP视频教程" />
<meta name="description" content="北京后盾计算机技术培训有限责任公司是专注于培养中国互联网优秀的程序语言专业人才的专业型培训机构。" />
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/profile_public.css"/>
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/common.css"/>
    <script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
        var url='http://127.0.0.1//hdjob';
    </script>
</head>
<body>
    <!--公共头部导航-->
<?php if(!defined("PATH_HD"))exit;?>
 <!-- <div id="top"><a href="http://127.0.0.1/hdjob/index.php" id="logo"><img src="http://127.0.0.1//hdjob/public/images/logo.png" width="217" height="60" /></a> -->
 

<div id="top-ads">
    <?php $db = M('ads');$adss =$db->field('href,text,path,color,width,height,uid')
        ->where(" state=1 AND cate =1 AND endtime >".time())
        ->limit(3)->findall();?><?php if(is_array($adss)):?><?php foreach ($adss as $ads):?>
    <a href="<?php echo $ads['href'];?>"><img src="http://127.0.0.1//hdjob/<?php echo $ads['path'];?>" width="300" height="63" /></a>
    <?php endforeach;?><?php endif;?>
</div>
 
<!--nav-->
<div id="nav" class="round-case">
<div class="fn-left"></div>
<div class="nav-list">
    <ul>
        <?php $db=M('nav');$navs=$db->cache(86400)->field('href,title,target')->where('state=1')->order('sort ASC')->limit(8)->findall();?><?php if(is_array($navs)):?><?php foreach ($navs as $nav):?>
          <li><a href="<?php if(substr($nav['href'],0,4)!='http'){?>http://127.0.0.1/hdjob/index.php<?php echo $nav['href'];?><?php  }else{ ?><?php echo $nav['href'];?><?php }?>" target="<?php echo $nav['target'];?>"><?php echo $nav['title'];?></a></li>
        <?php endforeach;endif;?>
    </ul>
    <div id="nav-help"><a href="###">企业服务</a><a href="###">招聘助手</a></div>
</div>
<div class="fn-right"></div>
</div>
<!--/nav--> 

    <!--/公共头部导航--> 
    <!--个人主页-->
    <div id="ui-center">
<div id="ui-opt">
<div id="ui-opt-menu">
  <div class="menu-list">
<h2>个人中心</h2>
<ul>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/">个人中心</a></li>
</ul>
    </div>
    <div class="menu-list">
<h2>简历管理</h2>
<ul>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/createNewRes.html">创建新简历</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/resume.html">简历管理</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/postLog.html">简历投递记录</a></li>
</ul>
    </div>
    <div class="menu-list">
<h2>求职管理</h2>
<ul>
<li><a href="http://127.0.0.1/hdjob/index.php/index/search.html" target="_blank">搜索职位</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/interview">收到的面试邀请</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/viewFav.html">职位收藏夹</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/downloadLog.html">谁下载了我的简历</a></li>
</ul>
    </div>
    <div class="menu-list">
<h2>账户中心</h2>
<ul>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/info.html">个人资料</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/proAuth">安全认证</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/profile/password">修改密码</a></li>
<li><a href="http://127.0.0.1/hdjob/index.php/index/index/feedback">意见反馈</a></li>
</ul>
    </div>
</div>
<script>
$('#ui-opt-menu li').hover(function(){
    if(!$(this).is(":animated")){
    $(this).animate({'padding-left': '+15px'},400);
    }else{
        $(this).stop();
    }
},function(){
    $(this).animate({'padding-left': '-15px'},400);
});
$("#ui-opt-menu h2").toggle(function(){
    $(this).addClass('title-more');
    $(this).next().slideUp();
},function(){
    $(this).removeClass('title-more');
    $(this).next().slideDown();
});
</script>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jqueryValidate/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jqueryValidate/jquery.metadata.js"></script>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/My97DatePicker/WdatePicker.js"></script>
<div id="ui-opt-area">
    <div id="new-resume-nav" class="fn-clear tabs-nav">
        <ul>
            <li> 1 基本情况</li>
            <li class="tabs-nav-active"> 2 教育与工作</li>
            <li> 3 附加信息</li>
        </ul>
    </div>
    <div id="new-resume">
            <h2 class="data-title">最高教育信息</h2>
        <form validate="true" method="post" action="http://127.0.0.1/hdjob/index.php/index/profile/createResumeEdu" ><?php if(!defined("PATH_HD"))exit;?>
<table class="table-form">
        <tr>
        <th>开始时间</th>
        <td><input type="text" name="edu_start" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',autoPickDate:true})"  validate={"required":true,"maxlength":20}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>结束时间</th>
        <td><input type="text" name="edu_end" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',autoPickDate:true})"  validate={"required":true,"maxlength":10}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>学校名称</th>
        <td><input type="text" name="school"   validate={"required":true,"maxlength":30}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>专业名称</th>
        <td><input type="text" name="major"   validate={"required":true,"maxlength":30}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>学历</th>
        <td><select name="degree"   validate={"required":true} ><option value="">请选择</option><option value="1" selected="selected">初中</option><option value="2" >高中</option><option value="3" >中专</option><option value="4" >中技</option><option value="5" >大专</option><option value="6" >本科</option><option value="7" >硕士</option><option value="8" >MBA</option><option value="9" >EMBA</option><option value="10" >博士</option><option value="11" >其他</option></select><span></span></td>
    </tr>
    </table>
        </form>
            <h2 class="data-title">工作经验</h2>
        <form validate="true" method="post" action="http://127.0.0.1/hdjob/index.php/index/profile/createResumeEdu" ><?php if(!defined("PATH_HD"))exit;?>
<script type="text/javascript" src="http://127.0.0.1//hdjob/caches/js/linkage_data.js"></script><script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/linkage/linkage_style_2.js"></script><table class="table-form">
        <tr>
        <th>公司行业</th>
        <td><input type="text" id="industry" title="" value=""  validate={"required":true}  /><script>$(function(){$("#industry").linkage_style_2({
                data:linkage_3,
                field:'industry',
                html_attr:' validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>所在的部门</th>
        <td><input type="text" name="department"   validate={"required":true,"maxlength":10}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>职位名称</th>
        <td><input type="text" name="job_name"   validate={"required":true,"maxlength":15}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>工作时间（开始）</th>
        <td><input type="text" name="job_start" onfocus="WdatePicker()"  validate={"required":true,"maxlength":10}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>工作时间（结束）</th>
        <td><input type="text" name="job_end" onfocus="WdatePicker()"  validate={"required":true,"maxlength":10}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>工作描述</th>
        <td><textarea name = "job_desc"   validate={"required":true,"maxlength":250}  ></textarea><span></span></td>
    </tr>
        <tr>
        <th>职位月薪</th>
        <td><select name="salary"   validate={"required":true} ><option value="">请选择</option><option value="1000" >1000元/月以下</option><option value="100002000" >1000-2000元/月</option><option value="200104000" >2001-4000元/月</option><option value="400106000" >4001-6000元/月</option><option value="600108000" >6001-8000元/月</option><option value="800110000" >8001-10000元/月</option><option value="1000115000" >10001-15000元/月</option><option value="1500025000" >15000-25000元/月</option><option value="2500000000" >25000元/月以上</option></select><span></span></td>
    </tr>
        <tr>
        <th>公司名称</th>
        <td><input type="text" name="company_name"   validate={"required":true,"maxlength":30}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>公司性质</th>
        <td><select name="company_property"   validate={"required":true} ><option value="">请选择</option><option value="1" >国企</option><option value="2" >外商独资</option><option value="3" >代表处</option><option value="4" >合资</option><option value="5" >民营</option><option value="6" >股份制企业</option><option value="7" >上市公司</option><option value="8" >国家机关</option><option value="9" >事业单位</option><option value="10" >其它</option></select><span></span></td>
    </tr>
        <tr>
        <th>公司规模</th>
        <td><select name="company_scope"  ><option value="">请选择</option><option value="1" selected="selected">20人以下</option><option value="2" >20-99人</option><option value="3" >100-499人</option><option value="4" >500-999人</option><option value="5" >1000-9999人</option><option value="6" >10000人以上</option></select><span></span></td>
    </tr>
    </table>
        <input type="hidden" name="resume_edu" id="resume_edu_v" /><input type="hidden" name="resume_id" value="<?php echo $_GET['resume_id'];?>" />
        <table class="table-form">
                <tr>
                    <th></th>
                    <td><button class="btn btn-large btn-green" id="save"  />提交</button>
                </tr>
            </table>  
        </form>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    $('#save').click(function(){
        $('#resume_edu_v').val($('#new-resume form:eq(0)').serialize());
    });
</script>
<?php if(!defined("PATH_HD"))exit;?>
<!-- <div id="copy-bottom">
    <div>
        <p><?php echo $_CONFIG['copyright'];?></p>
    </div>
</div>
</body>
</html> -->