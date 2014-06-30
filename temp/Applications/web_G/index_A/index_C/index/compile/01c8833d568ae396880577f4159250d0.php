<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_CONFIG['web_name'];?></title>
<meta name="keywords" content="PHP培训,PHP视频教程" />
<meta name="description" content="后盾网顶尖PHP培训 内容全面 全程实战！传授宝贵开发经验，200课时视频教程免费下载，这是实力的保证！010-64825057" />
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/base.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/index.css"/>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="http://127.0.0.1/hdjob/templates/default/js/textScroll.js"></script>
</head>

<body>
<!-- -->

<IFRAME id="iframepage" name="left" hspace="0" vspace="0" frameBorder=0 scrolling=no src="http://127.0.0.1//hdjob/header.html"  allowTransparency="true" width="100%"></IFRAME> 
<script type="text/javascript" language="javascript"> 
    parent.document.all("iframepage").style.height=document.body.scrollHeight; 
    parent.document.all("iframepage").style.width=document.body.scrollWidth; 
</script> 
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
<!--banner-->
<div id="banner">
<div id="login-panel">
    <div id="login-head">用户登录</div>
    <div id="login-input">
        <form method="post" name="" action="http://127.0.0.1/hdjob/index.php/index/auth/login.html">
            <div class="input-bar">
                <input type="text" id="username" maxlength="20" name="name" value="输入用户名/Email" />
            </div>
            <div class="input-bar">
                <input type="text" id="password" value="输入密码">
                    <input type="password" name="pwd" style="display:none;" />
            </div>
            <div class="validate-bar">
                <input type="text" name="code" id="validate" value="输入验证码" />
                <a href="#"><img src="http://127.0.0.1/hdjob/index.php/index/auth/validateCode.html" onclick="javascript:this.src='http://127.0.0.1/hdjob/index.php/index/auth/validateCode?next='+Math.random();return false;" /> </a>
            </div>
            <div class="login-bar">
                <input type="submit" name="login" class="login-button" value="" />
                <div class="checkbox">
                    <input type="checkbox" name="auto" id="auto" value="1" />
                    <label for="auto">自动登录</label>
                    <!-- <a href="###">忘记密码</a>  -->
                </div>
            </div>
        </form>
    </div>
    <div id="login-bottom"> <a href="http://127.0.0.1/hdjob/index.php/index/profile/createNewRes"><em class="fn-hide">写简历</em></a><a href="http://127.0.0.1/hdjob/index.php/register.html"><em class="fn-hide">注册</em></a> </div>
