 $('.jobs-info>ul li').click(function(){
$('.jobs-info>ul li').removeClass('ui-active');
$(this).addClass('ui-active');
$('.jobs-info>div').hide();
$($('a',$(this)).attr('href')).show();
return false;
});
$('.applyJob').click(function(){
var _recruit={0:[$(this).attr('href'),$('.company-name').text(),$('.job-name').text(),$('.company-name').attr('c-id')]};//简历id,公司名称，职位名称，企业id
appleJob(_recruit);
return false;
});
function appleJob(_recruit_id){
$.post(app+'/profile/applyRecruit',function(data){
var _option='';
if(data.status==0){
    alert(data.msg);
    location.href=web+'/login';
}else{
    $.each(data, function(k,v){
        _option+='<option value="'+v.resume_id+'">'+v.resume_name+'</option>'
    });
    $.dialog({
    "title":"请选择你需要投递的简历！",
    "fixed":true,
    "width":"300px",
    "content":'简历列表：<select name="resume_id" id="resume_id"><option value="">请选择</option>'+_option+'</select>',
    "okValue":'确定',
    "ok":function(){
        if($('#resume_id').val()==''){
            alert('请选择你要投递的简历！');
            return false;
        }
        $.post(app+'/profile/deliver',{'resume_id':$('#resume_id').val(),'resume_name':$('#resume_id :selected').text(),'recruit_id':_recruit_id},function(data){
            if(data.status){
                alert(data.msg);
            }else{
                alert('职位投递失败！');
            }
        },'json');
    }
});
}
}, 'json');
}