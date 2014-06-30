<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/install/tpl/public/css/installdb.css"/>
</head>
<body>
    <div class="setup">
        <div class="title">
            <?php echo $setup['webname'];?> install
        </div>
        <div class="copy">
            <p>
                <?php echo $setup['version'];?>
            </p>
        </div>
        <div class="body">
            <iframe src="http://127.0.0.1/hdjob/install/index.php/index/createtable" frameborder="0" scrolling="yes"></iframe>
        </div>
        
        <div class="step">
        </div>
    </div>
</body>
</html>
