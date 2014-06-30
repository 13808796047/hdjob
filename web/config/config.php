<?php

if (!defined("PATH_HD")){exit('拒绝访问');}
$db_config=include PATH_ROOT.'/config/database.php';
$app_config=include PATH_ROOT.'/config/app.php';
$inc_config=include PATH_ROOT.'/config/inc.php';
return array_merge($db_config,$app_config,$inc_config);
?>