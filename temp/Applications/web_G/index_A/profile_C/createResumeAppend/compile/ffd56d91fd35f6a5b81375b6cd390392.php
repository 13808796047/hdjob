<?php if(!defined("PATH_HD"))exit;?>
<table class="table-form">
        <tr>
        <th>标题</th>
        <td><select name="title"   validate={"required":true,"maxlength":30} ><option value="">请选择</option><option value="兴趣爱好" >兴趣爱好</option><option value="获得荣誉" >获得荣誉</option><option value="专业组织" >专业组织</option><option value="著作/论文" >著作/论文</option><option value="专利" >专利</option><option value="宗教信仰" >宗教信仰</option><option value="特长职业目标" >特长职业目标</option><option value="特殊技能" >特殊技能</option><option value="社会活动" >社会活动</option><option value="荣誉" >荣誉</option><option value="推荐信" >推荐信</option><option value="其他" >其他</option></select><span></span></td>
    </tr>
        <tr>
        <th>内容</th>
        <td><textarea name = "content"   validate={"maxlength":300}  ></textarea><span></span></td>
    </tr>
    </table>