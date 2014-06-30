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
<link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/js/artDialog/skins/blue.css"/>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/artDialog/jquery.artDialog.min.js"></script>
<style type="text/css">
    select{
        width: 100px;
        padding-right: 0px;
    }
</style>
<div id="ui-opt-area">
    <div id="new-resume-nav" class="fn-clear tabs-nav">
        <ul>
            <li class="tabs-nav-active"> 1 基本情况</li>
            <li> 2 教育与工作</li>
            <li> 3 附加信息</li>
        </ul>
    </div>
    <div id="new-resume">
        <form validate="true" method="post" action="http://127.0.0.1/hdjob/index.php/index/profile/createResume" ><?php if(!defined("PATH_HD"))exit;?>
<script type="text/javascript" src="http://127.0.0.1//hdjob/caches/js/linkage_data.js"></script><script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/linkage/linkage_style_1.js"></script><script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/linkage/linkage_style_2.js"></script><table class="table-form">
        <tr>
        <th>真实姓名</th>
        <td><input type="text" name="name"   validate={"required":true}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>性别</th>
        <td><label><input type="radio" name="gender"  value="1" validate={"required":true}   />男</label><label><input type="radio" name="gender"  value="2" validate={"required":true}   />女</label><span></span></td>
    </tr>
        <tr>
        <th>出生日期（年）</th>
        <td><input type="text" name="birthday" onfocus="WdatePicker({dateFmt:'yyyy',minDate:'1960',startDate:'1980',autoPickDate:true})"  validate={"required":true,"digits":true}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>婚姻状况</th>
        <td><label><input type="radio" name="marital_status"  value="1" validate={"required":true}   />未婚</label><label><input type="radio" name="marital_status"  value="2" validate={"required":true}   />已婚</label><label><input type="radio" name="marital_status"  value="3" validate={"required":true}   />保密</label><span></span></td>
    </tr>
        <tr>
        <th>户口所在地</th>
        <td><select style="margin-right:3px;" id="origin_provice" name="origin_provice"  validate={"required":true} ></select><script>$(function(){$("#origin_provice").linkage_style_1({
                data:city,
                field:'origin_provice#origin_city#origin_town',
                html_attr:' validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>联系地址</th>
        <td><select style="margin-right:3px;" id="link_provice" name="link_provice"  validate={"required":true} ></select><script>$(function(){$("#link_provice").linkage_style_1({
                data:city,
                field:'link_provice#link_city#link_town',
                html_attr:' validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>证件类型</th>
        <td><select name="cert_type"  ><option value="">请选择</option><option value="1" >身份证</option><option value="2" >护照</option><option value="3" >军官证</option><option value="4" >香港身份证</option><option value="5" >澳门身份证</option><option value="6" >港澳通行证</option><option value="7" >台胞证</option><option value="8" >其他</option></select><span></span></td>
    </tr>
        <tr>
        <th>证件号码</th>
        <td><input type="text" name="id_number"   validate={"required":true,"maxlength":20}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>工作经验</th>
        <td><select style="margin-right:3px;" id="work_exp" name="work_exp" ></select><script>$(function(){$("#work_exp").linkage_style_1({
                data:linkage_22,
                field:'work_exp',
                html_attr:''
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>联系电话</th>
        <td><input type="text" name="telephone"   value="" /><span></span></td>
    </tr>
        <tr>
        <th>联系Email</th>
        <td><input type="text" name="link_email"   validate={"email":true,"maxlength":30}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>个人主页</th>
        <td><input type="text" name="profile"   validate={"required":true,"url":true,"maxlength":100}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>自我评价</th>
        <td><textarea name = "self_eval"   validate={"maxlength":200}  ></textarea><span></span></td>
    </tr>
        <tr>
        <th>详细地址</th>
        <td><input type="text" name="address"   validate={"required":true,"maxlength":25}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>期望工作性质</th>
        <td><label><input type="checkbox" name="work_type[]"  value="1" validate={"required":true}  checked="checked" />全职</label><label><input type="checkbox" name="work_type[]"  value="2" validate={"required":true}   />兼职</label><label><input type="checkbox" name="work_type[]"  value="3" validate={"required":true}   />实习</label><span></span></td>
    </tr>
        <tr>
        <th>期望从事行业</th>
        <td><input type="text" id="hope_industry" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_industry").linkage_style_2({
                data:linkage_3,
                field:'hope_industry',
                html_attr:' validate={"required":true} ',checkbox:true
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>期望从事职业</th>
        <td><input type="text" id="hope_career" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_career").linkage_style_2({
                data:linkage_4,
                field:'hope_career#hope_career_t',
                html_attr:' validate={"required":true} ',checkbox:true
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>期望工作地点</th>
        <td><select style="margin-right:3px;" id="hope_provice" name="hope_provice"  validate={"required":true} ></select><script>$(function(){$("#hope_provice").linkage_style_1({
                data:city,
                field:'hope_provice#hope_city#hope_town',
                html_attr:' validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>期望月薪(税前)</th>
        <td><select style="margin-right:3px;" id="hope_salary" name="hope_salary"  validate={"required":true} ></select><script>$(function(){$("#hope_salary").linkage_style_1({
                data:linkage_19,
                field:'hope_salary',
                html_attr:' validate={"required":true} ',defaults:'200104000'
                })});</script><span></span></td>
    </tr>
    </table>
            <input type="hidden" name="resume_id" value="<?php echo $_GET['resume_id'];?>" />
            <table class="table-form">
                <tr>
                    <th></th>
                    <td><button class="btn btn-large btn-green" name="addRecruit" />提交</button>
                </tr>
            </table>    
        </form>
    </div>
</div>
</div>
</div>
<?php if(!defined("PATH_HD"))exit;?>
<!-- <div id="copy-bottom">
    <div>
        <p><?php echo $_CONFIG['copyright'];?></p>
    </div>
</div>
</body>
</html> -->