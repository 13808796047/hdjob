<?php return array (
  'recruit_name' => 
  array (
    'title' => '职位名称',
    'field_tips' => '',
    'add_html' => '<input type="text" name="recruit_name"   validate={"required":true,"messages":"请输入职位名称"}  value="" />',
    'edit_html' => '<input type="text" name="recruit_name"   validate={"required":true,"messages":"请输入职位名称"}  value="<?php echo $recruit_name;?>" />',
  ),
  'jobs_industry' => 
  array (
    'title' => '职位行业',
    'field_tips' => '最多选择5项',
    'add_html' => '<input type="text" id="jobs_industry" title="" value=""  /><script>$(function(){$("#jobs_industry").linkage_style_2({
                data:linkage_3,
                field:\'jobs_industry\',
                html_attr:\'\'
                })});</script>',
    'edit_html' => '<input type="text" id="jobs_industry" title="" value=""  /><script>$(function(){$("#jobs_industry").linkage_style_2({
                data:linkage_3,
                field:\'jobs_industry\',
                html_attr:\'\',defaults:\'<?php echo $jobs_industry;?>\'
                })});</script>',
  ),
  'class' => 
  array (
    'title' => '职位分类',
    'field_tips' => '',
    'add_html' => '<input type="text" id="class" title="" value=""  /><script>$(function(){$("#class").linkage_style_2({
                data:linkage_4,
                field:\'class#class_two\',
                html_attr:\'\'
                })});</script>',
    'edit_html' => '<input type="text" id="class" title="" value=""  /><script>$(function(){$("#class").linkage_style_2({
                data:linkage_4,
                field:\'class#class_two\',
                html_attr:\'\',defaults:\'<?php echo $class;?>#<?php echo $class_two;?>\'
                })});</script>',
  ),
  'effective_time' => 
  array (
    'title' => '有效时间',
    'field_tips' => '',
    'add_html' => '<input type="text" name="effective_time"  id="effective_time" validate={"required":true,"digits":true,"min":30,"regexp":/\\d+/,"messages":"请输入数字"}  value="" />',
    'edit_html' => '<input type="text" name="effective_time"  id="effective_time" validate={"required":true,"digits":true,"min":30,"regexp":/\\d+/,"messages":"请输入数字"}  value="<?php echo $effective_time;?>" />',
  ),
  'seo_desc' => 
  array (
    'title' => '职位摘要',
    'field_tips' => '',
    'add_html' => '<textarea name = "seo_desc"   validate={"maxlength":80}  style=" width:400px; height:80px;"  ></textarea>',
    'edit_html' => '<textarea name = "seo_desc"   validate={"maxlength":80}  style=" width:400px; height:80px;"  ><?php echo $seo_desc;?></textarea>',
  ),
  'job_desc' => 
  array (
    'title' => '职位描述',
    'field_tips' => '',
    'add_html' => '<script type="text/javascript">HD_UEDITOR_ROOT="__HDPHP__/org/ueditor/";</script><script type="text/javascript" src="__HDPHP__/org/ueditor/editor_config.js"></script>
		<script type="text/javascript" src="__HDPHP__/org/ueditor/editor_all.js"></script>
		<link rel="stylesheet" href="__HDPHP__/org/ueditor/themes/default/ueditor.css"><script type="text/plain" name="job_desc" id="job_desc" style=" width:500px">不告诉你</script><script type="text/javascript" >var editorOption = {
                         toolbars:[[\'Undo\', \'Redo\',\'Bold\',\'Italic\',\'Underline\',\'JustifyLeft\', \'JustifyCenter\', \'JustifyRight\',\'InsertOrderedList\',\'InsertUnorderedList\',\'FormatMatch\',\'Link\',\'Horizontal\']],
                         imageUrl:"__CONTROL__/ueditorupload",
                         imagePath:"",
                         fileUrl:"__CONTROL__/ueditorupload",
                         filePath:"",
                         maximumWords:100,
                         minFrameHeight:200
                    };var editor = new baidu.editor.ui.Editor(editorOption);
                    editor.render("job_desc")</script>',
    'edit_html' => '<script type="text/javascript">HD_UEDITOR_ROOT="__HDPHP__/org/ueditor/";</script><script type="text/javascript" src="__HDPHP__/org/ueditor/editor_config.js"></script>
		<script type="text/javascript" src="__HDPHP__/org/ueditor/editor_all.js"></script>
		<link rel="stylesheet" href="__HDPHP__/org/ueditor/themes/default/ueditor.css"><script type="text/plain" name="job_desc" id="job_desc" style=" width:500px"><?php echo htmlspecialchars_decode($job_desc);?></script><script type="text/javascript" >var editorOption = {
                         toolbars:[[\'Undo\', \'Redo\',\'Bold\',\'Italic\',\'Underline\',\'JustifyLeft\', \'JustifyCenter\', \'JustifyRight\',\'InsertOrderedList\',\'InsertUnorderedList\',\'FormatMatch\',\'Link\',\'Horizontal\']],
                         imageUrl:"__CONTROL__/ueditorupload",
                         imagePath:"",
                         fileUrl:"__CONTROL__/ueditorupload",
                         filePath:"",
                         maximumWords:100,
                         minFrameHeight:200
                    };var editor = new baidu.editor.ui.Editor(editorOption);
                    editor.render("job_desc")</script>',
  ),
  'sex' => 
  array (
    'title' => '性别要求',
    'field_tips' => '',
    'add_html' => '<label><input type="radio" name="sex"  value="0" checked="checked" />不限</label><label><input type="radio" name="sex"  value="1"  />男</label><label><input type="radio" name="sex"  value="2"  />女</label>',
    'edit_html' => '<label><input type="radio" name="sex"  value="0" <?php if(in_array("0",explode("#",$sex))):?>checked<?php endif;?> />不限</label><label><input type="radio" name="sex"  value="1" <?php if(in_array("1",explode("#",$sex))):?>checked<?php endif;?> />男</label><label><input type="radio" name="sex"  value="2" <?php if(in_array("2",explode("#",$sex))):?>checked<?php endif;?> />女</label>',
  ),
  'degree' => 
  array (
    'title' => '学历要求',
    'field_tips' => '',
    'add_html' => '<select style="margin-right:3px;" id="degree" name="degree" ></select><script>$(function(){$("#degree").linkage_style_1({
                data:linkage_18,
                field:\'degree\',
                html_attr:\'\'
                })});</script>',
    'edit_html' => '<select style="margin-right:3px;" id="degree" name="degree" ></select><script>$(function(){$("#degree").linkage_style_1({
                data:linkage_18,
                field:\'degree\',
                html_attr:\'\',defaults:\'<?php echo $degree;?>\'
                })});</script>',
  ),
  'jobs_property' => 
  array (
    'title' => '职位性质',
    'field_tips' => '',
    'add_html' => '<label><input type="radio" name="jobs_property"  value="0" validate={"required":true}  checked="checked" />实习</label><label><input type="radio" name="jobs_property"  value="1" validate={"required":true}   />兼职</label><label><input type="radio" name="jobs_property"  value="2" validate={"required":true}   />全职</label>',
    'edit_html' => '<label><input type="radio" name="jobs_property"  value="0" validate={"required":true}  <?php if(in_array("0",explode("#",$jobs_property))):?>checked<?php endif;?> />实习</label><label><input type="radio" name="jobs_property"  value="1" validate={"required":true}  <?php if(in_array("1",explode("#",$jobs_property))):?>checked<?php endif;?> />兼职</label><label><input type="radio" name="jobs_property"  value="2" validate={"required":true}  <?php if(in_array("2",explode("#",$jobs_property))):?>checked<?php endif;?> />全职</label>',
  ),
  'graduates' => 
  array (
    'title' => '应届生应聘',
    'field_tips' => '',
    'add_html' => '<label><input type="radio" name="graduates"  value="1" checked="checked" />允许</label><label><input type="radio" name="graduates"  value="0"  />不允许</label>',
    'edit_html' => '<label><input type="radio" name="graduates"  value="1" <?php if(in_array("1",explode("#",$graduates))):?>checked<?php endif;?> />允许</label><label><input type="radio" name="graduates"  value="0" <?php if(in_array("0",explode("#",$graduates))):?>checked<?php endif;?> />不允许</label>',
  ),
  'salary' => 
  array (
    'title' => '职位月薪',
    'field_tips' => '',
    'add_html' => '<select name="salary"  ><option value="">请选择</option><option value="1000" >1000元/月以下</option><option value="100002000" >1000-2000元/月</option><option value="200104000" >2001-4000元/月</option><option value="400106000" >4001-6000元/月</option><option value="600108000" >6001-8000元/月</option><option value="800110000" >8001-10000元/月</option><option value="1000115000" >10001-15000元/月</option><option value="1500025000" >15000-25000元/月</option><option value="2500000000" >25000元/月以上</option></select>',
    'edit_html' => '<select name="salary"  ><option value="">请选择</option><option value="1000" <?php if($salary=="1000"):?>selected<?php endif;?>>1000元/月以下</option><option value="100002000" <?php if($salary=="100002000"):?>selected<?php endif;?>>1000-2000元/月</option><option value="200104000" <?php if($salary=="200104000"):?>selected<?php endif;?>>2001-4000元/月</option><option value="400106000" <?php if($salary=="400106000"):?>selected<?php endif;?>>4001-6000元/月</option><option value="600108000" <?php if($salary=="600108000"):?>selected<?php endif;?>>6001-8000元/月</option><option value="800110000" <?php if($salary=="800110000"):?>selected<?php endif;?>>8001-10000元/月</option><option value="1000115000" <?php if($salary=="1000115000"):?>selected<?php endif;?>>10001-15000元/月</option><option value="1500025000" <?php if($salary=="1500025000"):?>selected<?php endif;?>>15000-25000元/月</option><option value="2500000000" <?php if($salary=="2500000000"):?>selected<?php endif;?>>25000元/月以上</option></select>',
  ),
  'work_exp' => 
  array (
    'title' => '工作经验',
    'field_tips' => '',
    'add_html' => '<select name="work_exp"   validate={"required":true} ><option value="">请选择</option><option value="0" selected="selected">不限</option><option value="1" >无经验</option><option value="2" >1年以下</option><option value="3" >1-3年</option><option value="4" >3-5年</option><option value="5" >5-10年</option><option value="6" >10年以上</option></select>',
    'edit_html' => '<select name="work_exp"   validate={"required":true} ><option value="">请选择</option><option value="0" <?php if($work_exp=="0"):?>selected<?php endif;?>>不限</option><option value="1" <?php if($work_exp=="1"):?>selected<?php endif;?>>无经验</option><option value="2" <?php if($work_exp=="2"):?>selected<?php endif;?>>1年以下</option><option value="3" <?php if($work_exp=="3"):?>selected<?php endif;?>>1-3年</option><option value="4" <?php if($work_exp=="4"):?>selected<?php endif;?>>3-5年</option><option value="5" <?php if($work_exp=="5"):?>selected<?php endif;?>>5-10年</option><option value="6" <?php if($work_exp=="6"):?>selected<?php endif;?>>10年以上</option></select>',
  ),
  'issue_type' => 
  array (
    'title' => '发布日期',
    'field_tips' => '',
    'add_html' => '<label><input type="radio" name="issue_type"  value="1" checked="checked" />立即发布</label><label><input type="radio" name="issue_type"  value="2"  />定时发布</label>',
    'edit_html' => '<label><input type="radio" name="issue_type"  value="1" <?php if(in_array("1",explode("#",$issue_type))):?>checked<?php endif;?> />立即发布</label><label><input type="radio" name="issue_type"  value="2" <?php if(in_array("2",explode("#",$issue_type))):?>checked<?php endif;?> />定时发布</label>',
  ),
  'start_time' => 
  array (
    'title' => '',
    'field_tips' => '',
    'add_html' => '<input type="text" name="start_time" onfocus="WdatePicker({minDate:\'%yyyy-%MM-%dd %HH:%mm:%ss\',alwaysUseStartDate:true,dateFmt:\'yyyy-MM-dd HH:mm:ss\',autoPickDate:true,vel:\'start_time\'})" id="start_time" style="display:none" value="" />',
    'edit_html' => '<input type="text" name="start_time" onfocus="WdatePicker({minDate:\'%yyyy-%MM-%dd %HH:%mm:%ss\',alwaysUseStartDate:true,dateFmt:\'yyyy-MM-dd HH:mm:ss\',autoPickDate:true,vel:\'start_time\'})" id="start_time" style="display:none" value="<?php echo $start_time;?>" />',
  ),
  'recruit_num' => 
  array (
    'title' => '招聘人数',
    'field_tips' => '0表示若干人',
    'add_html' => '<input type="text" name="recruit_num"   validate={"required":true,"digits":true}  value="0" />',
    'edit_html' => '<input type="text" name="recruit_num"   validate={"required":true,"digits":true}  value="<?php echo $recruit_num;?>" />',
  ),
  'address' => 
  array (
    'title' => '工作地点',
    'field_tips' => '',
    'add_html' => '<select style="margin-right:3px;" id="address" name="address" class="input-medium" validate={"required":true} ></select><script>$(function(){$("#address").linkage_style_1({
                data:city,
                field:\'address#city#town\',
                html_attr:\'class="input-medium" validate={"required":true} \'
                })});</script>',
    'edit_html' => '<select style="margin-right:3px;" id="address" name="address" class="input-medium" validate={"required":true} ></select><script>$(function(){$("#address").linkage_style_1({
                data:city,
                field:\'address#city#town\',
                html_attr:\'class="input-medium" validate={"required":true} \',defaults:\'<?php echo $address;?>#<?php echo $city;?>#<?php echo $town;?>\'
                })});</script>',
  ),
  'company_name' => 
  array (
    'title' => '公司名称',
    'field_tips' => '',
    'add_html' => '<input type="text" name="company_name"   value="" />',
    'edit_html' => '<input type="text" name="company_name"   value="<?php echo $company_name;?>" />',
  ),
  'company_property' => 
  array (
    'title' => '公司性质',
    'field_tips' => '',
    'add_html' => '<select name="company_property"   validate={"min":1} ><option value="">请选择</option><option value="1" >国企</option><option value="2" >外商独资</option><option value="3" >代表处</option><option value="4" >合资</option><option value="5" >民营</option><option value="6" >股份制企业</option><option value="7" >上市公司</option><option value="8" >国家机关</option><option value="9" >事业单位</option><option value="10" >其它</option></select>',
    'edit_html' => '<select name="company_property"   validate={"min":1} ><option value="">请选择</option><option value="1" <?php if($company_property=="1"):?>selected<?php endif;?>>国企</option><option value="2" <?php if($company_property=="2"):?>selected<?php endif;?>>外商独资</option><option value="3" <?php if($company_property=="3"):?>selected<?php endif;?>>代表处</option><option value="4" <?php if($company_property=="4"):?>selected<?php endif;?>>合资</option><option value="5" <?php if($company_property=="5"):?>selected<?php endif;?>>民营</option><option value="6" <?php if($company_property=="6"):?>selected<?php endif;?>>股份制企业</option><option value="7" <?php if($company_property=="7"):?>selected<?php endif;?>>上市公司</option><option value="8" <?php if($company_property=="8"):?>selected<?php endif;?>>国家机关</option><option value="9" <?php if($company_property=="9"):?>selected<?php endif;?>>事业单位</option><option value="10" <?php if($company_property=="10"):?>selected<?php endif;?>>其它</option></select>',
  ),
  'company_scope' => 
  array (
    'title' => '公司规模',
    'field_tips' => '',
    'add_html' => '<select name="company_scope"   validate={"required":true,"minlength":1} ><option value="">请选择</option><option value="1" >20人以下</option><option value="2" >20-99人</option><option value="3" >100-499人</option><option value="4" >500-999人</option><option value="5" >1000-9999人</option><option value="6" >10000人以上</option></select>',
    'edit_html' => '<select name="company_scope"   validate={"required":true,"minlength":1} ><option value="">请选择</option><option value="1" <?php if($company_scope=="1"):?>selected<?php endif;?>>20人以下</option><option value="2" <?php if($company_scope=="2"):?>selected<?php endif;?>>20-99人</option><option value="3" <?php if($company_scope=="3"):?>selected<?php endif;?>>100-499人</option><option value="4" <?php if($company_scope=="4"):?>selected<?php endif;?>>500-999人</option><option value="5" <?php if($company_scope=="5"):?>selected<?php endif;?>>1000-9999人</option><option value="6" <?php if($company_scope=="6"):?>selected<?php endif;?>>10000人以上</option></select>',
  ),
  'company_index' => 
  array (
    'title' => '公司主页',
    'field_tips' => '',
    'add_html' => '<input type="text" name="company_index"   validate={"url":true}  value="http://" />',
    'edit_html' => '<input type="text" name="company_index"   validate={"url":true}  value="<?php echo $company_index;?>" />',
  ),
  'company_desc' => 
  array (
    'title' => '公司介绍',
    'field_tips' => '',
    'add_html' => '<textarea name = "company_desc"   ></textarea>',
    'edit_html' => '<textarea name = "company_desc"   ><?php echo $company_desc;?></textarea>',
  ),
  'contact' => 
  array (
    'title' => '联系人',
    'field_tips' => '',
    'add_html' => '<input type="text" name="contact"   value="" />',
    'edit_html' => '<input type="text" name="contact"   value="<?php echo $contact;?>" />',
  ),
  'phone' => 
  array (
    'title' => '联系电话',
    'field_tips' => '',
    'add_html' => '<input type="text" name="phone"   value="" />',
    'edit_html' => '<input type="text" name="phone"   value="<?php echo $phone;?>" />',
  ),
  'rece_mail' => 
  array (
    'title' => '接收邮箱',
    'field_tips' => '',
    'add_html' => '<input type="text" name="rece_mail"   validate={"email":true}  value="" />',
    'edit_html' => '<input type="text" name="rece_mail"   validate={"email":true}  value="<?php echo $rece_mail;?>" />',
  ),
);