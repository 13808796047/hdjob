<script type="text/javascript">
            //向军  houdunwang.com  houdunwang@gmail.com
    if(typeof swfupload=='undefined'){
        swfupload_file=[];//记录添加或删除文件数据
    }
    $(function(){
        var swfu;
        var settings = {
            flash_url : '<?php echo $swfupload_path; ?>/swfupload/swfupload.swf',
            flash9_url : '<?php echo $swfupload_path; ?>/swfupload/swfupload_fp9.swf',
            upload_url: '<?php echo $url; ?>',
            post_params: {},//POST参数
            file_size_limit : '<?php echo $size; ?>',
            file_types : '<?php echo $type; ?>',
            file_types_description : 'Files:',
            file_upload_limit :<?php echo $limit; ?>,
            file_queue_limit : 0,
            custom_settings : {
                progressTarget : 'fsUploadProgress<?php echo $swfupload_id ?>',
                cancelButtonId : 'btnCancel'
            },
            debug: false,
            button_width: '110',
            button_height: '26',
            button_placeholder_id: 'spanButtonPlaceHolder',
            button_text: '<span class="theFont"><?php echo $text; ?></span>',
            button_text_style: '.theFont { display:block;font-size: 14;font-weight:bold;color:#ffffff; }',
            button_text_left_padding: 30,
            button_text_top_padding: 3,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,
            swfupload_preload_handler : preLoad,
            swfupload_load_failed_handler : loadFailed,
            file_queued_handler : fileQueued,
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_start_handler : uploadStart,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,
            queue_complete_handler : queueComplete	// Queue plugin event
        };
        settings.post_params['<?php echo $session_name; ?>']='<?php echo $session_id; ?>';
        settings.post_params['dir']='<?php echo $dir; ?>';
        settings.post_params['upload_display_width']='<?php echo $upload_display_width; ?>';
        settings.post_params['upload_display_height']='<?php echo $upload_display_height; ?>';
        settings.post_params['imagesize']='<?php echo $imagesize; ?>';
        //settings.post_params['thumbon1']='< ? php echo $thumbon; ? >';
        settings.post_params['swfupload_size']='<?php echo $swfupload_size; ?>';
        settings.post_params['water_on']='<?php echo $water_on; ?>';
        swfu = new SWFUpload(settings);
        var file_upload={
            "swfuobj":swfu,//上传对象
            "file_upload_limit":swfu.settings.file_upload_limit,//允许上传的总的文件数量，删除操作不会改变
            "file_allow_total":swfu.settings.file_upload_limit,//允许上传的总的文件数量,删除操作会改变
            "file_success_nums":0,//成功上传的文件数量
            "file_nums":0,//已经上传的文件数量，包括删除的文件
            "delurl":"<?php echo $delurl; ?>",
            "input_name":"<?php echo $input_hidden;?>"
        };
        swfupload_file.push(file_upload);
    })
</script>