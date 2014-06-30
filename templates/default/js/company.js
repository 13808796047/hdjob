$('.select').click(function(){
	var _obj=$('.table-list :checkbox');
		if($(this).attr('type')=='all'){
		$('.ui-func :checkbox').attr('checked',true);
		_obj.attr('checked',true);
	}else{
		$('.ui-func :checkbox').attr('checked',false);
		_obj.each(function(){
			$(this).attr('checked')?$(this).attr('checked',false):$(this).attr('checked',true);
		});
	}
	return false;
});
$('.select-all').click(function(){
	if($(this).attr('checked')){
		$('.table-list :checkbox[name]').attr('checked',true);
	}else{
		$('.table-list :checkbox[name]').attr('checked',false);
	}
});
$('.opt').click(function() {
var _obj=$('input[name]:checked'),_id=[],_action=$(this).attr('action');
if(_obj.length==0){
alert('没有被选项被选中！');
return false;
}_obj.each(function(){
_id.push($(this).val());
});
if(_action=='set-viewed' && confirm("确认将已选中的简历设置为已查看吗？")){
$.post(url+'/setViewed',{
'id':_id},function(data){
if(data==1){
alert("设置成功");
window.location.reload();
}},'html');
return false;
}if(_action=='update'){
var _msg='开启',_url='enableRecruit';
if($(this).attr('status')==0){
_msg='关闭';
_url='closeRecruit';
}if(confirm("确认"+_msg+"已选中的职位？")){
$.post(url+'/'+_url,{
'id':_id},function(data){
if(data==1){
alert("设置成功");
window.location.reload();
}},'html');
}return false;
}if(_action=='del' && confirm("确认删除已选中的招聘信息？")){
$.post(url+'/delRecruit',{
'id':_id},function(data){
if(data==1){
_obj.parents('tr').fadeOut('slow',function(){
_obj.parents('tr').remove();
});
}},'html');
return false;
}
if(_action=='del-fav' && confirm("确认删除已选中的收藏简历？")){
$.post(url+'/delFav',{
'id':_id},function(data){
if(data==1){
_obj.parents('tr').fadeOut('slow',function(){
_obj.parents('tr').remove();
});
}},'html');
return false;
}
return false;
});
$('.refresh').click(function(){
if($(this).next().length==0){
$(this).after('<div class="dialog fn-hide"><h2>确认刷新？</h2><div class="dialog-content"></div><div class="dialog-button"><a href="" class="refresh-ok">刷新</a><a href="###" class="dialog-close">取消</a></div></div>');
}$('.dialog').hide();
$(this).next().find('.dialog-button').show();
$(this).next().find('h2').html('确认刷新？');
$(this).next().slideDown();
if($(this).attr('already')=='true'){
$(this).next().find('.dialog-content').html('你今天已经刷新过了，再次刷新将扣除'+$('#point').text()+'积分。');
$(this).next().slideDown();
}else{
$(this).next().find('.dialog-content').html('你今天还没有刷新，拥有免费刷新一次的机会。');
}return false;
});
$('.dialog-close').live("click",function(){
$(this).parents('.dialog').slideUp('slow');
return false;
});
$('.refresh-ok').live("click",function(){
var _this=$(this),_id=$(this).parents('.dialog').prev().attr('r-id');
_this.parents('.dialog').hide('slow',function(){
_this.parent().prev().html('刷新成功。');
_this.parent().hide();
_this.parent().prev().prev().text("恭喜你：");
});
$.post(url+'/refreshRecruit',{
"id":_id},function(data){
if(data==1){
_this.parents('.dialog').prev().attr('already','true');
_this.parents('.dialog').slideDown();
setTimeout('$(".dialog").fadeOut()',2000);
}},'html');
return false;
});
