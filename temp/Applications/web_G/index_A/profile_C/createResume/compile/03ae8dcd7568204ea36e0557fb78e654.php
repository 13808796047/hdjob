<?php if(!defined("PATH_HD"))exit;?>
<script type="text/javascript" src="http://127.0.0.1//hdjob/caches/js/linkage_data.js"></script><script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/linkage/linkage_style_1.js"></script><script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/linkage/linkage_style_2.js"></script><table class="table-form">
        <tr>
        <th>真实姓名</th>
        <td><input type="text" name="name"   validate={"required":true}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>性别</th>
        <td><label><input type="radio" name="gender"  value="1" validate={"required":true}   />男</label><label><input type="radio" name="gender"  value="2" validate={"required":true}   />女</label><span></span></td>
    </tr>
        <tr>
        <th>出生日期（年）</th>
        <td><input type="text" name="birthday" onfocus="WdatePicker({dateFmt:'yyyy',minDate:'1960',startDate:'1980',autoPickDate:true})"  validate={"required":true,"digits":true}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>婚姻状况</th>
        <td><label><input type="radio" name="marital_status"  value="1" validate={"required":true}   />未婚</label><label><input type="radio" name="marital_status"  value="2" validate={"required":true}   />已婚</label><label><input type="radio" name="marital_status"  value="3" validate={"required":true}   />保密</label><span></span></td>
    </tr>
        <tr>
        <th>户口所在地</th>
        <td><select style="margin-right:3px;" id="origin_provice" name="origin_provice"  validate={"required":true} ></select><script>$(function(){$("#origin_provice").linkage_style_1({
                data:city,
                field:'origin_provice#origin_city#origin_town',
                html_attr:' validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>联系地址</th>
        <td><select style="margin-right:3px;" id="link_provice" name="link_provice"  validate={"required":true} ></select><script>$(function(){$("#link_provice").linkage_style_1({
                data:city,
                field:'link_provice#link_city#link_town',
                html_attr:' validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>证件类型</th>
        <td><select name="cert_type"  ><option value="">请选择</option><option value="1" >身份证</option><option value="2" >护照</option><option value="3" >军官证</option><option value="4" >香港身份证</option><option value="5" >澳门身份证</option><option value="6" >港澳通行证</option><option value="7" >台胞证</option><option value="8" >其他</option></select><span></span></td>
    </tr>
        <tr>
        <th>证件号码</th>
        <td><input type="text" name="id_number"   validate={"required":true,"maxlength":20}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>工作经验</th>
        <td><select style="margin-right:3px;" id="work_exp" name="work_exp" ></select><script>$(function(){$("#work_exp").linkage_style_1({
                data:linkage_22,
                field:'work_exp',
                html_attr:''
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>联系电话</th>
        <td><input type="text" name="telephone"   value="" /><span></span></td>
    </tr>
        <tr>
        <th>联系Email</th>
        <td><input type="text" name="link_email"   validate={"email":true,"maxlength":30}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>个人主页</th>
        <td><input type="text" name="profile"   validate={"required":true,"url":true,"maxlength":100}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>自我评价</th>
        <td><textarea name = "self_eval"   validate={"maxlength":200}  ></textarea><span></span></td>
    </tr>
        <tr>
        <th>详细地址</th>
        <td><input type="text" name="address"   validate={"required":true,"maxlength":25}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>期望工作性质</th>
        <td><label><input type="checkbox" name="work_type[]"  value="1" validate={"required":true}  checked="checked" />全职</label><label><input type="checkbox" name="work_type[]"  value="2" validate={"required":true}   />兼职</label><label><input type="checkbox" name="work_type[]"  value="3" validate={"required":true}   />实习</label><span></span></td>
    </tr>
        <tr>
        <th>期望从事行业</th>
        <td><input type="text" id="hope_industry" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_industry").linkage_style_2({
                data:linkage_3,
                field:'hope_industry',
                html_attr:' validate={"required":true} ',checkbox:true
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>期望从事职业</th>
        <td><input type="text" id="hope_career" title="" value=""  validate={"required":true}  /><script>$(function(){$("#hope_career").linkage_style_2({
                data:linkage_4,
                field:'hope_career#hope_career_t',
                html_attr:' validate={"required":true} ',checkbox:true
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>期望工作地点</th>
        <td><select style="margin-right:3px;" id="hope_provice" name="hope_provice"  validate={"required":true} ></select><script>$(function(){$("#hope_provice").linkage_style_1({
                data:city,
                field:'hope_provice#hope_city#hope_town',
                html_attr:' validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>期望月薪(税前)</th>
        <td><select style="margin-right:3px;" id="hope_salary" name="hope_salary"  validate={"required":true} ></select><script>$(function(){$("#hope_salary").linkage_style_1({
                data:linkage_19,
                field:'hope_salary',
                html_attr:' validate={"required":true} ',defaults:'200104000'
                })});</script><span></span></td>
    </tr>
    </table>