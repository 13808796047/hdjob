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
    <div id="new-resume">
        <h2 class="data-title">新建简历</h2>
        <form action="http://127.0.0.1/hdjob/index.php/index/profile/createNewRes" method="post" validate="true" enctype="multipart/form-data">
        <table class="table-form">
            <tr>
                <th>简历名称：</th>
                <td><input type="text" name="resume_name" validate="{required:true}" /><span>简历名称将作为企业的检索条件</span></td>
            </tr>
            <tr>
                <th>简历风格：</th>
                <td><select name="style" validate="{required:true}">
                        <option value="">请选择</option>
                        <?php if(is_array($style)):?><?php  foreach($style as $value){ ?>
                        <option value="<?php echo $value['dir_name'];?>"><?php echo $value['name'];?></option>
                        <?php }?><?php endif;?>
                    </select></td>
            </tr>
            <tr>
                <th>简历头像：</th>
                <td id="avatar_type">
                    <label><input type="radio" name="avatar_type" href="#no_avatar" value="0" checked>不使用头像</label>
                    <label><input type="radio" name="avatar_type" href="#avatar_info" value="1">使用个人头像</label>
                    <label><input type="radio" name="avatar_type" href="#avatar_new" value="2">上传新头像</label>
                </td>
            </tr>
            <script type="text/javascript">
            $('#avatar_type input').click(function(){
                $('.avatar_type').hide();
                $($(this).attr('href')).show();
            });
            </script>
            <tr class="avatar_type fn-hide" id="avatar_info">
                <th></th>
                <td>
                    <?php if(!empty($avatar)){?>
                    <img src="http://127.0.0.1//hdjob/<?php echo $avatar;?>" alt="">
                    <input type="hidden" name="avatar" value="<?php echo $avatar;?>">
                    <?php  }else{ ?>
                    <img src="http://127.0.0.1/hdjob/templates/default/images/no_photo.gif" alt="">
                    <input type="hidden" name="avatar" value="">
                    <?php }?>
                </td>
            </tr>
            <tr class="avatar_type fn-hide" id="avatar_new">
                <th></th>
                <td><input type="file" name="avatar" class="input-file"></td>
            </tr>
            <tr>
                <th>公开设置：</th>
                <td>
                    <label><input type="radio" name="open" value="1" checked id="">公开</label>
                    <label><input type="radio" name="open" value="0" id="">不公开</label>
                </td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" class="btn btn-green" style="width:100px;" value="保存并下一步" /></td>
            </tr>
        </table>
            </form>
    </div>
</div>
</div>
</div>
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