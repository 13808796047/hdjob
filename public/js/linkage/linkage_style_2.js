(function($){
    $.fn.linkage_style_2=function(settings){
        settings = jQuery.extend({
            name:'input[]',
            input_height:$(this).outerHeight()-1+'px',
            max:5
        }, settings);
        return this.each(function() {
            $.fn.linkage_style_2.style( $(this), settings );
        });
    };
    //select 需要被选中的，通过 _index 去遍历数据 
    $.fn.linkage_style_2.getNext=function(select,_index,settings){
        var _str='<ul>',
        _next='';//二级菜单情况
        if(settings.data[_index]){
            $.each(settings.data[_index], function(k,v){
                if(settings.data[k]){//如果有二级数据
                    _next='<img src="'+url+'/templates/default/images/plus.gif" style="display:block;float:left;margin:2px" /><span value="'+k+'">'+v+'</span>';
                }else if(settings.checkbox){//如果可以多选
                    if($.inArray(k,select)>=0){//处理选中
                        _next='<input type="checkbox" checked value="'+k+'" style="display: block;float: left;width:13px;height: 16px;padding: 2px;margin: 2px 5px;border:none;" />'+v;
                    }else{
                        _next='<input type="checkbox" value="'+k+'" style="display: block;float: left;width:13px;height: 16px;padding: 2px;margin: 2px 5px;border:none;" />'+v;
                    }
                }else{//使用单选
                    if($.inArray(k,select)>=0){//处理选中
                        _next='<a href="" value="'+k+'" style="display: block;float: left;height: 16px;line-height:16px;padding: 2px;margin-top: 2px;color:#F60;">'+v+'</a>';
                    }else{
                        _next='<a href="" value="'+k+'" style="display: block;float: left;height: 16px;line-height:16px;padding: 2px;margin-top: 2px;color:#3059A8;">'+v+'</a>';
                    }
                }
                if($.inArray(k,select)>=0){
                    _str+='<li title="'+v+'" style="float: left;width: 140px;padding-left:5px;cursor:pointer;color:#F60;height: 20px;line-height: 20px;z-index:400;overflow: hidden;">'+_next+'</li>';
                }else{
                    _str+='<li title="'+v+'" style="float: left;width: 140px;z-index:400;padding-left:5px;cursor:pointer;height: 20px;line-height: 20px;overflow: hidden;">'+_next+'</li>';
                }
            });
        }
        return _str+'</ul>';
    }
    $.fn.linkage_style_2.style=function($this,settings){
        if(settings.defaults==undefined){
            settings.defaults='';
        }
        var _table='<table style="width: 100%;color: #404040;">',
        _default=settings.defaults.split('#'),//默认的值
        _field=settings.field.split('#'),//字段列表
        _num=_field.length,//字段个数
        _tips=settings.tips || '点击查看/修改';
        for(i=0;i<_num;i++){
            _default_value=_default[i];
            if(!_default[i]){
                _default_value='';
            }
            $this.after('<input type="hidden" name="'+_field[i]+'" value="'+_default_value+'" />');
        }
        $this.css({"border":"none","cursor":"pointer","color":"#0D6FBA"}).val(_tips);
        $this.focus(function(){
            if($this.prev('.linkage_style_2').length==0){
             $this.before('<div class="linkage_style_2" id="'+_field[0]+'_show_name" style="background:#FFF;width: 720px;padding: 5px;height: auto;display: none;position: absolute;border:1px #CCCCFF solid;z-index: 9999;"><div class="tool_area" style="height:30px;border-bottom:2px #ccc solid"><div style="float:left;width: 570px;height: 23px;overflow: hidden;"><span style="">已选择：</span><span class="checked-list"></span></div><button onclick="javascript:$(\'.linkage_style_2\').slideUp(function(){$(\'select\').show();$(\'.dialog-modal\').hide()});return false;" style="float:right;height:24px;" class="btn">关闭</button><button onclick="javascript:$(\'.linkage_style_2\').slideUp(function(){$(\'select\').show();$(\'.dialog-modal\').hide()});return false;" style="float:right;height:24px;margin-right:5px;" class="btn">确定</button></div><div class="linkage_data_list" style="clear:both"></div></div>');
                
            }
            if($this.prev().children('.linkage_data_list').is(':empty')){//填充内容
                $.each(settings.data['first'], function(k,v){
                    _table+='<tr><th style="width:200px;text-align: center;font-weight: bold;padding:8px 0px;">'+v+'</th><td style="padding: 8px 0px;">'+$.fn.linkage_style_2.getNext(_default,k,settings)+'</td></tr>'
                });
                _table+='</table>';
                $this.prev().children('.linkage_data_list').html(_table);
            }
            if($('.dialog-modal').length==0){//创建modal层
                $('body').append('<div class="dialog-modal" style="width:'+document.body.scrollWidth+'px;height:'+document.body.scrollHeight+'px;background:#FFF;position:absolute;left:0px;top:0px;opacity:0.6;filter:alpha(opacity=60);"></div>');
            }
            $('.dialog-modal').show();//显示modal层
            $this.prev().css({'top':document.body.scrollTop+100+'px','left':'30%'});
            $('select').hide();//万恶的IE
            $(".linkage_style_2").hide();
            $(".linkage_style_2 .linkage_data_list table tr:odd").css({
                'background':'#D8E7FC',
                'border':'1px #CCF solid'
            });
            $(this).prev().slideDown();
            $(this).blur();
        });
        $("#"+_field[0]+"_show_name li").live('click',function(){
            var _img=$('img',$(this)),//如果有图片说明有二级数据
            _a=$('a',$(this)),//如果有a说明没有二级数据
            _next='';
            $("#"+_field[0]+"_show_name li a").css({"color":"#3059A8"});
            if(_img.length){
                var _span=$('span',$(this));
                $this.val(_span.text()+':');
                $this.attr('title',_span.text()+':');
                $('input[name="'+_field[0]+'"]').val(_span.attr('value'));
                if(_field[1]!=undefined){
                    $('input[name="'+_field[1]+'"]').val('');
                }
                $("#"+_field[0]+"_show_name li img").attr('src',url+'/templates/default/images/plus.gif');
                _img.attr('src',url+'/templates/default/images/minus.gif');
                $(this).css({'position':'relative'});
                $(".linkage-two").hide();
                $("#"+_field[0]+"_show_name li").css({'border':'none','z-index':'100','overflow': 'hidden','width':'140px','height':'20px','line-height':'20px','color':'#000'});
                $("#"+_field[0]+"_show_name li :checkbox").attr('checked',false);
                $(".linkage_style_2 .linkage_data_list table tr:odd").css({'background':'#D8E7FC','border':'1px #CCF solid'});
                $(".linkage_style_2 .linkage_data_list table tr:odd td > ul > li").css({
                    'background':'#D8E7FC'
                });
                $(this).css({
                    'border':'2px #C7C7C7 solid',
                    'border-radius':'2px',
                    'width':'136px',
                    'border-bottom':'none',
                    'background':'#FFF',
                    'z-index':'400',
                    'height':'18px',
                    'line-height':'18px'
                });
                if($(this).next('div').length==0){
                    var _position=$(this).position(),
                    _next=$.fn.linkage_style_2.getNext(_default,$('span',$(this)).attr('value'),settings);
                    if(_position.left>400){
                        _p_style="right:25px;top:"+(_position.top+18)+"px;";
                    }else{
                        _p_style='left:'+_position.left+'px;top:'+(_position.top+18)+'px;';
                    }
                    $(this).after('<div class="linkage-two" style="display:block;width:300px;z-index:300;position:absolute;'+_p_style+'background:#FFF;border:2px #C7C7C7 solid;">'+_next+'<div style="clear:both;border-top:1px #c7c7c7 solid;"><button style="float:right;margin:4px 10px;padding: 0px 4px;border: none;background: #F27936;color: white;font-size: 13px;border-radius: 2px;cursor: pointer;" onclick="$(this).parents(\'.linkage-two\').hide(function(){$(this).prev().css({\'border\':\'none\',\'z-index\':\'100\',\'width\':\'140px\',\'height\':\'20px\',\'line-height\':\'20px\'}).find(\'img\').attr(\'src\',url+\'/templates/default/images/plus.gif\');});return false;">确定</button></div></div>');
                }
                $(this).next('div').show();
            }else{//如果没有图片
                var _linkage_show=$this.prev(),
                _input_node=$(this).children('input'),
                _field_val='',
                _field_show='',
                _checked_list_html='';
                if(_input_node.attr('checked')){
                    _input_node.attr('checked',false);
                    $(this).css('color','#000');
                }else{
                    if(_linkage_show.find(":checked").length>settings.max-1){
                        $(this).css('color','#000');
                        alert('最多选择'+settings.max+'项!');
                        return false;
                    }
                    _input_node.attr('checked',true);
                    $(this).css('color','#F60');
                    $(this).parents().prev('li').css('color','#F60');
                }
                _linkage_show.find(":checked").each(function(){
                    _field_show+=$(this).parent().text()+'+';
                    _field_val+=$(this).val()+'#';
                    _checked_list_html+='<a href="###" style="padding:0px 4px;">'+$(this).parent().text()+'</a>';
                });
                _field_show=_field_show.replace(/\+$/,'');
                _linkage_show.find('.checked-list').html(_checked_list_html);

                if(_a.length){
                    if(settings.checkbox){
                        $('input[name="'+_field[1]+'"]').val(_a.attr('value'));
                        $(this).parent().parent().hide();
                    }else{
                        if(_field[1]!==undefined){
                            $('input[name="'+_field[1]+'"]').val(_a.attr('value'));   
                        }else{
                            $('input[name="'+_field[0]+'"]').val(_a.attr('value'));
                        }
                        var n_f=$(this).parent().parent().prev().text()+':'+_a.text();
                        $this.val(n_f);
                        $this.attr('title',n_f);
                        _a.css({"color":"#F60"});
                        _linkage_show.find('.checked-list').html('<a href="###" style="padding:0px 4px;">'+_a.text()+'</a>');
                        if(_a.parents('.linkage-two').length){
                            _linkage_show.find('.checked-list').html(_a.parents('.linkage-two').prev().text()+'： <a href="###" style="padding:0px 4px;">'+_a.text()+'</a>');
                        }
                    }
                }else{
                    if(settings.checkbox){
                        $this.attr('title',_field_show);
                        $this.val('').val($this.val()+_field_show);
                        $this.next().val(_field_val.replace(/#$/,''));
                    }
                }
            }
            return false;
        });
    };
})(jQuery);
