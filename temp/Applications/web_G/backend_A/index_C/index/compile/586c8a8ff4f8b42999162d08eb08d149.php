<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $_CONFIG['web_name'];?>--后台管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/web/backend/templates/css/index.css"/>
    <script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/jquery-1.7.2.min.js"></script>
  </head>
  <body>
    <div class="header">
      <div id="logo" class="fn-left">
        <h1 class="logo">后盾招聘系统</h1>
        <span class="slogan">有实力，做后盾</span>
      </div>
      <ul class="headermenu">
        <li class="home active"><a href="http://127.0.0.1/hdjob/index.php/backend">管理中心</a></li>
         <?php if(is_array($menu_list)):?><?php  foreach($menu_list as $menu){ ?>
         <li><a href="<?php echo $menu['app'];?>/<?php echo $menu['control'];?>" menuid="<?php echo $menu['id'];?>"><?php echo $menu['menu_name'];?></a></li>
         <?php }?><?php endif;?>
      </ul>
      <div class="head-right">
        <div class="userinfo user-name"><span><?php echo $_SESSION['username'];?></span>
          <div class="userpanel">
          <ul>
            <li>
              <a href="http://www.houdunwang.com"><i class="icon icon-home"></i>官方网站</a>
              <a href="http://127.0.0.1/hdjob/index.php/index/auth/logout"><i class="icon icon-user"></i>退出登录</a>
              <a href="http://127.0.0.1/hdjob/index.php/backend/index/update_cache"><i class="icon icon-folder"></i>更新缓存</a>
              <a href="http://bbs.houdunwang.com" target="_blank"><i class="icon icon-flag"></i>在线帮助</a>
            </li>
          </ul>
        </div>
        </div>
        <div class="userinfo update-cate">
          <a href="http://127.0.0.1//hdjob" style="margin-right:10px;"><span>网站首页</span></a>
          <a href="http://127.0.0.1/hdjob/index.php/backend/index/update_cache"><span>更新缓存</span></a>
        </div>
      </div>
      <style type="text/css">
      </style>
      <script type="text/javascript">
      $('.user-name').click(function(){
          $(this).children('.userpanel').slideDown();
      });
      $('.user-name').hover(function(){
      },function(){
        $(this).children('.userpanel').slideUp();
      });
        $('.headermenu li:not(.home) a').click(function() {
          var menuid=$(this).attr('menuid');
          $('.headermenu li').removeClass('active');
          $(this).parent().addClass('active');
          $.post('http://127.0.0.1/hdjob/index.php/backend/nav/showTwoMenu', 
                {'menuid':menuid}, 
                function(data){
                  $('.left-menu').html(data);
                }, 
                'html');
          return false;
        });
      </script>
    </div>
    <div id="content">
      <div id="menu">
        <ul class="left-menu">
          <li>
            <span><a href="">企业中心</a></span>
            <ul class="son">
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/company/spreadList" target="opt">推广列表</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/company/recruitList" target="opt">职位列表</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/company/spreadList" target="opt">添加推广</a></li>
            </ul>
          </li>
          <li>
            <span><a href="">用户中心</a></span>
            <ul class="son">
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/user/userList" target="opt">用户列表</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/user/addUser" target="opt">添加用户</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/user/roleList" target="opt">配置权限</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/webConfig/userConfig" target="opt">用户配置</a></li>
            </ul>
          </li>
          <li>
            <span><a href="###">广告管理</a></span>
            <ul class="son">
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/ads/addAds" target="opt">广告列表</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/ads/addAds/action/1" target="opt">添加广告</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/ads/addAds/action/3" target="opt">添加广告位</a></li>
            </ul>
          </li>
          <li>
            <span><a href="###">网站配置</a></span>
            <ul class="son">
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/nav/menuList" target="opt">后台菜单</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/webConfig/links" target="opt">友情链接</a></li>
              <li><a href="http://127.0.0.1/hdjob/index.php/backend/webConfig/siteNavigation" target="opt">导航管理</a></li>
            </ul>
          </li>
        </ul>
        <ul id="shortcut-ico" class="fn-hide">
          <li><a href="http://127.0.0.1/hdjob/index.php/backend/user/userList" target="opt" title="用户管理"><i class="icon icon-user"></i></a></li>
          <li><a href="http://127.0.0.1/hdjob/index.php/backend/webConfig/websiteConfig" title="网站配置" target="opt"><i class="icon icon-setting"></i></a></li>
          <li><a href="http://127.0.0.1/hdjob/index.php/backend/dataModel/modelList" title="模型列表" target="opt"><i class="icon icon-list"></i></a></li>
          <li><a href="http://127.0.0.1/hdjob/index.php/backend/company/recruitList" title="职位列表" target="opt"><i class="icon icon-flag"></i></a></li>
          <li><a href="http://127.0.0.1/hdjob/index.php/backend/user/roleList" title="权限配置" target="opt"><i class="icon icon-folder"></i></a></li>
        </ul>
        <div class="close-left" title="点击显示快捷菜单"></div>
      </div>
      <div id="opt">
        <iframe name="opt" src="http://127.0.0.1/hdjob/index.php/backend/index/home.html" frameborder="0" scrolling="yes" style="overflow:visible;"></iframe>
      </div>
    </div>
      <script type="text/javascript">
      window.onload=function(){(window.onresize=function(){
          //获取可见宽度
          var _document_width=document.documentElement.clientWidth,
          //获取可见高度
          _document_height=document.documentElement.clientHeight,
          _menu_width=$('#menu').outerWidth(),
          _bottom_height=_document_height-60;
          document.getElementById('menu').style.height=_bottom_height+'px';
          document.getElementById('opt').style.width=_document_width-_menu_width+'px';
          document.getElementById('opt').style.height=_bottom_height+'px';
      })()};
      $('.close-left').toggle(function(){
        
        left_w = $('#menu') . outerWidth();
        var right_w = $('#opt') . outerWidth() + left_w-30-7;
        $('.left-menu').fadeOut();
        $('#menu').animate({width:"30px"},function(){
            $('#shortcut-ico').show();
        });
        $('#opt').animate({"width":right_w+"px"});
        $(this).addClass('enable-left').attr("title","点击显示菜单列表")
      },function(){
        var right_w = $('#opt') . width() - left_w+30;
        $('#shortcut-ico').fadeOut(function(){
            $('.left-menu').fadeIn();
          }
        );
        $('#menu').animate({"width":left_w-7+"px"});
        $('#opt').animate({"width":right_w+"px"});
        $(this).removeClass('enable-left').attr("title","点击显示快捷菜单")
      });
      $('#menu span').live("click",function() {
        if($(this).next('ul:visible').length==0){
          $('#menu .son').slideUp();
          $(this).next().slideDown();
        }else{
          $(this).next().slideUp();
        }
        return false;
      });
      $('#menu .son a').hover(function(){
        $(this).addClass('son-hover');
      },function(){
        $(this).removeClass('son-hover');
      });
      </script>
  </body>
</html>