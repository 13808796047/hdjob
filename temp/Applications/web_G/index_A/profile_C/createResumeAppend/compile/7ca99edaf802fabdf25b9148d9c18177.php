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
<div id="ui-opt-area">
    <div id="new-resume-nav" class="fn-clear tabs-nav">
        <ul>
            <li> 1 基本情况</li>
            <li> 2 教育与工作</li>
            <li class="tabs-nav-active"> 3 附加信息</li>
        </ul>
    </div>
    <div id="new-resume">
        <form validate="true" method="post" action="http://127.0.0.1/hdjob/index.php/index/profile/createResumeAppend" ><?php if(!defined("PATH_HD"))exit;?>
<table class="table-form">
        <tr>
        <th>标题</th>
        <td><select name="title"   validate={"required":true,"maxlength":30} ><option value="">请选择</option><option value="兴趣爱好" >兴趣爱好</option><option value="获得荣誉" >获得荣誉</option><option value="专业组织" >专业组织</option><option value="著作/论文" >著作/论文</option><option value="专利" >专利</option><option value="宗教信仰" >宗教信仰</option><option value="特长职业目标" >特长职业目标</option><option value="特殊技能" >特殊技能</option><option value="社会活动" >社会活动</option><option value="荣誉" >荣誉</option><option value="推荐信" >推荐信</option><option value="其他" >其他</option></select><span></span></td>
    </tr>
        <tr>
        <th>内容</th>
        <td><textarea name = "content"   validate={"maxlength":300}  ></textarea><span></span></td>
    </tr>
    </table>
        <input type="hidden" name="resume_id" value="<?php echo $_GET['resume_id'];?>" />
        <table class="table-form">
                <tr>
                    <th></th>
                    <td><button class="btn btn-large btn-green" />提交</button>
                </tr>
        </table>
        </form>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    $('select[name="title"]').change(function(){
        if($(this).val()=='其他'){
            if($(this).next('input').length==0){
                $(this).after('<input type="text" class="append-title" name="title" id="" />');
            }else{
                $(this).next().show();
            }
        }else{
            $(this).next().hide();
        }
    });
</script>
<style type="text/css">
    #new-resume select{
        width: 100px;
        height:27px;
    }
    .append-title{
        margin: 7px;
    }
</style>
<!--/个人主页--> 
<!--公共底部信息--> 
<!--/公共底部信息-->
<?php if(!defined("PATH_HD"))exit;?>
<!-- <div id="copy-bottom">
    <div>
        <p><?php echo $_CONFIG['copyright'];?></p>
    </div>
</div>
</body>
</html> -->