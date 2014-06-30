<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/install/tpl/public/css/setconfig.css"/>
    <script type="text/javascript" src="http://127.0.0.1/hdjob/install/tpl/jquery-1.7.2.min.js"></script>
</head>
<body>
    <div class="setup">
        <div class="title">
           <?php echo $setup['webname'];?>安装向导
        </div>
        <div class="copy">
            <p>
                <?php echo $setup['version'];?>
            </p>
        </div>
        <div class="body">
            <h2>环境检测</h2>
            <form action="http://127.0.0.1/hdjob/install/index.php/index/installdb" method="post" id="config-form">
                <table>
                    <tr>
                        <td style="width:120px;">数据库连接主机</td>
                        <td style="width:320px;"><input type="text" name="db[DB_HOST]" id="db_host" value="localhost"/></td>
                        <td>数据库服务器地址一般为localhost</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">数据库用户名</td>
                        <td style="width:320px;"><input type="text" name="db[DB_USER]" id="db_user" value="root"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">数据库密码</td>
                        <td style="width:320px;"><input type="text" id="db_pwd" name="db[DB_PASSWORD]"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">数据库名称</td>
                        <td style="width:320px;"><input type="text" name="db[DB_DATABASE]" value="hd_recruit"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">端口</td>
                        <td style="width:320px;"><input type="text" name="db[DB_PORT]" value="3306"/></td>
                        <td>一般数据库连接端口为3306</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">数据表前缀</td>
                        <td style="width:320px;"><input type="text" name="db[DB_PREFIX]" value="hd_"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">数据库驱动</td>
                        <td style="width:320px;">
                            <input type="radio" name="db[DB_DRIVER]" style="width:20px;" checked="checked"value="mysqli"/>
                            <strong>mysqli</strong></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <!--目录检测-->
                <h2>管理员信息</h2>
                <table style="width:90%">
                    <tr>
                        <td style="width:120px;">管理员名称</td>
                        <td style="width:320px;">
                            <input type="text" name="admin[username]" value="admin"/>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">管理员密码</td>
                        <td style="width:320px;">
                            <input type="password" name="admin[password]" id="admin-pwd" />
                        </td>
                        <td>密码不要用管理员名称相同</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">重复密码</td>
                        <td style="width:320px;">
                            <input type="password" name="admin[password2]" id="admin-re-pwd" />
                        </td>
                        <td>密码不要用管理员名称相同</td>
                    </tr>
                    <tr>
                        <td style="width:120px;">管理员邮箱</td>
                        <td style="width:320px;">
                            <input type="text" name="admin[email]" value="admin@admin.com"/>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>


        </div>
        <div class="submit">
            <button class="send" type="submit">下一步</button>
        </div>
    </form>
    <div class="step">
    </div>
</div>
<script type="text/javascript">
    $('#config-form').submit(function(){
        if($('#admin-pwd').val().length==0 || ($('#admin-pwd').val().length< 6)){
            alert('密码长度至少为6位！');
            return false;
        }
        if($('#admin-pwd').val()!=$('#admin-re-pwd').val()){
            alert('两次密码不一致！');
            return false;
        }
        $.post('http://127.0.0.1/hdjob/install/index.php/index/checkDbConnect',{"host":$('#db_host').val(),"user":$('#db_user').val(),"pwd":$('#db_pwd').val(),},function(data){
            if(data==0){
                alert('别着急，检测到数据库链接有问题。请仔细检查你的数据库配置。');
                return false;
            }
        },'html');
    });
</script>
</body>
</html>
