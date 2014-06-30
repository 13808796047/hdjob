<?php
C("debug", 0);
$file = urldecode($_POST["file"]);

$file = explode("@@@",$file);
if($file){
    foreach ($file as $v) {
        @unlink($v);
    }
    echo 1;
}else{
    echo 0;
}
?>