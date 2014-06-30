<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/index.css"/>
<style type="text/css">
	#c-list{
		width: 1000px;
		margin: 15px auto;
		/*border: 1px #0f0 solid;*/
	}
	.channel{
		width: 290px;
		height: 205px;
		border: 1px #DEDEEE solid;
		float: left;
		margin-right: 15px;
		margin-bottom: 10px;
		overflow: hidden;
	}
	.channel-title{
		padding: 4px 10px;
		border-bottom: solid 1px #DEDEEE;
		color: #666;
	}
	.channel-title a{
		float: right;
		color: #999;
		font-weight: normal;
	}
	.channel ul{
		margin-top: 6px;
	}
	.channel ul li{
		padding: 2px;
		height: 20px;
		padding-left: 10px;
		font-size: 13px;
	}
	.channel ul li a{
		color: #06C;
	}
</style>
</head>
<body>
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

	<div id="c-list">
		<?php $db=M('channel');
        $channels=$db->cache(86400)->field('id,title,pinyin,href')->where('pid=0 AND state=1 AND type=1')->order('sort')->limit(10)->findall();?><?php if(is_array($channels)):?><?php foreach($channels as $channel):?>
			<div class="channel">
				<div class="channel-title">
					<h2><span><?php echo $channel['title'];?></span><a href="<?php echo $channel['href'];?>">更多</a></h2>
				</div>
				<ul>
				<?php $db=M('article');
            $arc_list=$db->field('title,id,created,updated,href')->where('state=1 AND cid='.$channel['id'])->limit(5)->order('updated desc')->findall();?><?php if(is_array($arc_list)):?><?php foreach($arc_list as $arc):?>
				<li><a href="<?php echo $arc['href'];?>" target="_blank"><?php echo $arc['title'];?></a></li>
				<?php endforeach;endif;?>
				</ul>
			</div>
		<?php endforeach;endif;?>
	</div>
</body>
</html>
