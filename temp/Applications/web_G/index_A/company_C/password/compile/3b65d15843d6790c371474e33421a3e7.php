<?php if(!defined("PATH_HD"))exit;?>
<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['company_name'];?>  Powered by <?php echo $_CONFIG['web_name'];?></title>
<meta name="keywords" content="<?php echo $_CONFIG['keyword'];?>" />
<meta name="description" content="<?php echo $_CONFIG['desc'];?>" />
<link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/company.css"/>
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/common.css"/>
</head>
<body>
<div id="public">
<!--page-nav-->
<div id="page-nav" class="fn-clear">
<div id="company-logo"><a href="http://127.0.0.1/hdjob/index.php"><img src="http://127.0.0.1/hdjob/templates/default/images/logo.png" width="210" height="60" /></a></div>
    <div id="page-nav-list">
        <ul>
            <li <?php if(METHOD=='index'){?>class="nav-active"<?php }?>><a href="http://127.0.0.1/hdjob/index.php/index/company.html">企业中心</a></li>
            <li <?php if(METHOD=='recruit'){?>class="nav-active"<?php }?>><a href="http://127.0.0.1/hdjob/index.php/index/company/recruit.html">职位管理</a></li>
            <li <?php if(METHOD=='template'||METHOD=='spread'){?>class="nav-active"<?php }?>><a href="http://127.0.0.1/hdjob/index.php/index/company/template.html">企业推广</a></li>
            <li <?php if(METHOD=='data'){?>class="nav-active"<?php }?>><a href="http://127.0.0.1/hdjob/index.php/index/company/data.html">企业资料</a></li>
            <li <?php if(METHOD=='account'){?>class="nav-active"<?php }?>><a href="http://127.0.0.1/hdjob/index.php/index/company/account">账户管理</a></li>
            <li><a href="http://127.0.0.1/hdjob/index.php/index/index/company/id/<?php echo $_SESSION['uid'];?>.html">企业首页</a></li>
        </ul>
        <div id="search-resume">
            <form action="http://127.0.0.1/hdjob/index.php/index/search/resume" method="get">
                <input type="text" name="keywords" placeholder="搜简历" />
                <button id="search-button" type="submit"></button>
            </form>
        </div>
    </div>
</div>
</div>
<!--/page-nav-->
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/company_account.css"/>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jqueryValidate/jquery.validate.min.js"></script>
<div id="content">
    <!--opt-->
    <div id="opt">
        <div id="opt-menu">
            <dl>
                <dt>企业中心</dt>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/recruit">招聘管理</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/isseuRecruit">发布职位</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/optLog">简历搜索</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/receiveApply">收到的职位申请</a></dd>
                <dt>企业资料</dt>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/data" class="view-data">查看企业资料</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/optLog" class="">操作日志</a></dd>
                <dt>企业推广</dt>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/template.html">企业模板</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/5.html">职位置顶</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/2.html">紧急招聘</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/1.html">推荐职位</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/6.html">职位变色</a></dd>
            </dl>
        </div>
        <!--opt-area-->
        <div id="opt-area">
            <div class="tabs-nav">
                <ul>
                    <li><a href="http://127.0.0.1/hdjob/index.php/index/company/account">营业执照</a></li>
                    <li><a href="http://127.0.0.1/hdjob/index.php/index/company/logo">企业LOGO</a></li>
                    <li><a href="http://127.0.0.1/hdjob/index.php/index/company/proAuth">Email认证</a></li>
                    <li class="tabs-nav-active"><a href="http://127.0.0.1/hdjob/index.php/index/company/password">修改密码</a></li>
                </ul>
                <div class="fn-clear"></div>
            </div>
            <div id="account-set">
                <form action="http://127.0.0.1/hdjob/index.php/index/company/password" validate="true" method="post">
                <table class="table-form">
                    <tr>
                        <th>旧密码：</th>
                        <td><input type="text" class="required" name="old_pwd" id=""></td>
                    </tr>
                    <tr>
                        <th>新密码：</th>
                        <td><input type="password" class="required" name="pwd" minlength="6" maxlength="20" id="pwd"></td>
                    </tr>
                    <tr>
                        <th>重复密码：</th>
                        <td><input type="password" class="required" equalTo="#pwd" name="re_pwd" id=""></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><button class="btn btn-large">更新</button> </td>
                    </tr>
                </table>
                </form>
            </div>
           
        </div>
        <!--/opt-area-->
    </div>
    <!--/opt--> 
</div>
<?php if(!defined("PATH_HD"))exit;?>
<!-- <div id="copy-bottom">
    <div>
        <p><?php echo $_CONFIG['copyright'];?></p>
    </div>
</div>
</body>
</html> -->