<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>操作成功---<?php echo $_CONFIG['web_name'];?></title>
            <link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/base.css"/>
            <link type="text/css" rel="stylesheet" href="http://127.0.0.1//hdjob/public/css/success.css"/>
            <script type="text/javascript">
                window.setTimeout("<?php echo $url;?>",<?php echo $time;?>*1000);
            </script>
    </head>
    <body>
        <div class="opt-notice">
            <h2>操作成功！</h2>
            <div class="notice-con">
                <div class="msg">
                    <table width="100%" height="100%">
                        <tr>
                            <td valign="middle"><?php echo $msg;?></td>
                        </tr>
                    </table>
                </div>
                <p class="link"><span id="time"><?php echo $time;?></span>秒钟后将进行<a href="javascript:<?php echo $url;?>">跳转</a>也可以<a href="http://127.0.0.1/hdjob/index.php">返回首页</a></p>
            </div>
        </div>
        <script type="text/javascript">
         var _w=document.documentElement.clientWidth,
            _h=document.documentElement.clientHeight,
            _time=document.getElementById("time").innerHTML;
            document.body.style.cssText="width:"+_w+'px;height:'+_h+'px';
            function revTime(){
                _time--;
                if(_time>0){
                    document.getElementById("time").innerHTML=_time;
                }
            }
            setInterval("revTime()",1000);
        </script>
    </body>
</html>
