<?php if(!defined("PATH_HD"))exit;?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="http://127.0.0.1/hdjob/install/tpl/public/css/check.css"/>
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
            <table>
                <tr>
                    <th>名称</th>
                    <th>HDCMS要求配置</th>
                    <th>最佳配置</th>
                    <th>当前环境</th>
                </tr>
                <tr>
                    <td>操作系统</td>
                    <td>任意</td>
                    <td>类linux</td>
                    <td><?php echo $system['phpos'];?></td>
                </tr>
                <tr>
                    <td>PHP版本</td>
                    <td>5.0</td>
                    <td>5.3</td>
                    <td><?php echo $system['phpversion'];?></td>
                </tr>
                <tr>
                    <td>附件上传大小</td>
                    <td>不限制</td>
                    <td>5M</td>
                    <td><?php echo $system['uploadsize'];?></td>
                </tr>
                <tr>
                    <td>GD库</td>
                    <td>1.0</td>
                    <td>2.0</td>
                    <td><?php echo $system['gdversion'];?></td>
                </tr>
                <tr>
                    <td>磁盘空间</td>
                    <td>8MB</td>
                    <td>不限制</td>
                    <td><?php echo $system['diskspace'];?></td>
                </tr>
            </table>
            <!--目录检测-->
            <h2>环境检测</h2>
            <table style="width:90%">
                <tr>
                    <th>目录文件名称</th>
                    <th>所需状态</th>
                    <th>当前状态</th>
                </tr>
                <?php if(is_array($setup['dirs'])):?><?php  foreach($setup['dirs'] as $k=>$v){ ?>
                    <tr>
                        <td><?php echo $v;?></td>
                        <td>可写</td>
                        <td><?php echo is_writeable($v)?"<img src='http://127.0.0.1/hdjob/install/tpl/public/images/10.png'/>&nbsp;可写":"<img src='http://127.0.0.1/hdjob/install/tpl/public/images/12.png'/>不可写"?></td>
                    </tr>
                <?php }?><?php endif;?>
            </table>

            <!--函数检测-->
            <h2>环境检测</h2>
            <table style="width:80%">
                <tr>
                    <th width="280">目录名称</th>
                    <th>检测结果</th>
                    <th>系统建议</th>
                </tr>
                <?php if(is_array($setup['functions'])):?><?php  foreach($setup['functions'] as $k=>$v){ ?>
                    <tr>
                        <td><?php echo $v;?></td>
                        <td><?php echo function_exists($v)?"<img src='http://127.0.0.1/hdjob/install/tpl/public/images/10.png'/>&nbsp;支持":"<img src='http://127.0.0.1/hdjob/install/tpl/public/images/12.png'/>不支持"?></td>
                        <td>无</td>
                    </tr>
                <?php }?><?php endif;?>
            </table>
        </div>
        <div class="submit">
            <form action="http://127.0.0.1/hdjob/install/index.php/index/setconfig" method="get">
                <button class="send" type="submit">下一步</button>
            </form>
        </div>
        <div class="step">
        </div>
    </div>
</body>
</html>