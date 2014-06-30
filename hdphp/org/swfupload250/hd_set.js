//上传成功
function uploadSuccess(file, serverData) {

    var swfupload_id = file.id.substr(10,1);//第几个swfupload
    eval("serverData="+serverData);
    var file_upload=swfupload_file[swfupload_id];
    file_upload.swfuobj.removeFileParam(file.index);
    // var str='';
    // for(var i in serverData){
    //     str+=i+'=>'+serverData[i]+"\n";
    // }
	// alert(str);
    if(serverData.state!='SUCCESS'){//上传错误
        file_upload.file_nums++;  //已经上传成功文件
        file_upload.swfuobj.setFileUploadLimit(++file_upload.file_allow_total);//设置上传数量
        alert(file.name+'文件不能上传，因为'+serverData.state);
    }else{
        var successData ;//提交回调函数upload的参数
        successData = serverData;
        successData['index']=file.index;
        successData['size']=file.size;
        successData['name']=file.name;
        file_upload.file_success_nums++;//已经上传的文件数加1
        file_upload.file_nums++;          
        var msg ='';//提示文本
        msg = '你已经上传'+file_upload.file_success_nums+'个文件，还可以上传'+(file_upload.file_allow_total-file_upload.file_nums)+'个文件，每张不超过'+file_upload.swfuobj.settings.file_size_limit;
        $("#swfupload_message"+swfupload_id).html(msg).addClass("swfupload_message_style");//输出上传状态文本
        var input_name = file_upload.input_name.replace(/\[|\]/ig,'');
       
        var input_name=input_name+"["+file_upload.file_success_nums+"][]";
        var fid = serverData['fid'];
        var input_hidden='';//隐藏域
        $(serverData.file).each(function(i){
            input_hidden+="<input type='hidden' fid='"+fid+"' name='"+input_name+"' value='"+serverData.file[i].path+"'/>";  
        })
        $('.swfupload_input').append(input_hidden);
        if(serverData.thumb){
            var imgStr='<li>';
            imgStr+= "<div class='upload_imgbox'><img src='"+serverData.thumb.file+"' width='"+serverData.thumb.w+"' height='"+serverData.thumb.h+"'/></div>";
            //            var delData = encodeURIComponent(serverData.bigpath)+"@@@"+encodeURIComponent (serverData.thumbpath);
            imgStr+="<span class='del_upload_file'swfupload_id='"+swfupload_id+"' fid='"+fid+"'></span>";
            imgStr+="</li>";
            $("#swfupload_file_show"+swfupload_id+" ul").append(imgStr);
        }
        
        if(typeof window.hd_upload == "function"){
            hd_upload(successData);
        }
    }
    $("#"+file.id).slideUp(0);//隐藏上传成功后的文件
}
//异步删除
$(".del_upload_file").live("click",function(){
    var span_del_obj=$(this);
    var swfupload_id = span_del_obj.attr("swfupload_id");
    var swfu_data = swfupload_file[swfupload_id];//保存上传成功失败文件等数据的对象
    var delFile ='';
    $("input[fid='"+$(this).attr("fid")+"']").each(function(i){
        delFile+=$(this).attr("value")+"@@@";
    })
    var obj = $(this);
    $.ajax({
        type:"POST",
        url:swfu_data.delurl,
        data:{
            "file":delFile
        },
        success:function(data){
            if(data==1){
                $("input[fid='"+obj.attr("fid")+"']").remove();//移除INPUT
                span_del_obj.parent().fadeOut(0,function(){
                    span_del_obj.parent().remove();
                });
                swfu_data.file_success_nums--;//已经上传的文件数减一
                swfu_data.swfuobj.setFileUploadLimit(++swfu_data.file_allow_total);//设置上传数量
                msg = '你已经上传'+swfu_data.file_success_nums+'个文件，还可以上传'+(swfu_data.file_allow_total-swfu_data.file_nums)+'个文件，每张不超过'+swfu_data.swfuobj.settings.file_size_limit;
                $("#swfupload_message"+swfupload_id).html(msg).addClass("swfupload_message_style");//输出上传状态文本
            }else{
                alert("删除失败");
            }
        }
    })
})


