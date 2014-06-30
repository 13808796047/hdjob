<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/bootstrap/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/web/backend/templates/css/home.css"/>
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/web/backend/templates/css/public.css"/>
    <script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
  </head>
  <body style="padding:20px;">
    <?php
    $db=M();
    $version=$db->query('SELECT VERSION() AS MYSQL_VERSION');
    ?>
    <div id="panel-left">
      <div >
        <div class="box">
            <div class="title">
                <h4>
                    <span class="icon-user"></span>
                    <span>个人信息</span>
                </h4>
                <a href="#" class="minimize hide"></a>
            </div>
            <div class="content" style="display: block;">
                    <p>你好，<?php echo $_SESSION['username'];?></p>
                    <p>所属角色：<?php echo implode('、',$_SESSION['role']['rtitle']);?></p>
                    <p>上次登录时间：<?php echo date('Y-m-d H:i:s',$_SESSION['last_login']);?></p>
                    <p>上次登录IP：<?php echo $_SESSION['last_ip'];?></p>
                </table>
            </div>
        </div>
      </div>
       <div >
          <div class="box">
              <div class="title">
                  <h4>
                      <span class="icon-exclamation-sign"></span>
                      <span>安全提示</span>
                  </h4>
                  <a href="#" class="minimize hide"></a>
              </div>
              <div class="content sys" style="display: block;">
                <p>建议将hdphp目录权限修改为644</p>
                <p>建议将web目录权限修改为644</p>
                <p>系统安装完成后将install目录删除</p>
              </div>
          </div>
        </div>
        <div >
          <div class="box">
              <div class="title">
                  <h4>
                      <span class="icon-flag"></span>
                      <span>系统信息</span>
                  </h4>
                  <a href="#" class="minimize hide"></a>
              </div>
              <div class="content" style="display: block;">
                <p>后盾求职招聘系统 <span id="version"><?php echo $_CONFIG['version'];?></span><span id="new-version"><a class="success" href="">有新版本发布了，马上更新？</a></span></p>
                <p class="hide" id="show-new-version"></p>
                <p>操作系统：<?php echo PHP_OS;?></p>
                <p>PHP：<?php echo PHP_VERSION;?></p>
                <p>服务器环境：<?php echo $_SERVER['SERVER_SOFTWARE'];?></p>
                <p>MySQL版本：<?php echo $version[0]['MYSQL_VERSION'];?></p>
              </div>
          </div>
        </div>
        </div>
        <div id="panel-right">
        <div >
          <div class="box">
              <div class="title">
                  <h4>
                      <span class="icon-tags"></span>
                      <span>快捷操作</span>
                  </h4>
                  <a href="#" class="minimize hide"></a>
              </div>
              <div class="content fast-opt" style="display: block;">
                 <a href="http://127.0.0.1/hdjob/index.php/backend/content/addArc">发布文章</a>
                 <a href="http://127.0.0.1/hdjob/index.php/backend/resume/resumeList">管理简历</a>
                 <a href="http://127.0.0.1/hdjob/index.php/backend/company/recruitList">管理招聘</a>
                 <a href="http://127.0.0.1/hdjob/index.php/backend/webConfig/emailConfig">邮箱配置</a>
                 <a href="http://127.0.0.1/hdjob/index.php/backend/dataModel/modelList">模型管理</a>
                 <a href="http://127.0.0.1/hdjob/index.php/backend/dataModel/category">地区管理</a>
                 <a href="http://127.0.0.1/hdjob/index.php/backend/dataModel/linkageCateList">联动数据</a>
                 <div style="clear:both"></div>
              </div>
          </div>
        </div>
        <div >
          <div class="box">
              <div class="title">
                  <h4>
                      <span class="icon-fire"></span>
                      <span>网站动态</span>
                  </h4>
                  <a href="#" class="minimize hide"></a>
              </div>
              <div class="content" style="display: block;">
                <p>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  今日注册：<span class="success"><?php echo $db->table('user')->where(array('created'=>array('gt'=>strtotime(date('Y-m-d')))))->count();?> </span>人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  发布职位：<span class="success"><?php echo $db->table('recruit')->where(array('created'=>array('gt'=>strtotime(date('Y-m-d')))))->count();?> </span>个&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  新增简历：<span class="success"><?php echo $db->table('resume')->where(array('created'=>array('gt'=>strtotime(date('Y-m-d')))))->count();?> </span>份
                </p>
                <p>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  总会员&nbsp;&nbsp;：<span class="success"><?php echo $db->table('user')->count();?> </span>人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  发布文章：<span class="success"><?php echo $db->table('article')->count();?> </span>篇&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  有效推广：<span class="success"><?php echo $db->table('spread')->where(array('endtime'=>array('gt'=>time())))->count();?> </span>个
                </p>
              </div>
          </div>
        </div>
        <div >
          <div class="box">
              <div class="title">
                  <h4>
                      <span class="icon-question-sign"></span>
                      <span>帮助中心</span>
                  </h4>
                  <a href="#" class="minimize hide"></a>
              </div>
              <div class="content fast-opt help-opt" style="display: block;">
                <a href="http://www.houdunwang.com" target="_blank">后盾网</a>
                <a href="http://bbs.houdunwang.com" target="_blank">后盾论坛</a>
                <a href="http://www.houdunwang.com/gaoxin.html" target="_blank">后盾PHP培训</a>
                 <div style="clear:both"></div>
              </div>
          </div>
        </div>
        <div >
          <div class="box">
              <div class="title">
                  <h4>
                      <span class="icon-home"></span>
                      <span>作者</span>
                  </h4>
                  <a href="#" class="minimize hide"></a>
              </div>
              <div class="content" style="display: block;">
                <p>核心开发：<a href="mailto:zhangbo1248@gmail.com">张博</a></p>
                <p>技术支持：<a href="mailto:houdunwangxj@gmail.com">向军</a></p>
                <p>通过HDPHP构建</p>
              </div>
          </div>
        </div>
         <div >
          <div class="box">
              <div class="title">
                  <h4>
                      <span class="icon-fire"></span>
                      <span>官方动态</span>
                  </h4>
                  <a href="#" class="minimize hide"></a>
              </div>
              <div class="content" id="news" style="display: block;">
               <p><a href="http://www.houdunwang.com/zuopinzhanshi.html" target="_blank">后盾学员作品展示</a></p>
               <p><a href="http://www.houdunwang.com/hdinfo" target="_blank">基于HDPHP开发的后盾分类信息强势发布</a></p>
               <p><a href="http://www.houdunwang.com/gaoxin.html">最新PHP课程介绍</a></p>
              </div>
          </div>
        </div>
</div>
        <style type="text/css">
        #panel-left{
          width: 45%;
          float: left;
          margin-right: 20px;
        }
        #panel-right{
          width: 45%;
          float: left;
        }
        .fast-opt a{
          padding:5px 20px;
        }
        .help-opt{
          padding:5px 10px;
        }
        #new-version{
          display: none;
          padding-left: 10px;
        }
        </style>
<script type="text/javascript" src="http://www.houdunwang.com/hdjob/product/index.php/product/index/index/version/<?php echo $_CONFIG['version'];?>/product/hdjob"></script>
<script type="text/javascript">
  $('.title').hover(function(){
    $(this).children('.minimize').removeClass('hide');
  },function(){
    $(this).children('.minimize').addClass('hide');
  });
  $('.minimize').toggle(function(){
    $(this).parent().next().slideUp();
  },function(){
    $(this).parent().next().slideDown();
  });
</script>
  </body>
</html>
