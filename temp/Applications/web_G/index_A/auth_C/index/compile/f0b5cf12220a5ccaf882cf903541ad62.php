<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录页面---<?php echo $_CONFIG['web_name'];?></title>
<meta name="keywords" content="PHP培训,PHP视频教程" />
<meta name="description" content="北京后盾计算机技术培训有限责任公司是专注于培养中国互联网优秀的程序语言专业人才的专业型培训机构。" />
<link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/login.css"/>
</head>

<body>
<div id="top">
  <div id="top-center"> <a id="logo" href="http://127.0.0.1//hdjob"><img src="http://127.0.0.1/hdjob/templates/default/images/logo.png" width="250" /></a>
    <div id="create"> <span>第一次使用后盾人才系统？</span><a href="http://127.0.0.1/hdjob/index.php/register.html" class="create-account">创建账户</a> </div>
  </div>
</div>
<div id="login-panel">
  <div id="login-left">
    <div id="left-head">
      <h2>Houdun Recruitment System</h2>
      <span>由后盾提供的人才招聘服务系统</span>
      <p>HRS的开发理念是：求职招聘可以更加直观、高效而实用，HRS 具有以下特点：</p>
    </div>
    <ul class="features">
      <li><img src="http://127.0.0.1/hdjob/templates/default/images/filing_cabinet-g42.png" width="42" height="42" />
        <p class="title">个性化招聘</p>
        <p>有丰富的企业资料的和个人资料供你参考</p>
      </li>
      <li><img src="http://127.0.0.1/hdjob/templates/default/images/filing_cabinet-g42.png" width="42" height="42" />
        <p class="title">方便快捷</p>
        <p>仅需简单的步骤即可快速发布招聘</p>
      </li>
      <li><img src="http://127.0.0.1/hdjob/templates/default/images/filing_cabinet-g42.png" width="42" height="42" />
        <p class="title">灵活管理</p>
        <p>会员中心拥有方便而灵活的功能</p>
      </li>
    </ul>
  </div>
  <div id="login-right">
    <form action="http://127.0.0.1/hdjob/index.php/index/auth/login.html" method="post">
      <h2>登录</h2>
      <div id="login-input">
        <label for="username"><strong>用户名：</strong></label>
        <input type="text" name="name" id="username" />
        
        <label for="password"><strong>密码：</strong></label>
        <input type="password" name="pwd" id="password" />
        <?php if($show_captcha){?>
        <label for="code"><strong>验证码：</strong></label>
        <input type="text" name="code" id="code" style="width:100px;" />
        <img src="http://127.0.0.1/hdjob/index.php/index/auth/validateCode.html" title="点击更换验证码" onclick="javascript:this.src='http://127.0.0.1/hdjob/index.php/index/auth/validateCode?'+Math.random()+'.html'"/>
        <?php }?>
      </div>
      <div id="login-sub">
        <input name="login" class="login-botton" type="submit" value="登录" />
        <input name="remember" type="checkbox" id="remember" value="1" />
        <label for="remember">记住我</label>
      </div>
      <!-- <p><a href="###">账户遇到问题？</a></p> -->
    </form>
    <script type="text/javascript">
    	document.getElementById('username').focus();
    </script>
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