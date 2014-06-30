<?php return array (
  'title' => 
  array (
    'title' => '标题',
    'field_tips' => '',
    'add_html' => '<select name="title"   validate={"required":true,"maxlength":30} ><option value="">请选择</option><option value="兴趣爱好" >兴趣爱好</option><option value="获得荣誉" >获得荣誉</option><option value="专业组织" >专业组织</option><option value="著作/论文" >著作/论文</option><option value="专利" >专利</option><option value="宗教信仰" >宗教信仰</option><option value="特长职业目标" >特长职业目标</option><option value="特殊技能" >特殊技能</option><option value="社会活动" >社会活动</option><option value="荣誉" >荣誉</option><option value="推荐信" >推荐信</option><option value="其他" >其他</option></select>',
    'edit_html' => '<select name="title"   validate={"required":true,"maxlength":30} ><option value="">请选择</option><option value="兴趣爱好" <?php if($title=="兴趣爱好"):?>selected<?php endif;?>>兴趣爱好</option><option value="获得荣誉" <?php if($title=="获得荣誉"):?>selected<?php endif;?>>获得荣誉</option><option value="专业组织" <?php if($title=="专业组织"):?>selected<?php endif;?>>专业组织</option><option value="著作/论文" <?php if($title=="著作/论文"):?>selected<?php endif;?>>著作/论文</option><option value="专利" <?php if($title=="专利"):?>selected<?php endif;?>>专利</option><option value="宗教信仰" <?php if($title=="宗教信仰"):?>selected<?php endif;?>>宗教信仰</option><option value="特长职业目标" <?php if($title=="特长职业目标"):?>selected<?php endif;?>>特长职业目标</option><option value="特殊技能" <?php if($title=="特殊技能"):?>selected<?php endif;?>>特殊技能</option><option value="社会活动" <?php if($title=="社会活动"):?>selected<?php endif;?>>社会活动</option><option value="荣誉" <?php if($title=="荣誉"):?>selected<?php endif;?>>荣誉</option><option value="推荐信" <?php if($title=="推荐信"):?>selected<?php endif;?>>推荐信</option><option value="其他" <?php if($title=="其他"):?>selected<?php endif;?>>其他</option></select>',
  ),
  'content' => 
  array (
    'title' => '内容',
    'field_tips' => '',
    'add_html' => '<textarea name = "content"   validate={"maxlength":300}  ></textarea>',
    'edit_html' => '<textarea name = "content"   validate={"maxlength":300}  ><?php echo $content;?></textarea>',
  ),
);