(function($){
    $.fn.linkage_style_1=function(settings){
        settings = jQuery.extend({
            //data : linkage_data_1,
            name:'input[]',
            defaults:''
        }, settings);
        return this.each(function() {
            $.fn.linkage_style_1.style( $( this ), settings );
        });
    };
    $.fn.linkage_style_1.style = function($this, settings){
        settings.defaults.replace(/#$/,'');
        var _field=settings.field.split('#'),//字段列表
        _default=settings.defaults.split('#'),//默认的值
        _select_nums=_field.length?_field.length:1,//字段的长度
        _index=$.fn.arrayFilter(settings.defaults.split('#')),//数据索引
        _select='';
        _index.pop();
        _index.unshift('first');
        for(i=0;i<_select_nums;i++){
            if(i<_select_nums-1){
                _select+=('<select style="margin-right:3px;" name="'+_field[i+1]+'" '+settings.html_attr+'></select>');
            }
            $("select[name='"+_field[i]+"']").live('change',function(){
                $(this).nextAll('select').hide();
                $(this).nextAll('select').val('');
                if(settings.data[$(this).val()]!=undefined){
                    var _two=$.fn.linkage_style_1.getNext($(this).val(),'',settings);
                    $(this).next().html(_two).show();
                }
            });
        }
        $this.html($.fn.linkage_style_1.getNext(_index[0],_default[0],settings));
        $this.after(_select);
        $this.nextAll('select').hide();
        for(i=1;i<_select_nums;i++){
            if(_index[i]>=0){
                $('select[name="'+_field[i]+'"]').html($.fn.linkage_style_1.getNext(_index[i],_default[i],settings)).show();
            }
        }
    }
    /**
     * index_id 当先选中的值
     * select 默认应该选中的值
     * source_data 数据源
     */
    $.fn.linkage_style_1.getNext=function(index_id,select,source_data){
        var _str='<option value="">请选择</option>';
        if(source_data.data[index_id]){
            $.each(source_data.data[index_id],function(k,v){
                if(select==k){
                    _str+='<option value="'+k+'" selected="selected">'+v+'</option>';   
                }else{
                    _str+='<option value="'+k+'">'+v+'</option>';   
                }
            })
        }
        return _str;
    }
    $.fn.arrayFilter=function(arr){
        for(i=1;i<arr.length;i++){
            if(arr[i]=='' || arr[i+1]==0){
                arr.splice(i,1);
            }
        }
        return arr;
    }
})(jQuery);