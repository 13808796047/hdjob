<?php return array (
  'industry' => 
  array (
    'title' => '公司行业',
    'field_tips' => '',
    'add_html' => '<input type="text" id="industry" title="" value=""  validate={"required":true}  /><script>$(function(){$("#industry").linkage_style_2({
                data:linkage_3,
                field:\'industry\',
                html_attr:\' validate={"required":true} \'
                })});</script>',
    'edit_html' => '<input type="text" id="industry" title="" value=""  validate={"required":true}  /><script>$(function(){$("#industry").linkage_style_2({
                data:linkage_3,
                field:\'industry\',
                html_attr:\' validate={"required":true} \',defaults:\'<?php echo $industry;?>\'
                })});</script>',
  ),
  'department' => 
  array (
    'title' => '所在的部门',
    'field_tips' => '',
    'add_html' => '<input type="text" name="department"   validate={"required":true,"maxlength":10}  value="" />',
    'edit_html' => '<input type="text" name="department"   validate={"required":true,"maxlength":10}  value="<?php echo $department;?>" />',
  ),
  'job_name' => 
  array (
    'title' => '职位名称',
    'field_tips' => '',
    'add_html' => '<input type="text" name="job_name"   validate={"required":true,"maxlength":15}  value="" />',
    'edit_html' => '<input type="text" name="job_name"   validate={"required":true,"maxlength":15}  value="<?php echo $job_name;?>" />',
  ),
  'job_start' => 
  array (
    'title' => '工作时间（开始）',
    'field_tips' => '',
    'add_html' => '<input type="text" name="job_start" onfocus="WdatePicker()"  validate={"required":true,"maxlength":10}  value="" />',
    'edit_html' => '<input type="text" name="job_start" onfocus="WdatePicker()"  validate={"required":true,"maxlength":10}  value="<?php echo $job_start;?>" />',
  ),
  'job_end' => 
  array (
    'title' => '工作时间（结束）',
    'field_tips' => '',
    'add_html' => '<input type="text" name="job_end" onfocus="WdatePicker()"  validate={"required":true,"maxlength":10}  value="" />',
    'edit_html' => '<input type="text" name="job_end" onfocus="WdatePicker()"  validate={"required":true,"maxlength":10}  value="<?php echo $job_end;?>" />',
  ),
  'job_desc' => 
  array (
    'title' => '工作描述',
    'field_tips' => '',
    'add_html' => '<textarea name = "job_desc"   validate={"required":true,"maxlength":250}  ></textarea>',
    'edit_html' => '<textarea name = "job_desc"   validate={"required":true,"maxlength":250}  ><?php echo $job_desc;?></textarea>',
  ),
  'salary' => 
  array (
    'title' => '职位月薪',
    'field_tips' => '',
    'add_html' => '<select name="salary"   validate={"required":true} ><option value="">请选择</option><option value="1000" >1000元/月以下</option><option value="100002000" >1000-2000元/月</option><option value="200104000" >2001-4000元/月</option><option value="400106000" >4001-6000元/月</option><option value="600108000" >6001-8000元/月</option><option value="800110000" >8001-10000元/月</option><option value="1000115000" >10001-15000元/月</option><option value="1500025000" >15000-25000元/月</option><option value="2500000000" >25000元/月以上</option></select>',
    'edit_html' => '<select name="salary"   validate={"required":true} ><option value="">请选择</option><option value="1000" <?php if($salary=="1000"):?>selected<?php endif;?>>1000元/月以下</option><option value="100002000" <?php if($salary=="100002000"):?>selected<?php endif;?>>1000-2000元/月</option><option value="200104000" <?php if($salary=="200104000"):?>selected<?php endif;?>>2001-4000元/月</option><option value="400106000" <?php if($salary=="400106000"):?>selected<?php endif;?>>4001-6000元/月</option><option value="600108000" <?php if($salary=="600108000"):?>selected<?php endif;?>>6001-8000元/月</option><option value="800110000" <?php if($salary=="800110000"):?>selected<?php endif;?>>8001-10000元/月</option><option value="1000115000" <?php if($salary=="1000115000"):?>selected<?php endif;?>>10001-15000元/月</option><option value="1500025000" <?php if($salary=="1500025000"):?>selected<?php endif;?>>15000-25000元/月</option><option value="2500000000" <?php if($salary=="2500000000"):?>selected<?php endif;?>>25000元/月以上</option></select>',
  ),
  'company_name' => 
  array (
    'title' => '公司名称',
    'field_tips' => '',
    'add_html' => '<input type="text" name="company_name"   validate={"required":true,"maxlength":30}  value="" />',
    'edit_html' => '<input type="text" name="company_name"   validate={"required":true,"maxlength":30}  value="<?php echo $company_name;?>" />',
  ),
  'company_property' => 
  array (
    'title' => '公司性质',
    'field_tips' => '',
    'add_html' => '<select name="company_property"   validate={"required":true} ><option value="">请选择</option><option value="1" >国企</option><option value="2" >外商独资</option><option value="3" >代表处</option><option value="4" >合资</option><option value="5" >民营</option><option value="6" >股份制企业</option><option value="7" >上市公司</option><option value="8" >国家机关</option><option value="9" >事业单位</option><option value="10" >其它</option></select>',
    'edit_html' => '<select name="company_property"   validate={"required":true} ><option value="">请选择</option><option value="1" <?php if($company_property=="1"):?>selected<?php endif;?>>国企</option><option value="2" <?php if($company_property=="2"):?>selected<?php endif;?>>外商独资</option><option value="3" <?php if($company_property=="3"):?>selected<?php endif;?>>代表处</option><option value="4" <?php if($company_property=="4"):?>selected<?php endif;?>>合资</option><option value="5" <?php if($company_property=="5"):?>selected<?php endif;?>>民营</option><option value="6" <?php if($company_property=="6"):?>selected<?php endif;?>>股份制企业</option><option value="7" <?php if($company_property=="7"):?>selected<?php endif;?>>上市公司</option><option value="8" <?php if($company_property=="8"):?>selected<?php endif;?>>国家机关</option><option value="9" <?php if($company_property=="9"):?>selected<?php endif;?>>事业单位</option><option value="10" <?php if($company_property=="10"):?>selected<?php endif;?>>其它</option></select>',
  ),
  'company_scope' => 
  array (
    'title' => '公司规模',
    'field_tips' => '',
    'add_html' => '<select name="company_scope"  ><option value="">请选择</option><option value="1" selected="selected">20人以下</option><option value="2" >20-99人</option><option value="3" >100-499人</option><option value="4" >500-999人</option><option value="5" >1000-9999人</option><option value="6" >10000人以上</option></select>',
    'edit_html' => '<select name="company_scope"  ><option value="">请选择</option><option value="1" <?php if($company_scope=="1"):?>selected<?php endif;?>>20人以下</option><option value="2" <?php if($company_scope=="2"):?>selected<?php endif;?>>20-99人</option><option value="3" <?php if($company_scope=="3"):?>selected<?php endif;?>>100-499人</option><option value="4" <?php if($company_scope=="4"):?>selected<?php endif;?>>500-999人</option><option value="5" <?php if($company_scope=="5"):?>selected<?php endif;?>>1000-9999人</option><option value="6" <?php if($company_scope=="6"):?>selected<?php endif;?>>10000人以上</option></select>',
  ),
);