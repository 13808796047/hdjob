$(function(){
    $("#name").focus();
    $("#name").blur(function(){
        var _name=$(this).val(),
        _name_obj=$(this).parent().next(),
        _reg=/^[a-z]\w{5,19}$/i;
        if(!_reg.test(_name)){
            _name_obj.attr('class','error').html('用户名格式不正确！');
            return false;
        }
        $.post(url+'/index/auth/userExist', {
            name:_name
        }, function(returnData){
            if(returnData==1){
                _name_obj.attr('class','error').html('用户名已经存在！');
            }else{
                _name_obj.attr('class','success').empty();
            }
        }, 'html');
    });
    $("#pwd").blur(function(){
        var _pwd=$(this).val().length,
        _pwd_obj=$(this).parent().next();
        if(_pwd<6 || _pwd>20){
            _pwd_obj.attr('class','error').html('密码长度为6-20位！');
        }else{
            _pwd_obj.attr('class','success').empty();
        }
    });
    $("#re-pwd").blur(function(){
        var _re_pwd=$(this).val(),
        _re_pwd_obj=$(this).parent().next();
        if(_re_pwd!=$("#pwd").val()||_re_pwd.length<6 || _re_pwd.length>20){
            _re_pwd_obj.attr('class','error').html('两次密码不一致！');
        }else{
            _re_pwd_obj.attr('class','success').empty();
        }
    });
    $("#email").blur(function(){
        var _email=$(this).val(),
        _email_obj=$(this).parent().next(),
        _regexp=/^\w{2,20}@\w+\.[a-z]+(\.[a-z]+)?$/i;
        if(!_regexp.test(_email)){
            _email_obj.attr('class','error').html('Email输入错误！');
            return false;
        }
        $.post(url+'/index/auth/emailExist', {
            "email":_email
        }, function(data){
            if(data==1){
                _email_obj.attr('class','error').html('Email已经存在');
            }else{
                _email_obj.attr('class','success').empty();
            }
        }, 'html');
    });
    $("#form-area form").submit(function(){
        if($(this).find('.error').length>0){
            alert('请完善你的注册资料！');
            return false;
        }
        if($("#agree").is(':checked')==false){
            alert('同意注册协议后方可注册！');
            return false;
        }
    });
    $('#read-registration').toggle(function(){
        if($('#registration').css("right")=='10px'){
            $('#registration').animate({"top":"0px"});
        }else{
            $('#registration').animate({"right":"10px"});
        }
    },function(){
        if($('#registration').css("top")=='0px'){
            $('#registration').animate({"right":"-450px"});
        }else{
            $('#registration').animate({"right":"10px"});
        }
    });
})