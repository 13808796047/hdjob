<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>职位搜索-<?php echo $_CONFIG['web_name'];?></title>
<meta name="keywords" content="PHP培训,PHP视频教程" />
<meta name="description" content="北京后盾计算机技术培训有限责任公司是专注于培养中国互联网优秀的程序语言专业人才的专业型培训机构。" />
<link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/search.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/common.css"/>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/js/artDialog/skins/green.css"/>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/artDialog/jquery.artDialog.min.js"></script>
</head>

<body>
<!--nav--> 

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

<!--/nav--> 
<!--条件区域-->
<div id="condition">
<div class="cond-list fn-clear" id="">
<div class="cond-title">关键字：</div>
<div class="cond-switch"> <span id="keyword-area">
  <form method="get" id="search" action="http://127.0.0.1/hdjob/index.php/index/search/">
    <input name="keywords" id="keywords" class="input-word" type="text" onclick="javascript:if(this.value=='输入职位名称、公司名称......'){this.value='';}" <?php if(!empty($_GET['keywords'])){?>value="<?php echo $_GET['keywords'];?>"<?php  }else{ ?>value="输入职位名称、公司名称......"<?php }?>>
    <input class="search-img" type="submit" value=" ">
  </form>
        <script type="text/javascript">
            $('#search').submit(function(){
                if($('#keywords').val()=='输入职位名称、公司名称......'){
                 $('#keywords').val('');   
                }
            });
        </script>
  </span>
  <div id="show_more"> <span class="show_more"><a href="">显示更多搜索条件</a></span> </div>
</div>
</div>
<div class="cond-list fn-clear" id="jobs_ins">
<div class="cond-title">职位行业：</div>
<div class="cond-switch">
  <ul>
    <?php $i=1;?>
    <?php if(is_array($filterCond['industry'])):?><?php  foreach($filterCond['industry'] as $value){ ?>
    <?php if($i==5){?>
    <li><span class="show_more"><a href="">更多</a></span></li>
    <?php }?>
    <li title="<?php echo $value['title'];?>"><a <?php if(isset($_GET['jobs_industry']) && $_GET['jobs_industry']==$value['laid']){?>class="cond-checked"<?php }?> href="<?php echo url_remove_param('jobs_industry');?>/jobs_industry/<?php echo $value['laid'];?>.html"><?php echo $value['title'];?></a></li>
    <?php $i++;?>
    <?php }?><?php endif;?>
    <li><span class="show_less"><a href="">收起</a></span></li>
  </ul>
</div>
</div>
<div class="cond-list fn-clear">
<div class="cond-title">地区：</div>
<div class="cond-switch">
    <ul>
        <?php $i=1;?>
    <?php if(is_array($filterCond['address'])):?><?php  foreach($filterCond['address'] as $value){ ?>
    <?php if($i==5){?>
    <li><span class="show_more"><a href="">更多</a></span></li>
    <?php }?>
    <li><a <?php if(isset($_GET['address']) && $_GET['address']==$value['id']){?>class="cond-checked"<?php }?> href="<?php echo url_remove_param('address');?>/address/<?php echo $value['id'];?>"><?php echo $value['name'];?></a></li>
    <?php $i++;?>
    <?php }?><?php endif;?>
    <li><span class="show_less"><a href="">收起</a></span></li>
    </ul>
</div>
</div>
<?php if(isset($_GET['address'])){?>
<div class="cond-list fn-clear">
<div class="cond-title">地区子类：</div>
<div class="cond-switch">
    <ul>
        <?php $i=1;?>
    <?php if(is_array($filterCond['sonAddress'])):?><?php  foreach($filterCond['sonAddress'] as $value){ ?>
    <?php if($i==5){?>
    <li><span class="show_more"><a href="">更多</a></span></li>
    <?php }?>
    <li><a <?php if(isset($_GET['city']) && $_GET['city']==$value['id']){?>class="cond-checked"<?php }?> href="<?php echo url_remove_param('city');?>/city/<?php echo $value['id'];?>.html"><?php echo $value['name'];?></a></li>
    <?php $i++;?>
    <?php }?><?php endif;?>
    <li><span class="show_less"><a href="">收起</a></span></li>
    </ul>
</div>
</div>
<?php }?>
<div class="cond-list fn-clear">
<div class="cond-title">更新：</div>
<div class="cond-switch">
    <ul>
        <li><a href="<?php echo url_remove_param('refresh_date');?>/refresh_date/3.html" <?php if(isset($_GET['refresh_date']) && $_GET['refresh_date']==3){?>class="cond-checked"<?php }?>>3天内</a></li>
        <li><a href="<?php echo url_remove_param('refresh_date');?>/refresh_date/10.html" <?php if(isset($_GET['refresh_date']) && $_GET['refresh_date']==10){?>class="cond-checked"<?php }?>>10天内</a></li>
        <li><a href="<?php echo url_remove_param('refresh_date');?>/refresh_date/15.html" <?php if(isset($_GET['refresh_date']) && $_GET['refresh_date']==15){?>class="cond-checked"<?php }?>>15天内</a></li>
        <li><a href="<?php echo url_remove_param('refresh_date');?>/refresh_date/30.html" <?php if(isset($_GET['refresh_date']) && $_GET['refresh_date']==30){?>class="cond-checked"<?php }?>>30天内</a></li>
    </ul>