//等待上传中。。
function fileQueued(file) {
    try {
        var progress = new FileProgress(file, this.customSettings.progressTarget);
        progress.setStatus('等待中...');
        progress.toggleCancel(true, this);
    } catch (ex) {
        this.debug(ex);
    }
}
//上传中。。
function uploadProgress(file, bytesLoaded, bytesTotal) {
    $('.progressCancel').click(function(){//点击X后放弃上传
        swfu.cancelUpload(file.index);
    })
    try {
        var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
        var progress = new FileProgress(file, this.customSettings.progressTarget);
        progress.setProgress(percent);
        progress.setStatus('文件上传中...');
    } catch (ex) {
        this.debug(ex);
    }
}
//发生错误
function fileQueueError(a,b,num){
    if(num>0){
    //     alert('只能上传'+file_upload_limit+'张图片，请重新选择');
    }else{
        alert('上传文件数量已经达到最大值，不可以上传了');
    }
}                            
//选择文件过多错误
function fileQueueError(file, errorCode, message) {
    try {
        if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
            alert("还能再上传"+message +"个文件" +(message === 0 ? "您已经达到了上传限制" :  (message > 1 ?  message + "" : "")));
            return;
        }

        var progress = new FileProgress(file, this.customSettings.progressTarget);
        progress.setError();
        progress.toggleCancel(false);

        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                progress.setStatus("文件太大。");
                this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                progress.setStatus("Cannot upload Zero Byte files.");
                this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                progress.setStatus("Invalid File Type.");
                this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            default:
                if (file !== null) {
                    progress.setStatus("Unhandled Error");
                }
                this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
        }
    } catch (ex) {
        this.debug(ex);
    }
}

       
//=====================================================
function preLoad() {
    if (!this.support.loading) {
        alert("You need the Flash Player 9.028 or above to use SWFUpload.");
        return false;
    }
}
function loadFailed() {
    alert("Something went wrong while loading SWFUpload. If this were a real application we'd clean up and then give you an alternative");
}
function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesSelected > 0) {
        //	document.getElementById(this.customSettings.cancelButtonId).disabled = false;
        }
		
        /* I want auto start the upload and I can do that here */
        this.startUpload();
    } catch (ex)  {
        this.debug(ex);
    }
}
function uploadStart(file) {
    try {
        /* I don't want to do any file validation or anything,  I'll just update the UI and
                return true to indicate that the upload should start.
                It's important to update the UI here because in Linux no uploadProgress events are called. The best
                we can do is say we are uploading.
                 */
        var progress = new FileProgress(file, this.customSettings.progressTarget);
        progress.setStatus("Uploading...");
        progress.toggleCancel(true, this);
    }
    catch (ex) {}
	
    return true;
}
function uploadError(file, errorCode, message) {
    try {
        var progress = new FileProgress(file, this.customSettings.progressTarget);
        progress.setError();
        progress.toggleCancel(false);

        switch (errorCode) {
            case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                progress.setStatus("Upload Error: " + message);
                this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
                progress.setStatus("Upload Failed.");
                this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.IO_ERROR:
                progress.setStatus("Server (IO) Error");
                this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
                progress.setStatus("Security Error");
                this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                progress.setStatus("Upload limit exceeded.");
                this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
                progress.setStatus("Failed Validation.  Upload skipped.");
                this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                // If there aren't any files left (they were all cancelled) disable the cancel button
                if (this.getStats().files_queued === 0) {
                    document.getElementById(this.customSettings.cancelButtonId).disabled = true;
                }
                progress.setStatus("Cancelled");
                progress.setCancelled();
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                progress.setStatus("Stopped");
                break;
            default:
                progress.setStatus("Unhandled Error: " + errorCode);
                this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
        }
    } catch (ex) {
        this.debug(ex);
    }
}
function uploadComplete(file) {
    if (this.getStats().files_queued === 0) {
    //document.getElementById(this.customSettings.cancelButtonId).disabled = true;
    }
}
// This event comes from the Queue Plugin
function queueComplete(numFilesUploaded) {
//var status = document.getElementById("divStatus");
//status.innerHTML = numFilesUploaded + " file" + (numFilesUploaded === 1 ? "" : "s") + " uploaded.";
}