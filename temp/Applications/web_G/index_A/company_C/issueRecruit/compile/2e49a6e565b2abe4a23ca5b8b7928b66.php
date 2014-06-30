<?php if(!defined("PATH_HD"))exit;?>
<script type="text/javascript" src="http://127.0.0.1//hdjob/caches/js/linkage_data.js"></script><script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/linkage/linkage_style_1.js"></script><script type="text/javascript" src="http://127.0.0.1//hdjob/public/js/linkage/linkage_style_2.js"></script><table class="table-form">
        <tr>
        <th>职位名称</th>
        <td><input type="text" name="recruit_name"   validate={"required":true,"messages":"请输入职位名称"}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>职位行业</th>
        <td><input type="text" id="jobs_industry" title="" value=""  /><script>$(function(){$("#jobs_industry").linkage_style_2({
                data:linkage_3,
                field:'jobs_industry',
                html_attr:''
                })});</script><span>最多选择5项</span></td>
    </tr>
        <tr>
        <th>职位分类</th>
        <td><input type="text" id="class" title="" value=""  /><script>$(function(){$("#class").linkage_style_2({
                data:linkage_4,
                field:'class#class_two',
                html_attr:''
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>有效时间</th>
        <td><input type="text" name="effective_time"  id="effective_time" validate={"required":true,"digits":true,"min":30,"regexp":/\d+/,"messages":"请输入数字"}  value="" /><span></span></td>
    </tr>
        <tr>
        <th>职位摘要</th>
        <td><textarea name = "seo_desc"   validate={"maxlength":80}  style=" width:400px; height:80px;"  ></textarea><span></span></td>
    </tr>
        <tr>
        <th>职位描述</th>
        <td><script type="text/javascript">HD_UEDITOR_ROOT="http://127.0.0.1//hdjob/hdphp/org/ueditor/";</script><script type="text/javascript" src="http://127.0.0.1//hdjob/hdphp/org/ueditor/editor_config.js"></script>
		<script type="text/javascript" src="http://127.0.0.1//hdjob/hdphp/org/ueditor/editor_all.js"></script>
		<link rel="stylesheet" href="http://127.0.0.1//hdjob/hdphp/org/ueditor/themes/default/ueditor.css"><script type="text/plain" name="job_desc" id="job_desc" style=" width:500px">不告诉你</script><script type="text/javascript" >var editorOption = {
                         toolbars:[['Undo', 'Redo','Bold','Italic','Underline','JustifyLeft', 'JustifyCenter', 'JustifyRight','InsertOrderedList','InsertUnorderedList','FormatMatch','Link','Horizontal']],
                         imageUrl:"http://127.0.0.1/hdjob/index.php/index/company/ueditorupload",
                         imagePath:"",
                         fileUrl:"http://127.0.0.1/hdjob/index.php/index/company/ueditorupload",
                         filePath:"",
                         maximumWords:100,
                         minFrameHeight:200
                    };var editor = new baidu.editor.ui.Editor(editorOption);
                    editor.render("job_desc")</script><span></span></td>
    </tr>
        <tr>
        <th>性别要求</th>
        <td><label><input type="radio" name="sex"  value="0" checked="checked" />不限</label><label><input type="radio" name="sex"  value="1"  />男</label><label><input type="radio" name="sex"  value="2"  />女</label><span></span></td>
    </tr>
        <tr>
        <th>学历要求</th>
        <td><select style="margin-right:3px;" id="degree" name="degree" ></select><script>$(function(){$("#degree").linkage_style_1({
                data:linkage_18,
                field:'degree',
                html_attr:''
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>职位性质</th>
        <td><label><input type="radio" name="jobs_property"  value="0" validate={"required":true}  checked="checked" />实习</label><label><input type="radio" name="jobs_property"  value="1" validate={"required":true}   />兼职</label><label><input type="radio" name="jobs_property"  value="2" validate={"required":true}   />全职</label><span></span></td>
    </tr>
        <tr>
        <th>应届生应聘</th>
        <td><label><input type="radio" name="graduates"  value="1" checked="checked" />允许</label><label><input type="radio" name="graduates"  value="0"  />不允许</label><span></span></td>
    </tr>
        <tr>
        <th>职位月薪</th>
        <td><select name="salary"  ><option value="">请选择</option><option value="1000" >1000元/月以下</option><option value="100002000" >1000-2000元/月</option><option value="200104000" >2001-4000元/月</option><option value="400106000" >4001-6000元/月</option><option value="600108000" >6001-8000元/月</option><option value="800110000" >8001-10000元/月</option><option value="1000115000" >10001-15000元/月</option><option value="1500025000" >15000-25000元/月</option><option value="2500000000" >25000元/月以上</option></select><span></span></td>
    </tr>
        <tr>
        <th>工作经验</th>
        <td><select name="work_exp"   validate={"required":true} ><option value="">请选择</option><option value="0" selected="selected">不限</option><option value="1" >无经验</option><option value="2" >1年以下</option><option value="3" >1-3年</option><option value="4" >3-5年</option><option value="5" >5-10年</option><option value="6" >10年以上</option></select><span></span></td>
    </tr>
        <tr>
        <th>发布日期</th>
        <td><label><input type="radio" name="issue_type"  value="1" checked="checked" />立即发布</label><label><input type="radio" name="issue_type"  value="2"  />定时发布</label><span></span></td>
    </tr>
        <tr>
        <th></th>
        <td><input type="text" name="start_time" onfocus="WdatePicker({minDate:'%yyyy-%MM-%dd %HH:%mm:%ss',alwaysUseStartDate:true,dateFmt:'yyyy-MM-dd HH:mm:ss',autoPickDate:true,vel:'start_time'})" id="start_time" style="display:none" value="" /><span></span></td>
    </tr>
        <tr>
        <th>招聘人数</th>
        <td><input type="text" name="recruit_num"   validate={"required":true,"digits":true}  value="0" /><span>0表示若干人</span></td>
    </tr>
        <tr>
        <th>工作地点</th>
        <td><select style="margin-right:3px;" id="address" name="address" class="input-medium" validate={"required":true} ></select><script>$(function(){$("#address").linkage_style_1({
                data:city,
                field:'address#city#town',
                html_attr:'class="input-medium" validate={"required":true} '
                })});</script><span></span></td>
    </tr>
        <tr>
        <th>公司名称</th>
        <td><input type="text" name="company_name"   value="" /><span></span></td>
    </tr>
        <tr>
        <th>公司性质</th>
        <td><select name="company_property"   validate={"min":1} ><option value="">请选择</option><option value="1" >国企</option><option value="2" >外商独资</option><option value="3" >代表处</option><option value="4" >合资</option><option value="5" >民营</option><option value="6" >股份制企业</option><option value="7" >上市公司</option><option value="8" >国家机关</option><option value="9" >事业单位</option><option value="10" >其它</option></select><span></span></td>
    </tr>
        <tr>
        <th>公司规模</th>
        <td><select name="company_scope"   validate={"required":true,"minlength":1} ><option value="">请选择</option><option value="1" >20人以下</option><option value="2" >20-99人</option><option value="3" >100-499人</option><option value="4" >500-999人</option><option value="5" >1000-9999人</option><option value="6" >10000人以上</option></select><span></span></td>
    </tr>
        <tr>
        <th>公司主页</th>
        <td><input type="text" name="company_index"   validate={"url":true}  value="http://" /><span></span></td>
    </tr>
        <tr>
        <th>公司介绍</th>
        <td><textarea name = "company_desc"   ></textarea><span></span></td>
    </tr>
        <tr>
        <th>联系人</th>
        <td><input type="text" name="contact"   value="" /><span></span></td>
    </tr>
        <tr>
        <th>联系电话</th>
        <td><input type="text" name="phone"   value="" /><span></span></td>
    </tr>
        <tr>
        <th>接收邮箱</th>
        <td><input type="text" name="rece_mail"   validate={"email":true}  value="" /><span></span></td>
    </tr>
    </table>