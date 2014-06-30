<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户注册--<?php echo $_CONFIG['web_name'];?></title>
<meta name="keywords" content="PHP培训,PHP视频教程" />
<meta name="description" content="北京后盾计算机技术培训有限责任公司是专注于培养中国互联网优秀的程序语言专业人才的专业型培训机构。" />
<link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/register.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/common.css"/>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
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

<!--reg-->
<div id="reg"> 
  <!--reg-center-->
  <div id="reg-center"> 
    <!--reg-area-->
    <div id="reg-input-info">
      <div id="reg-type" class="fn-clear"><a href="http://127.0.0.1/hdjob/index.php/register.html" class="reg-type <?php if($type=='user'){?>reg-type-active<?php }?>">个人注册</a><a href="http://127.0.0.1/hdjob/index.php/register/company.html" class="reg-type  <?php if($type=='company'){?>reg-type-active<?php }?>">企业注册</a></div>
      <div id="form-area" >
        <form action="http://127.0.0.1/hdjob/index.php/index/auth/register.html" method="post">
          <table>
            <tr>
              <th>用户名：</th>
              <td><input name="name" id="name" type="text" maxlength="20" /></td>
              <td class="tips">可以输入数字、字母、下划线，必须以字母开头。6-20位<br />
                例如：houdunwang</td>
            </tr>
            <tr>
              <th>密码：</th>
              <td><input name="pwd" id="pwd" type="password" maxlength="20" /></td>
              <td class="tips">6-20个字符，建议使用复杂的<br />
                密码（密码中包含字母，标点符号，大写字母）</td>
            </tr>
            <tr>
              <th>重复密码：</th>
              <td><input name="re-pwd" id="re-pwd" type="password" maxlength="20" /></td>
              <td class="tips">重复用户密码</td>
            </tr>
            <tr>
              <th>Email：</th>
              <td><input name="email" id="email" type="text" /></td>
              <td class="tips">Email地址</td>
            </tr>
              <?php if(C('AUTH_REG_CODE')){?>
            <tr>
              <th>验证码：</th>
              <td><input name="code" type="text" /></td>
              <td class="tips"><img title="点击更换验证码" src="http://127.0.0.1/hdjob/index.php/index/auth/validateCode.html" onclick="javascript:this.src='http://127.0.0.1/hdjob/index.php/index/auth/validateCode?1.html'" /></td>
            </tr>
              <?php }?>
            <tr>
                <th></th>
                <td colspan="2"><input type="checkbox" class="checkbox" name="agree" id="agree" value="1" style="" /><label for="agree">我同意<a href="" id="read-registration">《后盾招聘注册协议》</a><label></td>
            </tr>
            <tr>
                <td><input type="hidden" name="id" value="<?php echo $type;?>" /></td>
              <td colspan="2"><input name="register" type="submit" value="提交注册" class="reg-button" /></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
    <!--/reg-area--> 
    <!--suggest-area-->
    <div id="reg-suggest">
      <div id="has-account">
        <h2>已有账号？</h2>
        <a href="http://127.0.0.1/hdjob/index.php/login.html">马上登陆</a>
        </div>
      <div id="account-instruct">
        <h2>个人用户</h2>
        <span> 填写个人简历，在线投递简历，在线申请职位，搜索职位... </span>
        <h2>企业用户</h2>
        <span> 发布招聘信息，查看人才简历，搜索简历，在线邀请面试... </span>
      </div>
    </div>
    <!--/suggest-area--> 
    <div id="registration" class="fn-hidex">
      <h2 class="data-title">注册协议</h2>
      <div id="registration-con">
        <?php echo nl2br($_CONFIG['registration']);?>
        </div>
      </div>
  </div>
  <!--/reg-center--> 
</div>
<!--/reg-->
<div id="f-link">
  <h2>友情链接：</h2>
  <ul>
    <?php $db=M('link');$links=$db->cache(86400)->field('web_name,href,logo')->where('state=1 ')->order('sort')->limit(8)->findall();?><?php if(is_array($links)):?><?php foreach ($links as $link):?>
    <li><a href="<?php echo $link['href'];?>"><?php echo $link['web_name'];?></a></li>
    <?php endforeach;endif;?>
  </ul>
</div>
<div id="copyright"> <a href="http://www.houdunwang.com" id="bottom-logo"><em class="fn-hide">后盾logo</em></a> <span id="info">联系地址：北京市朝阳区小营路5号四方大厦 版权所有：<a href="http://www.houdunwang.com">后盾网</a> 京ICP备10027771号-1 站长统计 <br />
  All rights reserved, houdunwang.com services for Beijing 2008-2011</span> </div>
<script type="text/javascript">
    var url='http://127.0.0.1/hdjob/index.php';
</script>
<script type="text/javascript" src="http://127.0.0.1/hdjob/templates/default/js/regValidate.js"></script>
</body>
</html>
