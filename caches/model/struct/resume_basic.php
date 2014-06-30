<?php return array (
  'name' => 
  array (
    'title' => '真实姓名',
    'field_tips' => '',
    'add_html' => '<input type="text" name="name"   validate={"required":true}  value="" />',
    'edit_html' => '<input type="text" name="name"   validate={"required":true}  value="<?php echo $name;?>" />',
  ),
  'gender' => 
  array (
    'title' => '性别',
    'field_tips' => '',
    'add_html' => '<label><input type="radio" name="gender"  value="1" validate={"required":true}   />男</label><label><input type="radio" name="gender"  value="2" validate={"required":true}   />女</label>',
    'edit_html' => '<label><input type="radio" name="gender"  value="1" validate={"required":true}  <?php if(in_array("1",explode("#",$gender))):?>checked<?php endif;?> />男</label><label><input type="radio" name="gender"  value="2" validate={"required":true}  <?php if(in_array("2",explode("#",$gender))):?>checked<?php endif;?> />女</label>',
  ),
  'birthday' => 
  array (
    'title' => '出生日期（年）',
    'field_tips' => '',
    'add_html' => '<input type="text" name="birthday" onfocus="WdatePicker({dateFmt:\'yyyy\',minDate:\'1960\',startDate:\'1980\',autoPickDate:true})"  validate={"required":true,"digits":true}  value="" />',
    'edit_html' => '<input type="text" name="birthday" onfocus="WdatePicker({dateFmt:\'yyyy\',minDate:\'1960\',startDate:\'1980\',autoPickDate:true})"  validate={"required":true,"digits":true}  value="<?php echo $birthday;?>" />',
  ),
  'marital_status' => 
  array (
    'title' => '婚姻状况',
    'field_tips' => '',
    'add_html' => '<label><input type="radio" name="marital_status"  value="1" validate={"required":true}   />未婚</label><label><input type="radio" name="marital_status"  value="2" validate={"required":true}   />已婚</label><label><input type="radio" name="marital_status"  value="3" validate={"required":true}   />保密</label>',
    'edit_html' => '<label><input type="radio" name="marital_status"  value="1" validate={"required":true}  <?php if(in_array("1",explode("#",$marital_status))):?>checked<?php endif;?> />未婚</label><label><input type="radio" name="marital_status"  value="2" validate={"required":true}  <?php if(in_array("2",explode("#",$marital_status))):?>checked<?php endif;?> />已婚</label><label><input type="radio" name="marital_status"  value="3" validate={"required":true}  <?php if(in_array("3",explode("#",$marital_status))):?>checked<?php endif;?> />保密</label>',
  ),
  'origin_provice' => 
  array (
    'title' => '户口所在地',
    'field_tips' => '',
    'add_html' => '<select style="margin-right:3px;" id="origin_provice" name="origin_provice"  validate={"required":true} ></select><script>$(function(){$("#origin_provice").linkage_style_1({
                data:city,
                field:\'origin_provice#origin_city#origin_town\',
                html_attr:\' validate={"required":true} \'
                })});</script>',
    'edit_html' => '<select style="margin-right:3px;" id="origin_provice" name="origin_provice"  validate={"required":true} ></select><script>$(function(){$("#origin_provice").linkage_style_1({
                data:city,
                field:\'origin_provice#origin_city#origin_town\',
                html_attr:\' validate={"required":true} \',defaults:\'<?php echo $origin_provice;?>#<?php echo $origin_city;?>#<?php echo $origin_town;?>\'
                })});</script>',
  ),
  'link_provice' => 
  array (
    'title' => '联系地址',
    'field_tips' => '',
    'add_html' => '<select style="margin-right:3px;" id="link_provice" name="link_provice"  validate={"required":true} ></select><script>$(function(){$("#link_provice").linkage_style_1({
                data:city,
                field:\'link_provice#link_city#link_town\',
                html_attr:\' validate={"required":true} \'
                })});</script>',
    'edit_html' => '<select style="margin-right:3px;" id="link_provice" name="link_provice"  validate={"required":true} ></select><script>$(function(){$("#link_provice").linkage_style_1({
                data:city,
                field:\'link_provice#link_city#link_town\',
                html_attr:\' validate={"required":true} \',defaults:\'<?php echo $link_provice;?>#<?php echo $link_city;?>#<?php echo $link_town;?>\'
                })});</script>',
  ),
  'cert_type' => 
  array (
    'title' => '证件类型',
    'field_tips' => '',
    'add_html' => '<select name="cert_type"  ><option value="">请选择</option><option value="1" >身份证</option><option value="2" >护照</option><option value="3" >军官证</option><option value="4" >香港身份证</option><option value="5" >澳门身份证</option><option value="6" >港澳通行证</option><option value="7" >台胞证</option><option value="8" >其他</option></select>',
    'edit_html' => '<select name="cert_type"  ><option value="">请选择</option><option value="1" <?php if($cert_type=="1"):?>selected<?php endif;?>>身份证</option><option value="2" <?php if($cert_type=="2"):?>selected<?php endif;?>>护照</option><option value="3" <?php if($cert_type=="3"):?>selected<?php endif;?>>军官证</option><option value="4" <?php if($cert_type=="4"):?>selected<?php endif;?>>香港身份证</option><option value="5" <?php if($cert_type=="5"):?>selected<?php endif;?>>澳门身份证</option><option value="6" <?php if($cert_type=="6"):?>selected<?php endif;?>>港澳通行证</option><option value="7" <?php if($cert_type=="7"):?>selected<?php endif;?>>台胞证</option><option value="8" <?php if($cert_type=="8"):?>selected<?php endif;?>>其他</option></select>',
  ),
  'id_number' => 
  array (
    'title' => '证件号码',
    'field_tips' => '',
    'add_html' => '<input type="text" name="id_number"   validate={"required":true,"maxlength":20}  value="" />',
    'edit_html' => '<input type="text" name="id_number"   validate={"required":true,"maxlength":20}  value="<?php echo $id_number;?>" />',
  ),
  'work_exp' => 
  array (
    'title' => '工作经验',
    'field_tips' => '',
    'add_html' => '<select style="margin-right:3px;" id="work_exp" name="work_exp" ></select><script>$(function(){$("#work_exp").linkage_style_1({
                data:linkage_22,
                field:\'work_exp\',
                html_attr:\'\'
                })});</script>',
    'edit_html' => '<select style="margin-right:3px;" id="work_exp" name="work_exp" ></select><script>$(function(){$("#work_exp").linkage_style_1({
                data:linkage_22,
                field:\'work_exp\',
                html_attr:\'\',defaults:\'<?php echo $work_exp;?>\'
                })});</script>',
  ),
  'telephone' => 
  array (
    'title' => '联系电话',
    'field_tips' => '',
    'add_html' => '<input type="text" name="telephone"   value="" />',
    'edit_html' => '<input type="text" name="telephone"   value="<?php echo $telephone;?>" />',
  ),
  'link_email' => 
  array (
    'title' => '联系Email',
    'field_tips' => '',
    'add_html' => '<input type="text" name="link_email"   validate={"email":true,"maxlength":30}  value="" />',
    'edit_html' => '<input type="text" name="link_email"   validate={"email":true,"maxlength":30}  value="<?php echo $link_email;?>" />',
  ),
  'profile' => 
  array (
    'title' => '个人主页',
    'field_tips' => '',
    'add_html' => '<input type="text" name="profile"   validate={"required":true,"url":true,"maxlength":100}  value="" />',
    'edit_html' => '<input type="text" name="profile"   validate={"required":true,"url":true,"maxlength":100}  value="<?php echo $profile;?>" />',
  ),
  'self_eval' => 
  array (
    'title' => '自我评价',
    'field_tips' => '',
    'add_html' => '<textarea name = "self_eval"   validate={"maxlength":200}  ></textarea>',
    'edit_html' => '<textarea name = "self_eval"   validate={"maxlength":200}  ><?php echo $self_eval;?></textarea>',
  ),
  'address' => 
  array (
    'title' => '详细地址',
    'field_tips' => '',
    'add_html' => '<input type="text" name="address"   validate={"required":true,"maxlength":25}  value="" />',
    'edit_html' => '<input type="text" name="address"   validate={"required":true,"maxlength":25}  value="<?php echo $address;?>" />',
  ),
  'work_type' => 
  array (
    'title' => '期望工作性质',
    'field_tips' => '',
    'add_html' => '<label><input type="checkbox" name="work_type[]"  value="1" validate={"required":true}  checked="checked" />全职</label><label><input type="checkbox" name="work_type[]"  value="2" validate={"required":true}   />兼职</label><label><input type="checkbox" name="work_type[]"  value="3" validate={"required":true}   />实习</label>',
    'edit_html' => '<label><input type="checkbox" name="work_type[]"  value="1" validate={"required":true}  <?php if(in_array("1",explode("#",$work_type))):?>checked<?php endif;?> />全职</label><label><input type="checkbox" name="work_type[]"  value="2" validate={"required":true}  <?php if(in_array("2",explode("#",$work_type))):?>checked<?php endif;?> />兼职</label><label><input type="checkbox" name="work_type[]"  value="3" validate={"required":true}  <?php if(in_array("3",explode("#",$work_type))):?>checked<?php endif;?> />实习</label>',
  ),
  'hope_industry' => 
  array (
    'title' => '期望从事行业',
    'field_tips' => '',
    'add_html' => '<input type="text" id="hope_industry" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_industry").linkage_style_2({
                data:linkage_3,
                field:\'hope_industry\',
                html_attr:\' validate={"required":true} \',checkbox:true
                })});</script>',
    'edit_html' => '<input type="text" id="hope_industry" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_industry").linkage_style_2({
                data:linkage_3,
                field:\'hope_industry\',
                html_attr:\' validate={"required":true} \',defaults:\'<?php echo $hope_industry;?>\',checkbox:true
                })});</script>',
  ),
  'hope_career' => 
  array (
    'title' => '期望从事职业',
    'field_tips' => '',
    'add_html' => '<input type="text" id="hope_career" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_career").linkage_style_2({
                data:linkage_4,
                field:\'hope_career#hope_career_t\',
                html_attr:\' validate={"required":true} \',checkbox:true
                })});</script>',
    'edit_html' => '<input type="text" id="hope_career" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_career").linkage_style_2({
                data:linkage_4,
                field:\'hope_career#hope_career_t\',
                html_attr:\' validate={"required":true} \',defaults:\'<?php echo $hope_career;?>#<?php echo $hope_career_t;?>\',checkbox:true
                })});</script>',
  ),
  'hope_provice' => 
  array (
    'title' => '期望工作地点',
    'field_tips' => '',
    'add_html' => '<select style="margin-right:3px;" id="hope_provice" name="hope_provice"  validate={"required":true} ></select><script>$(function(){$("#hope_provice").linkage_style_1({
                data:city,
                field:\'hope_provice#hope_city#hope_town\',
                html_attr:\' validate={"required":true} \'
                })});</script>',
    'edit_html' => '<select style="margin-right:3px;" id="hope_provice" name="hope_provice"  validate={"required":true} ></select><script>$(function(){$("#hope_provice").linkage_style_1({
                data:city,
                field:\'hope_provice#hope_city#hope_town\',
                html_attr:\' validate={"required":true} \',defaults:\'<?php echo $hope_provice;?>#<?php echo $hope_city;?>#<?php echo $hope_town;?>\'
                })});</script>',
  ),
  'hope_salary' => 
  array (
    'title' => '期望月薪(税前)',
    'field_tips' => '',
    'add_html' => '<select style="margin-right:3px;" id="hope_salary" name="hope_salary"  validate={"required":true} ></select><script>$(function(){$("#hope_salary").linkage_style_1({
                data:linkage_19,
                field:\'hope_salary\',
                html_attr:\' validate={"required":true} \',defaults:\'200104000\'
                })});</script>',
    'edit_html' => '<select style="margin-right:3px;" id="hope_salary" name="hope_salary"  validate={"required":true} ></select><script>$(function(){$("#hope_salary").linkage_style_1({
                data:linkage_19,
                field:\'hope_salary\',
                html_attr:\' validate={"required":true} \',defaults:\'<?php echo $hope_salary;?>\'
                })});</script>',
  ),
);