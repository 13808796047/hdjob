/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: js.js      2012-2-18  16:11:47
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
/**
 * 对话框
 */
function createDialogElement(){//弹出对话框
    if($("#hd_dialog").length==0){
        $str = "<div class='hd_dialog' id='hd_dialog' style='display:none; '>\n\
 <div class='title'></div><div class='close'><a href='javascript:dialog_hide()'></a></div>";   
        $("body").append($str);
    }
}
function dialog_show(title,closeStr){
    createDialogElement();
    $("#hd_dialog .title").html(title);
    $("#hd_dialog .close a").html(closeStr);
    $("#hd_dialog").fadeIn(0);
}
//隐藏对话框
function dialog_hide(){
    $("#hd_dialog").fadeOut(300);
}

en