</div>
</div>
<!--隐藏条件区域-->
<div class="hide-cond">
<?php if(is_array($switchs)):?><?php  foreach($switchs as $key=>$swicth){ ?>
  <div class="cond-list fn-clear">
    <div class="cond-title"><?php echo $swicth['title'];?>：</div>
    <div class="cond-switch">
        <ul>
        <?php if(is_array($swicth['switch'])):?><?php  foreach($swicth['switch'] as $switch_k=>$switch_v){ ?>
        <li><a href="<?php echo url_remove_param($key);?>/<?php echo $key;?>/<?php echo $switch_k;?>" <?php if(isset($_GET[$key]) && $_GET[$key]==$switch_k){?>class="cond-checked"<?php }?>><?php echo $switch_v;?></a></li>
        <?php }?><?php endif;?>
        </ul>
    </div>
  </div>
<?php }?><?php endif;?>
  <div class="cond-list fn-clear">
<div class="cond-title">职位分类：</div>
<div class="cond-switch">
    <ul>
        <?php $i=1;?>
    <?php if(is_array($filterCond['jobClass'])):?><?php  foreach($filterCond['jobClass'] as $value){ ?>
    <?php if($i==5){?>
    <li><span class="show_more"><a href="">更多</a></span></li>
    <?php }?>
    <li><a <?php if(isset($_GET['class']) && $_GET['class']==$value['laid']){?>class="cond-checked"<?php }?> href="<?php echo url_remove_param('class');?>/class/<?php echo $value['laid'];?>"><?php echo $value['title'];?></a></li>
    <?php $i++;?>
    <?php }?><?php endif;?>
    <li><span class="show_less"><a href="">收起</a></span></li>
    </ul>
</div>
</div>
<?php if(isset($_GET['class'])){?>
<div class="cond-list fn-clear">
<div class="cond-title">分类子类：</div>
<div class="cond-switch">
    <ul>
        <?php $i=1;?>
    <?php if(is_array($filterCond['sonJobClass'])):?><?php  foreach($filterCond['sonJobClass'] as $value){ ?>
    <?php if($i==5){?>
    <li><span class="show_more"><a href="">更多</a></span></li>
    <?php }?>
    <li><a <?php if(isset($_GET['class_two']) && $_GET['class_two']==$value['laid']){?>class="cond-checked"<?php }?> href="<?php echo url_remove_param('class_two');?>/class_two/<?php echo $value['laid'];?>.html"><?php echo $value['title'];?></a></li>
    <?php $i++;?>
    <?php }?><?php endif;?>
    <li><span class="show_less"><a href="">收起</a></span></li>
    </ul>
</div>
</div>
<?php }?>
</div>
<!--/隐藏条件区域-->
</div>
<!--/条件区域-->
<div id="now-cond"><span>当前搜索条件：</span><?php if(is_array($_GET)):?><?php  foreach($_GET as $key=>$value){ ?><?php if(isset($switchs[$key])){?><a href="<?php echo url_remove_param($key);?>"><?php echo $switchs[$key]['switch'][$value];?><em>X</em></a><?php }?><?php }?><?php endif;?><?php if(isset($_GET['update'])){?><a href="<?php echo url_remove_param('update');?>"><?php echo $_GET['update'];?>天内<em>X</em></a><?php }?></div>
<div id="lists">
<div id="list-nav">
    <div id="view-way">
        <span class="fn-left">查看方式：</span>
        <a href="" class="list-info">详细</a>
        <a href="" class="list">列表</a>
    </div>
    <!--<div id="total-list">符合当前条件的共有<span><?php echo count($jobs);?></span>个职位</div>-->
</div>
<div id="list">
<table>
<tr class="list-head">
	<th class="check-fav"></th>
	<th>职位名称</th>
	<th>公司名称</th>
	<th>工作地点</th>
	<th>经验要求</th>
	<th class="update-date">更新日期</th>
