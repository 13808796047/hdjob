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
<link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/templates/default/css/company_index.css"/>
<script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
<div id="content">
    <!--opt-->
    <div id="opt">
        <div id="opt-menu">
            <dl>
                <dt>职位管理</dt>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/issueRecruit.html">发布职位</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/recruit.html">职位管理</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/search/resume">简历搜索</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/receiveApply">收到的职位申请</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/viewFav">简历收藏夹</a></dd>
                <dt>企业推广</dt>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/template.html">企业模板</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/5.html">职位置顶</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/2.html">紧急招聘</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/1.html">推荐职位</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/addSpread/cate/6.html">职位变色</a></dd>
                <dt>企业资料</dt>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/data" class="view-data">企业资料</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/index/company/id/<?php echo $_SESSION['uid'];?>" class="view-data">企业首页</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/proAuth" class="view-data">邮箱认证</a></dd>
                <dd><a href="http://127.0.0.1/hdjob/index.php/index/company/optLog">操作日志</a></dd>
            </dl>
        </div>
        <!--opt-area-->
        <div id="opt-area">
            <!--企业资料-->
            <div id="info" class="fn-clear"> 
                <div class="data-title"><h2>帐号信息</h2></div>
                <!--资料-->
                <div id="data">
                    <div id="update">
                        <div id="company-name">
                            <div id="companyN"><?php echo $company['name'];?></div>
                            <a href="http://127.0.0.1/hdjob/index.php/index/company/account" <?php if($company['license_verify']==1){?>class="license-pass" title="营业执照已认证"<?php  }else{ ?>class="license-status" title="营业执照未认证"<?php }?> ><em class="fn-hide">营业执照认证</em></a>
                            <a href="http://127.0.0.1/hdjob/index.php/index/company/proAuth" <?php if($company['email_verify']){?>class="email-pass" title="电子邮箱已认证"<?php  }else{ ?>class="email-status" title="Email未认证"<?php }?>><em class="fn-hide">Email认证</em></a>
                        </div>
                        <div class="c-info" id="login-log"><span>上次登录时间：<?php echo date('Y-m-d H:i:s',$_SESSION['last_login']);?></span><span>&nbsp;&nbsp;&nbsp;&nbsp;上次登录IP：<?php echo $_SESSION['last_ip'];?></span></div>
                        <div class="c-info">您已经发布了&nbsp;<span class="nums"><?php echo $company['recruit_nums'];?></span>&nbsp;个职位：您可以&nbsp;&nbsp;<a href="http://127.0.0.1/hdjob/index.php/index/company/recruit">管理职位</a>&nbsp;&nbsp;或者&nbsp;&nbsp;<a href="http://127.0.0.1/hdjob/index.php/index/company/receiveApply">查看投递人</a>&nbsp;&nbsp;</div>
                        <div class="c-info">
                            收到的职位申请：&nbsp;<span class="nums"><?php echo $receives;?></span>
                            <span class="fast-opt">
                                <a href="http://127.0.0.1/hdjob/index.php/index/company/issueRecruit.html" class="">发布招聘</a>
                            <a href="http://127.0.0.1/hdjob/index.php/index/auth/logout" class="">注销登录</a>
                            </span>
                        </div>
                    </div>
                </div>
                <!--/资料--> 
                <!--积分-->
                <div id="point">
                    <h2>我的积分</h2>
                    <p class="point-num"><span><i><?php echo $_SESSION['point'];?></i></span>&nbsp;&nbsp;点</p>
                    <div id="recharge"><a href=""><img src="http://127.0.0.1/hdjob/templates/default/images/15.gif" /></a></div>
                    <p class="point-rule"><a href="http://127.0.0.1/hdjob/index.php/index/company/optLog">积分消费明细</a></p>
                </div>
                <!--/积分--> 
            </div>
            <!--/企业资料-->
            <div id="new-recruit" class="feature-list">
                <div class="data-title"><h2>最新招聘</h2></div>
                <table class="table-list">
                    <tr>
                        <th>职位名称</th>
                        <th>招聘人数</th>
                        <th>开始时间</th>
                        <th>有效时间</th>
                        <th>结束时间</th>
                        <th>审核状态</th>
                    </tr>
                    <?php
            $db=K('company');
            $lists=$db->newRecruit('',5,'start_time desc','recruit_id,recruit_name,recruit_num,start_time,expiration_time,effective_time,verify');
            ?><?php if(is_array($lists)):?><?php foreach($lists as $list):?>
                    <tr>
                        <td><a href="http://127.0.0.1/hdjob/index.php/index/search/jobs/id/<?php echo $list['recruit_id'];?>" target="_blank"><?php echo $list['recruit_name'];?></a></td>
                        <?php if(!$list['recruit_num']){?>
                        <td>若干</td>
                        <?php  }else{ ?>
                        <td><?php echo $list['recruit_num'];?>人</td>
                        <?php }?>
                        <td><?php echo date('Y-m-d H:i:s',$list['start_time']);?></td>
                        <td><?php echo $list['effective_time'];?>天</td>
                        <td><?php echo date('Y-m-d H:i:s',$list['expiration_time']);?></td>
                        <td>
                            <?php if($list['verify']==1){?>
                            <span class="success">已通过</span>
                            <?php  }elseif($list['verify']==2){ ?>
                            <span class="sys">审核中</span>
                            <?php  }else{ ?>
                            <span class="warning">未通过</span>
                            <?php }?>
                        </td>
                    </tr>
                    <?php endforeach;endif;?>
                </table>
            </div>
            <div class="feature-list">
                <div class="data-title">
                    <h2>最新投递</h2>
                </div>
                <div>
                    <table class="table-list">
                        <tr>
                            <th>投递简历</th>
                            <th>投递职位</th>
                            <th>投递人</th>
                            <th>投递时间</th>
                        </tr>
                        <?php if(!empty($delivers)){$list_id1=0;?><?php foreach($delivers as $key=>$deliver){?><?php if($list_id1==5) break;?><?php $list_id1+=1;?>
                        <tr>
                            <td><a href="http://127.0.0.1/hdjob/index.php/index/profile/viewResume/id/<?php echo $deliver['resume_id'];?>" target="_blank"><?php echo $deliver['resume_name'];?></a></td>
                            <td><?php echo $deliver['recruit_name'];?></td>
                            <td><?php echo $deliver['name'];?></td>
                            <td><?php echo date('Y-m-d H:i:s',$deliver['sendtime']);?></td>
                        </tr>
                        <?php }?><?php }?>
                    </table>
                </div>
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