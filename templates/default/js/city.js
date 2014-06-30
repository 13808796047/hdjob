$("#username").focus(function(){
    $(this).val('');
});
$("#password").focus(function(){
    $(this).hide();
    $(this).next().show();
    $(this).next().focus();
});
$("#validate").focus(function(){
    $(this).val('');
});
$('#town-show').focus(function(){
	var _town='<a href="" title="不限">不限</a>';
	$.each(city[$(this).attr('city-id')],function(k,v){
		_town+='<a title="'+v+'" href="'+k+'">'+v+'</a>';
	});
	$('.filter-city-list').html(_town).slideDown();
});
$('#town-show').blur(function(){
	$('.filter-city-list').slideUp();
});
$('.filter-city-list a').live('click',function(){
	$('#town-show').val($(this).text());
	$('input[name="town"]').val($(this).attr('href'));
	$('.filter-city-list').slideUp();
	return false;
});