</tr>
    <!--遍历推广职位-->
    <?php if(is_array($spreads)):?><?php  foreach($spreads as $spread){ ?>
    <tr class="jobs-simple">
	<td><label><input type="checkbox" name="recruit_id[]" value="<?php echo $spread['recruit_id'];?>" /></label></td>
    <td><?php if($spread['cate_id']==5){?>
        <a class="job-title" href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $spread['recruit_id'];?>.html" target="_blank"><?php echo $spread['recruit_name'];?></a> <img src="http://127.0.0.1/hdjob/templates/default/images/ding.gif" alt="" /> 
        <?php  }elseif($spread['cate_id']==2){ ?>
        <a class="job-title" href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $spread['recruit_id'];?>.html" target="_blank"><?php echo str_ireplace($keywords,$keywords_replace,$spread['recruit_name']);?></a> <img src="http://127.0.0.1/hdjob/templates/default/images/ji_.gif" alt="" /> 
        <?php  }elseif($spread['cate_id']==6){ ?>
        <a class="job-title" style="color: <?php echo $spread['color'];?>" href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $spread['recruit_id'];?>.html" target="_blank"><?php echo $spread['recruit_name'];?></a>
        <?php  }else{ ?><a class="job-title" href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $spread['recruit_id'];?>.html" target="_blank"><?php echo $spread['recruit_name'];?></a><?php }?></td>
	<td c_id="<?php echo $spread['uid'];?>"><a href="http://127.0.0.1/hdjob/index.php/index/index/company/id/<?php echo $spread['uid'];?>.html" target="_blank" class="job-company"><?php echo $spread['company_name'];?></a></td>
	<td><?php echo $spread['address'];?></td>
	<td><?php echo $spread['work_exp'];?></td>
	<td><?php echo date('Y-m-d',$spread['refresh_date']);?></td>
</tr>
<tr class="job-info">
	<td></td>
	<td colspan="4">
        <div class="company-info">
        <span>公司性质：<?php echo $spread['company_property'];?></span>
        <span>公司规模：<?php echo $spread['company_scope'];?></span>
        <span>学历要求：<?php echo $spread['degree'];?></span>
        <span>月薪：<?php echo $spread['salary'];?></span>
        </div>
        <div class="jobs-desc">
        <?php echo str_ireplace($keywords,$keywords_replace,$spread['seo_desc']);?>……
        </div>
    </td>
    <td class="apply-button">
    <a href="<?php echo $spread['recruit_id'];?>"><img src="http://127.0.0.1/hdjob/templates/default/images/list_36.gif" alt="" /></a>
    </td>
</tr>
    <?php }?><?php endif;?>
    
    <!--/遍历推广职位-->
    <!--遍历普通职位-->
    <?php if(is_array($jobs)):?><?php  foreach($jobs as $job){ ?>
    <tr class="jobs-simple">
	<td><label><input type="checkbox" name="recruit_id[]" value="<?php echo $job['recruit_id'];?>"/></label></td>
    <td><a class="job-title" href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $job['recruit_id'];?>.html" target="_blank"><?php echo str_ireplace($keywords,$keywords_replace,$job['recruit_name']);?></a></td>
	<td c_id="<?php echo $job['uid'];?>"><a href="http://127.0.0.1/hdjob/index.php/index/index/company/id/<?php echo $job['uid'];?>.html" target="_blank" class="job-company"><?php echo $job['company_name'];?></a></td>
	<td><?php echo $job['address'];?></td>
	<td><?php echo $job['work_exp'];?></td>
	<td><?php echo date('Y-m-d',$job['refresh_date']);?></td>
</tr>
<tr class="job-info">
	<td></td>
	<td colspan="4">
        <div class="company-info">
        <span>公司性质：<?php echo $job['company_property'];?></span>
        <span>公司规模：<?php echo $job['company_scope'];?></span>
        <span>学历要求：<?php echo $job['degree'];?></span>
        <span>月薪：<?php echo $job['salary'];?></span>
        </div>
        <div class="jobs-desc">
        <?php echo str_ireplace($keywords,$keywords_replace,$job['seo_desc']);?>……
        </div>
    </td>
    <td class="apply-button">
    <a href="<?php echo $job['recruit_id'];?>"><img src="http://127.0.0.1/hdjob/templates/default/images/list_36.gif" alt="" /></a>
    </td>
</tr>
    <?php }?><?php endif;?>
    <!--/遍历普通职位-->
<tr class="list-footer">
    <td colspan="3">
        <div class="select-all"><label id="select-button" for="select-all"><input type="checkbox" name="" id="select-all" />全选</label></div>
        <div class="tool">
            <a href="" id="apply" class="apply" type="apply">申请选中职位</a>
            <a href="" id="favorite" class="favorite" type="favorite">收藏选中职位</a>
        </div>
    </td>
    <td colspan="3"><div class="page-list"><?php echo $page;?></div></td>
</tr>
</table>
</div>

</div>
<style type="text/css">
.d-content select{
    width:130px;
    background:none;
    height:25px;
    border:1px #B7B7B7 solid;
}
</style>
<script type="text/javascript">
var url='http://127.0.0.1/hdjob/index.php',
    app='http://127.0.0.1/hdjob/index.php/index';
</script>
<script type="text/javascript" src="http://127.0.0.1/hdjob/templates/default/js/search.js"></script>

</body>

</html>


