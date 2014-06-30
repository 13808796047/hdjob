<?php
C("debug", 0);
$title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
$upload = new upload('', '', C("EDITOR_FILE_SIZE"));
$uploadFile = $upload->upload();
$state = empty($upload->error) ? "SUCCESS" : $upload->error;
$path = __ROOT__ . '/' . $uploadFile[0]['path'];
echo "{'url':'" . $path . "','title':'" . $title . "','state':'" . $state . "'}";
?>