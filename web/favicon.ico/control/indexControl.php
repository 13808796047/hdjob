<?php
class indexControl extends Control{
    function index(){
        header("Content-type:text/html;charset=utf-8");
        echo "<div style='font-size:16px;font-weight:bold;color:#333;margin-left:20px;border:solid 2px #F00;width:500px;height:30px;padding:30px 50px 20px;'>感谢使用由后盾网提供的HD开源框架，基础目录已经创建成功！</div>";
    }
}