</div>
<!--right-->
<div id="banner-right"> 
    <!--city-list-->
    <div id="city-list" style="position:relative;" class="fn-clear"> <span>城市频道</span>
        <ul>
            <?php $db=M('city');$citys=$db->cache(86400)->field('name,pinyin')->where('is_open=1')->limit(15)->findall();?><?php if (is_array($citys)):?><?php foreach ($citys as $city):?>
            <li <?php if(strpos(ip::area(),$city['name'])!==false){?>class="city-active"<?php }?>><a href="http://127.0.0.1/hdjob/index.php/<?php echo $city['pinyin'];?>"><?php echo $city['name'];?></a></li>
            <?php endforeach;endif;?>
            <li id="city-more"><a href="http://127.0.0.1/hdjob/index.php/citymap.html">更多</a><span></span></li>
        </ul>
    </div>
    <!--/city-list-->
    <div id="search-area"> 
        <!--left-->
        <div id="search-l"> <span id="input-area">
                <form method="get" id="search" action="http://127.0.0.1/hdjob/index.php/index/search">
                    <input name="keywords" id="keywords" class="input-word" type="text" onclick="javascript:this.value='';" value="输入职位名称" />
                    <input class="search-img" type="submit" value=" " />
                </form>
            </span> <span id="hot-keyword"> 热门关键字：<?php $db=M('keywords');$keywords=$db->field('keyword,red')->order('nums DESC')->limit(8)->findall();?><?php if(is_array($keywords)):?><?php foreach ($keywords as $keyword):?><a href="http://127.0.0.1/hdjob/index.php/index/search/index/keywords/<?php echo $keyword['keyword'];?>" <?php if($keyword['red']){?>class="hot"<?php }?>><?php echo $keyword['keyword'];?></a><?php endforeach;endif;?></span> </div>
        <!--/left-->
        <div id="search-r">
            <div class="search-type">
                <a href="http://127.0.0.1/hdjob/index.php/index/search/index/refresh_date/10">最近更新职位</a>
                <a href="http://127.0.0.1/hdjob/index.php/index/search">高级搜索</a>
            </div>
        </div>
    </div>
    <!--/right-->

    <div id="top-ads" class="fn-clear">
        <dl>
            <dt id="houdun-service-b"></dt>
            <dd id="houdun-service"> <a href="http://127.0.0.1/hdjob/index.php/register/company.html">企业注册</a> <a href="">招聘推广</a> <a href="">企业服务</a> <a href="">求职帮助</a> </dd>
            <!--/-->
            <dt id="fast-news-b"></dt>
            <dd id="fast-news" class="top-ads-list">
                <ul>
                    <?php $db = M('ads');$adss =$db->field('href,text,path,color,width,height,uid')
        ->where(" state=1 AND cate =6 AND endtime >".time())
        ->limit(3)->findall();?><?php if(is_array($adss)):?><?php foreach ($adss as $ads):?>
                    <li><a href="<?php if(substr($ads['href'],0,4)=='http'){?><?php echo $ads['href'];?><?php  }else{ ?>http://127.0.0.1//hdjob/<?php echo $ads['href'];?><?php }?>"><?php echo $ads['text'];?></a></li>
                    <?php endforeach;?><?php endif;?>
                </ul>
            </dd>
            <!--/-->
            <dt id="hot-recruit-b"></dt>
            <dd id="hot-recruit" class="top-ads-list">
                <ul>
                    <?php $db = M('ads');$adss =$db->field('href,text,path,color,width,height,uid')
        ->where(" state=1 AND cate =6 AND endtime >".time())
        ->limit(3)->findall();?><?php if(is_array($adss)):?><?php foreach ($adss as $ads):?>
                    <li><a href="<?php if(substr($ads['href'],0,4)=='http'){?><?php echo $ads['href'];?><?php  }else{ ?>http://127.0.0.1//hdjob/<?php echo $ads['href'];?><?php }?>"><?php echo $ads['text'];?></a></li>
                    <?php endforeach;?><?php endif;?>
                </ul>
            </dd>
        </dl>
    </div>
    <!--top-ads--> 
</div>
</div>
<!--/banner-->
<div id="banner-ads">
<ul>
    <?php $db = M('ads');$adss =$db->field('href,text,path,color,width,height,uid')
        ->where(" state=1 AND cate =7 AND endtime >".time())
        ->limit(3)->findall();?><?php if(is_array($adss)):?><?php foreach ($adss as $ads):?>
    <li class="pic-list"> <a href="<?php if(substr($ads['href'],0,4)=='http'){?><?php echo $ads['href'];?><?php  }else{ ?>http://127.0.0.1//hdjob/<?php echo $ads['href'];?><?php }?>"><img src="<?php echo $ads['path'];?>" width="322" height="53" /></a></li>
    <?php endforeach;?><?php endif;?>
</ul>
</div>

<!--热门职位-->
<div id="hot-job-ads">
<div>
    <h2></h2>
</div>
<ul>
    <?php $db=M('recruit');
        $recruits=$db->field('recruit_id,recruit_name,company_name,uid')->where('state=1  AND verify=1 AND expiration_time > '.time())
        ->order('views DESC,created DESC')->limit(30)->findall();?><?php if(is_array($recruits)):?><?php foreach($recruits as $recruit):?>
    <li><a href="http://127.0.0.1/hdjob/index.php/index/index/company/id/<?php echo $recruit['uid'];?>" target="_blank"><?php echo $recruit['company_name'];?></a><span><a href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $recruit['recruit_id'];?>" target="_blank"><?php echo $recruit['recruit_name'];?></a></span></li>
    <?php endforeach;endif;?>
</ul>
</div>
<!--/热门职位--> 

<!--紧急招聘-->
<div id="urgent-ads">
<div>
    <h2></h2>
