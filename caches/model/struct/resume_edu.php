<?php return array (
  'edu_start' => 
  array (
    'title' => '开始时间',
    'field_tips' => '',
    'add_html' => '<input type="text" name="edu_start" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',autoPickDate:true})"  validate={"required":true,"maxlength":20}  value="" />',
    'edit_html' => '<input type="text" name="edu_start" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',autoPickDate:true})"  validate={"required":true,"maxlength":20}  value="<?php echo $edu_start;?>" />',
  ),
  'edu_end' => 
  array (
    'title' => '结束时间',
    'field_tips' => '',
    'add_html' => '<input type="text" name="edu_end" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',autoPickDate:true})"  validate={"required":true,"maxlength":10}  value="" />',
    'edit_html' => '<input type="text" name="edu_end" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',autoPickDate:true})"  validate={"required":true,"maxlength":10}  value="<?php echo $edu_end;?>" />',
  ),
  'school' => 
  array (
    'title' => '学校名称',
    'field_tips' => '',
    'add_html' => '<input type="text" name="school"   validate={"required":true,"maxlength":30}  value="" />',
    'edit_html' => '<input type="text" name="school"   validate={"required":true,"maxlength":30}  value="<?php echo $school;?>" />',
  ),
  'major' => 
  array (
    'title' => '专业名称',
    'field_tips' => '',
    'add_html' => '<input type="text" name="major"   validate={"required":true,"maxlength":30}  value="" />',
    'edit_html' => '<input type="text" name="major"   validate={"required":true,"maxlength":30}  value="<?php echo $major;?>" />',
  ),
  'degree' => 
  array (
    'title' => '学历',
    'field_tips' => '',
    'add_html' => '<select name="degree"   validate={"required":true} ><option value="">请选择</option><option value="1" selected="selected">初中</option><option value="2" >高中</option><option value="3" >中专</option><option value="4" >中技</option><option value="5" >大专</option><option value="6" >本科</option><option value="7" >硕士</option><option value="8" >MBA</option><option value="9" >EMBA</option><option value="10" >博士</option><option value="11" >其他</option></select>',
    'edit_html' => '<select name="degree"   validate={"required":true} ><option value="">请选择</option><option value="1" <?php if($degree=="1"):?>selected<?php endif;?>>初中</option><option value="2" <?php if($degree=="2"):?>selected<?php endif;?>>高中</option><option value="3" <?php if($degree=="3"):?>selected<?php endif;?>>中专</option><option value="4" <?php if($degree=="4"):?>selected<?php endif;?>>中技</option><option value="5" <?php if($degree=="5"):?>selected<?php endif;?>>大专</option><option value="6" <?php if($degree=="6"):?>selected<?php endif;?>>本科</option><option value="7" <?php if($degree=="7"):?>selected<?php endif;?>>硕士</option><option value="8" <?php if($degree=="8"):?>selected<?php endif;?>>MBA</option><option value="9" <?php if($degree=="9"):?>selected<?php endif;?>>EMBA</option><option value="10" <?php if($degree=="10"):?>selected<?php endif;?>>博士</option><option value="11" <?php if($degree=="11"):?>selected<?php endif;?>>其他</option></select>',
  ),
);