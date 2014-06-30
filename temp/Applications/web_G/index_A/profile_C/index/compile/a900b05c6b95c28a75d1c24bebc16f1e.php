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
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/common.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/profile.css"/>
<div id="ui-opt-area">
    <div id="personal">
    	<h2 class="data-title">个人信息</h2>
    	<div id="info">
    		<div class="avatar">
    			<?php if(!empty($user['avatar'])){?>
    			<a href="http://127.0.0.1/hdjob/index.php/index/profile/avatars"><img src="http://127.0.0.1//hdjob/<?php echo $user['avatar'];?>" alt="<?php echo $user['name'];?>" title="<?php echo $user['name'];?>"></a>
    			<?php  }else{ ?>
    			<a href="http://127.0.0.1/hdjob/index.php/index/profile/avatars"><img src="http://127.0.0.1/hdjob/templates/default/images/no_photo.gif" alt=""></a>
    			<?php }?>
    		</div>
    		<div class="user-log">
    			<h2>
    				<span class="user-title"><?php echo $user['name'];?></span>
                    <span><a href="http://127.0.0.1/hdjob/index.php/index/auth/logout">退出登录</a></span>
    				<?php if($user['email_verify']==1){?>
    				<span class="email-pass">Email已认证</span>
    				<?php }?>
    			</h2>
    			<p class="login-log tips">
    				<span>上次登录：<?php echo date('Y-m-d H:i:s',$_SESSION['last_login']);?></span>
    				<span>上次IP：<?php echo $_SESSION['last_ip'];?></span>
    			</p>
    			<div class="person-log">
    				<span>你创建了 <span class="sys"><?php echo $user['resumeNums'];?></span> 份简历</span>
    				<a href="http://127.0.0.1/hdjob/index.php/index/profile/resume">管理简历</a>
    				<a href="http://127.0.0.1/hdjob/index.php/index/profile/interview">查看收到的邀请</a>
    			</div>
    			<div id="shortcut-tips">
    				<span>收到的面试邀请：(<a href="" class="tips"><?php echo $user['interviews'];?></a>)</span>
    				<span>已申请职位：(<a href="" class="tips"><?php echo $user['delivers'];?></a>)</span>
    				<span>已收藏职位：(<a href="" class="tips"><?php echo $user['favorites'];?></a>)</span>
    			</div>
    		</div>
    	</div>
    	<div id="shortcut">
    		<a href="http://127.0.0.1/hdjob/index.php/index/profile/createNewRes" class="creat"></a>
    		<a href="http://127.0.0.1/hdjob/index.php/index/profile/resume" class="manage"></a>
    	</div>
    	<div style="clear:both"></div>
    </div>
    <div id="news">
    	<h2 class="data-title">最新邀请面试</h2>
    	<table class="table-list">
    		<tr class="table-list-header">
    			<th>邀请公司</th>
    			<th>面试职位</th>
    			<th>邀请时间</th>
    		</tr>
    		<?php if(!empty($interviews)){$list_id1=0;?><?php foreach($interviews as $key=>$interview){?><?php if($list_id1==5) break;?><?php $list_id1+=1;?>
    		<tr>
    			<td><a href="http://127.0.0.1/hdjob/index.php/index/index/company/id/<?php echo $interview['company_id'];?>" target="_blank"><?php echo $interview['company_name'];?></a></td>
    			<td><a href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $interview['recruit_id'];?>" target="_blank"><?php echo $interview['recruit_name'];?></a></td>
    			<td><?php echo date('Y-m-d H:i:s',$interview['created']);?></td>
    		</tr>
    		<?php }?><?php }?>
    	</table>
    </div>
</div>
</div>
</div>
<<?php if(!defined("PATH_HD"))exit;?>
<!-- <div id="copy-bottom">
    <div>
        <p><?php echo $_CONFIG['copyright'];?></p>
    </div>
</div>
</body>
</html> -->