</div>
<ul>
    <?php $db = M('ads');$adss =$db->field('href,text,path,color,width,height,uid')
        ->where(" state=1 AND cate =8 AND endtime >".time())
        ->limit(3)->findall();?><?php if(is_array($adss)):?><?php foreach ($adss as $ads):?>
    <li><a href="<?php if(substr($ads['href'],0,4)=='http'){?><?php echo $ads['href'];?><?php  }else{ ?>http://127.0.0.1//hdjob/<?php echo $ads['href'];?><?php }?>" target="_blank"><img src="<?php if(substr($ads['path'],0,4)=='http'){?><?php echo $ads['path'];?><?php  }else{ ?>http://127.0.0.1//hdjob/<?php echo $ads['path'];?><?php }?>" width="109" height="45" /></a>
        <p><?php echo $ads['text'];?></p>
    </li>
    <?php endforeach;?><?php endif;?>
</ul>
</div>
<!--/紧急招聘--> 
<!--最新简历-->
<div id="update-resume">
<div id="resume-list">
    <div><span>最新简历</span><a href="#">更多</a></div>
    <ul>
        <?php $db=V('resume');$db->view=array('resume_basic'=>array('type'=>'left','on'=>'resume.resume_id=resume_basic.resume_id'));$resumes=$db->field('resume.resume_id,resume_name,created,avatar,views,name,gender,work_exp')->where('')->order('created DESC')->findall();?><?php if(is_array($resumes)):?><?php foreach($resumes as $resume):?><?php $data=new data("resume_basic");$resume=$data->convert($resume);?>
        <li><a href=""><?php echo mb_substr($resume['name'],0,1,'utf-8');?><?php echo str_repeat('*',mb_strlen($resume['name'],'utf-8')-1);?></a><?php echo $resume['gender'];?>&nbsp;&nbsp;<?php echo $resume['work_exp'];?><span><a  href="http://127.0.0.1/hdjob/index.php/index/profile/viewResume/id/<?php echo $resume['resume_id'];?>"><?php echo $resume['resume_name'];?></a></span></li>
        <?php endforeach;endif;?>
    </ul>
</div>

<!--最新头像简历-->
<div id="image-resume">
    <dl>
        <dt><span>最新头像简历</span><a href="#">更多&gt;&gt;</a></dt>
        <dd>
            <ul>
                <?php $db=V('resume');$db->view=array('resume_basic'=>array('type'=>'left','on'=>'resume.resume_id=resume_basic.resume_id'));$resumes=$db->field('resume.resume_id,resume_name,created,avatar,views,name')->where('avatar !=""')->order('created DESC')->findall();?><?php if(is_array($resumes)):?><?php foreach($resumes as $resume):?><?php $data=new data("resume_basic");$resume=$data->convert($resume);?>
                <li> <a href="#"><img src="http://127.0.0.1//hdjob/<?php echo $resume['avatar'];?>" /></a>
                    <div> <span>
                            <p><?php echo $resume['name'];?></p>
                            <p><a href="http://127.0.0.1/hdjob/index.php/index/profile/viewResume/id/<?php echo $resume['resume_id'];?>" target="_blank"><?php echo $resume['resume_name'];?></a></p>
                        </span>
                        <p>浏览：<?php echo $resume['views'];?>次 <?php echo date('Y-m-d',$resume['created']);?></p>
                    </div>
                </li>
                <?php endforeach;endif;?>
                
            </ul>
        </dd>
    </dl>
</div>
<!--/最新头像简历--> 
</div>
<div id="copy">
<div id="shortcut-link">
    <?php $db=M('channel');$pages=$db->field('title,href,id')->where('type=2 && pid!=0')->limit(4)->findall();?><?php if(is_array($pages)):?><?php foreach($pages as $page):?>
        <dl>
            <dt><?php echo $page['title'];?></dt>
            <dd>
                <ul>
                    <li><a href="http://127.0.0.1//hdjob<?php echo $page['href'];?>"><?php echo $page['title'];?></a></li>
                </ul>
            </dd>
        </dl>
    <?php endforeach;endif;?>
</div>
<!--/快捷链接-->
<div id="tel-area">
    <div id="tel"><em class="fn-hide">一个快乐的小电话</em></div>
    <div id="tel-list">
        <h2>招聘咨询电话：</h2>
        <?php echo $_CONFIG['tel'];?>
        <h2>客户服务电话：</h2>
        <?php echo $_CONFIG['service_tel'];?>
    </div>
</div>

</div>

<script type="text/javascript" src="http://127.0.0.1/hdjob/templates/default/js/index.js"></script>
<IFRAME id="iframepage" name="left" hspace="0" vspace="0" frameBorder=0 scrolling=no src="http://127.0.0.1//hdjob/footer.html"  allowTransparency="true" width="100%"></IFRAME> 
</body>
</html>