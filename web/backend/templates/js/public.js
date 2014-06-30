//全选
//id,有checkbox区域的id, 全选的按钮类名  name:需要选中name属性
function select_all (id,class_name,name) {
	id = id || undefined ? id : 'select-area';
	class_name = class_name || undefined ? class_name: 'select-all';
	name = name || undefined ? name : 'name';
	$('.'+class_name).click(function(){
		if($(this).attr('checked')){
			$('.'+class_name).attr('checked',true);
			$('#'+id+' :checkbox['+name+']').attr('checked',true);
		}else{
			$('.'+class_name).attr('checked',false);
			$('#'+id+' :checkbox['+name+']').attr('checked',false);
		}
	});
}

//删除选中的数据
// object arg 参数列表
// string url 请求的url地址
// object id  待发送 Key/value数据
// object checked 选中的对象
function del_selected(arg) {
	$.post(arg.url,arg.id,function(data){
        if(data==1){
            arg.arg.parents('tr').fadeOut('slow',function(){
                arg.arg.parents('tr').remove();
            });
        }
    },'html');
}

$(function(){
	$('.switch').click(function(){
		if($(this).is('.switch-off')){
			$(this).animate({'background-position':'0px'}).removeClass('switch-off');
			$(this).next().val('TRUE');
		}else{
			$(this).animate({'background-position':'-102px'}).addClass('switch-off');
			$(this).next().val('FALSE');
		}
	});	
})
