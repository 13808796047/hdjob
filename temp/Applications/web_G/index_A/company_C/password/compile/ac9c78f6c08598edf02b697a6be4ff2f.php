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