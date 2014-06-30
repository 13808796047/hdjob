<?php

if (!defined("PATH_HD"))
    exit;
/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2012-2-18 下午01:53:33
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
return array(
//基本参数
    "CHARSET" => "utf8", //字符集设置
    "DEFAULT_TIME_ZONE" => "PRC", //默认时区
    "HTML_PATH" => "h", //静态HTML保存目录
    "DEBUG" => 1, //开启调试模式
    "DEBUG_AJAX" => 0, //AJAX异步时关闭调试信息显示   1 为显示调试信息  0 关闭调试信息
    "ALWAYS_COMPILE_TPL" => 0, //开启调试模式时，无论模板有无修改，每次都编译模板文件，需要将配置DEBUG设置为1
    "SESSION_AUTO" => 1, //自动开启SESSION
//数据库
    "DB_DRIVER" => "mysqli", //数据库驱动
    "DB_HOST" => "127.0.0.1", //数据库连接主机  如localhost
    "DB_PORT" => 3306, //数据库连接端口
    "DB_USER" => "root", //数据库用户名
    "DB_PASSWORD" => "", //数据库密码
    "DB_DATABASE" => "", //数据库名称
    "DB_PREFIX" => "", //表前缀
//系统调试
    "ERROR_MESSAGE" => "系统发生错误...", //关闭DEBUG时显示的错误内容
    "DEBUG_MENU"=>1,//显示debug菜单
    "SHOW_NOTICE" => 1, //提示性错误显示
    "SHOW_SYSTEM" => 1, //显示系统信息
    "SHOW_INCLUDE" => 1, //显示加载文件信息
    "SHOW_SQL" => 1, //显示执行的SQL语句
    "SHOW_TPLCOMPILE" => 1, //显示模板编译文件
//SESSION
    "SESSION_NAME" => "hdsid", //储存SESSION_ID的COOKIE名
    "SESSION_ENGINE" => 'file', //file文件 mysql数据库处理 memcache为memcache缓存处理
    "SESSION_LIFTTIME" => 2440, //在线保持时间(秒)SESSION过期时间
    "SESSION_TABLE_NAME" => "session", //储存SESSION的数据表名，不要写前缀系统会自动添加
    "SESSION_GC_DIVISOR" => 10, //清理过期用户频率，数字越小清理越频繁根据网站并发自行设置
//URL设置
    "URL_REWRITE" => 0, //url重写模式 使用方式请参考HD手册   如果设置错误 网站将无法访问
    "URL_TYPE" => 1, /*
      1 pathinfo模式 :  index.php/index/index
      2 普通模式：例: index.php?m=index&a=index
     */
    "PATHINFO_Dli" => "/", //PATHINFO分隔符
    "PATHINFO_VAR" => "q", //兼容模式分隔符
    "PATHINFO_HTML" => ".html", //伪静态扩展名
//全局变量
    "VAR_APP" => "a", //应用变量名
    "VAR_CONTROL" => "c", //默认模块名
    "VAR_METHOD" => "m", //动作名
//项目参数
    "DEFAULT_NAME" => "@", //应用名称
    "DEFAULT_APP" => "index", //默认项目应用
    "DEFAULT_CONTROL" => "index", //默认控制器
    "DEFAULT_METHOD" => "index", //默认动作名
    "CONTROL_FIX" => "Control", //控制器文件后缀
    "MODEL_FIX" => "Model", //模型文件名后缀
//URL路由设置
    "route" => array(),
//缓存控制
    "CACHE_DEFAULT_TIME" => 3600, //全局默认缓存时间 如果缓存时没有指定时间将以此为准
    "CACHE_DEFAULT_TYPE" => "file", //缓存类型，可选择类型有：file:文件缓存 memcache: memcache缓存
    "CACHE_SELECT_TIME" => 0, //SQL SELECT查询缓存时间 推荐使用模板缓存  0为关闭 SELECT中的字段按需取不要取无用字段
    "TPL_CACHE_TIME" => 0, //模板缓存时间   0为不缓存
//文件上传
    "THUMB_ON" => 0, //上传的图片是进行缩略图处理       1进行   0不进行
    "UPLOAD_EXT_SIZE" => array("jpg" => 200000, "jpeg" => 200000, "gif" => 200000,
        "png" => 200000, "bmg" => 200000, "zip" => 300000,
        "txt" => 300000, "rar" => 300000, "doc" => 300000), //上传类型与大小
    "UPLOAD_PATH" => PATH_ROOT . "/upload", //上传路径
    "UPLOAD_IMG_DIR" => "img", //图片上传目录名 系统会以UPLOAD_PATH项的子目录形式创建
    "UPLOAD_IMG_RESIZE_ON" => 0, /* 上传图片缩放处理，设置0或false为关闭缩放处理
      如果上传图片宽度或高度超过以下参数值系统进行缩放 */
    "UPLOAD_IMG_MAX_WIDTH" => 2000000, //上传图片超过这个宽度值，系统进行缩放处理 单位像素
    "UPLOAD_IMG_MAX_HEIGHT" => 2000000, //上传图片超过这个高度值，系统进行缩放处理 单位像素
//图像水印处理
    "WATER_ON" => 1, //水印开关
    "WATER_FONT" => PATH_HD . "/data/font/font.ttf", //水印字体
    "WATER_IMG" => PATH_HD . "/data/water/water.png", //水印图像
    "WATER_POS" => 9, //水印位置
    "WATER_PCT" => 60, //水印透明度
    "WATER_QUALITY" => 80, //水印压缩质量
    "WATER_TEXT" => "WWW.HOUDUNWANG.COM", //水印文字
    "WATER_TEXT_COLOR" => "#f00f00", //水印文字颜色
    "WATER_TEXT_SIZE" => 12, //水印文字大小
//图片缩略图
    "THUMB_PREFIX" => "", //缩略图前缀
    "THUMB_ENDFIX" => "_thumb", //缩略图后缀
    "THUMB_TYPE" => 6, //生成缩略图方式,
    //1:固定宽度  高度自增      2:固定高度  宽度自增    3:固定宽度  高度裁切
    //4:固定高度  宽度裁切      5:缩放最大边 原图不裁切  6:缩略图尺寸不变，自动裁切图片
    "THUMB_WIDTH" => 100, //缩略图宽度
    "THUMB_HEIGHT" => 100, //缩略图高度
    "THUMB_PATH" => "", //缩略图路径
//验证码
    "CODE_FONT" => PATH_HD . "/data/font/font.ttf", //验证码字体 
    "CODE_STR" => "1234567890abcdefghijklmnopqrstuvwsyz", //验证码种子
    "CODE_WIDTH" => 80, //验证码宽度
    "CODE_HEIGHT" => 25, //验证码高度
    "CODE_BG_COLOR" => "#CCE8CF", //验证码背景颜色 
    "CODE_LEN" => 4, //长度
    "CODE_FONT_SIZE" => 16, //字体大小
    "CODE_FONT_COLOR" => "", //字体颜色
//分页处理
    "PAGE_VAR" => "page", //分页GET变量
    "PAGE_ROW" => 10, //显示页码数量 
    "ARC_ROW" => 10, //每页显示条数
    "PAGE_STYLE" => 2, //页码显示风格
    "PAGE_DESC" => array("pre" => "上一页", "next" => "下一页",
        "first" => "首页", "end" => "尾页", "unit" => "条"), //分页文字设置
//模板参数
    "TPL_ENGINE" => "hd", //模板引擎  支持smarty模版引擎与HD后盾模版引擎  建议使用HD模版引擎 效率及扩展性更高
    "TPL_FIX" => ".html", //模版文件扩展名
    "TPL_TAG_LEFT" => "<", //模板左标签
    "TPL_TAG_RIGHT" => ">", //模板右标签
    "TPL_DIR" => "tpl", //模板文件的目录名
    "TPL_TAGS" => "", //扩展标签库用,分隔  当前模块的标签库可以不用写 系统会自动加载
    "TPL_STYLE" => "", //如果有多风格模版时，这里添上目录名  那么路径结果就会变成 TPL_PATH/TPL_STYLE形式
    "TPL_COMPILE" => true, //开启模板编译
    "TPL_ERROR" => "", //错误的模板页面
    "TPL_SUCCESS" => "", //正确的模板页面
//购物车参数
    "CART_NAME" => "cart", //购物车名
//文本编辑器
    "EDITOR_TYPE" => 1, //复文本编辑器  1 baidu  2 kindeditor
    "EDITOR_STYLE" => 1, //1 完全模式(全部工具条)  2 精简模式 默认为1
    "EDITOR_MAX_STR" => 100, //编辑器允许输入最大字数
    "EDITOR_WIDTH" => "100%", //编辑器高度
    "EDITOR_HEIGHT" => 280, //编辑器高度
    "EDITOR_FILE_SIZE" => 2000000, //上传文件大小   单位是字节
//RBAC基于角色的权限控制参数
    "LOGIN_MODEL" => "", //默认登录入口
    "RBAC_TYPE" => 1, //1时时认证｜2登录认证
    "RBAC_ADMIN" => "admin", //超级管理员  认证SESSION键名
    "RBAC_AUTH" => "user", //认证SESSION键名
    "NO_AUTH" => array(), //不需要验证的控制器或方法如：array(index/index)表示index控制器的index方法不需要验证
    "USER_TABLE" => "user", //用户表
    "ROLE_TABLE" => "role", //角色表
    "NODE_TABLE" => "node", //节点表
    "ROLE_USER_TABLE" => "user_role", //角色，用户表｜ 注意：不要写表前缀
    "ACCESS_TABLE" => "access", //权限表｜ 注意：不要写表前缀
//日志处理
    "LOG_START" => 0, //是否开启日志记录
    "LOG_KEY" => "houdunwang.com", //日志文件保密密匙串
    "LOG_SIZE" => 2000000, //日志文件大小
    "LOG_TYPE" => array("error", "notice", "sql"), //保存日志类型
//邮箱配置
    "email_username" => "houdunwangdemo@126.com", //发送邮件邮箱用户名
    "email_password" => "houdunwangabc", //发送邮件邮箱密码
    "email_host" => "smtp.126.com", //邮箱服务器smtp地址如smtp.gmail.com或smtp.126.com
    "email_port" => 25, //邮箱服务器smtp端口，126等25，gmail 465
    "mail_charset" => "utf8", //字符集设置，中文乱码就是这个没有设置好
    "replymail" => "houdunwangdemo@126.com", //收件人回复邮箱时显示的邮箱地址
    "replyusername" => "后盾论坛", //收件人回复邮箱时显示的用户名
    "frommail" => "houdunwangdemo@126.com", //发送人发件箱显示的邮箱址址,设置和replymail一样即可，也可不设置
    "fromname" => "后盾论坛", //发送人发件箱显示的用户名，设置和replyusername一样即可，也可不设置
);
