<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/install/tpl/public/css/index.css"/>
</head>
<body>
    <div class="setup">
        <div class="title">
            后盾求职招聘系统安装向导
        </div>
        <div class="copy">
            <p>
                <?php echo $setup['version'];?>
            </p>
        </div>
        <div class="body">
            <div class="con" style="height: 400px; border: solid 1px #999;padding:10px;">
                <?php echo $setup['license'];?>
            </div>
        </div>
        <div class="submit">
            <form action="http://127.0.0.1/hdjob/install/index.php/index/check" method="get">
                <button class="send" type="submit">同意协议</button>
            </form>
        </div>
        <div class="step">
        </div>
    </div>
</body>
</html>
