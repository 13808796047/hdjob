<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/install/tpl/public/css/finish.css"/>
    <base target="_blank"/>
</head>
<body>
    <div class="setup">
        <div class="title">
            HDCMS install
        </div>
        <div class="copy">
            <p>
                <?php echo $setup['version'];?>
            </p>
        </div>
        <div class="body">
            <h1>感谢您使用后盾网求职招聘系统</h1>
            <p><a href="<?php echo str_replace(APP, '', $root)?>">访问首页</a></p>
            <p><a href="<?php echo str_replace(APP, '', $root)?>index.php/backend">进入后台管理</a></p>
            <br/>
            <span style="font-size:14px;display:block;clear:both;color:#89B928;padding-top:30px;">
                安全提示：安装完成后请将install目录删除
            </span>
        </div>

        <div class="step">
        </div>
    </div>
</body>
</html>
