<?php if(!defined("PATH_HD"))exit;?>
<table class="table-form">
        <tr>
        <th>开始时间</th>
        <td><input type="text" name="edu_start" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',autoPickDate:true})"  validate={"required":true,"maxlength":20}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>结束时间</th>
        <td><input type="text" name="edu_end" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',autoPickDate:true})"  validate={"required":true,"maxlength":10}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>学校名称</th>
        <td><input type="text" name="school"   validate={"required":true,"maxlength":30}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>专业名称</th>
        <td><input type="text" name="major"   validate={"required":true,"maxlength":30}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>学历</th>
        <td><select name="degree"   validate={"required":true} ><option value="">请选择</option><option value="1" selected="selected">初中</option><option value="2" >高中</option><option value="3" >中专</option><option value="4" >中技</option><option value="5" >大专</option><option value="6" >本科</option><option value="7" >硕士</option><option value="8" >MBA</option><option value="9" >EMBA</option><option value="10" >博士</option><option value="11" >其他</option></select><span></span></td>
    </tr>
    </table>