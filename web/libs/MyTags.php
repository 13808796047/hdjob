<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-07-02
 * Describe   : 自定义标签
 */

class MyTags {

    public $tag = array(
        'js' => array('block' => 0),
        'css' => array('block' => 0),
        'linkage' => array('block' => 1, 'level' => 3),
        'editor' => array('block' => 0),
        'getLinkage' => array('block' => 0, 'level' => 3),
        'hd_field' => array('block' => 1),
        'new_recruit' => array('block' => 1),
        'recruit_list' => array('block' => 1),
        'spread' => array('block' => 1),
        'resume_has_downloaded'=>array('block' => 0),//企业是否下载简历
        'company'=>array('block'=>1,'level'=>3),//是否企业用户
        'logged_in'=>array('block'=>1,'level'=>3),//是否登录
        'hd_keyword'=>array('block'=>1),
        'hd_nav'=>array('block'=>1),
        'new_resume'=>array('block'=>1),
        'hd_ads'=>array('block'=>1),
        'hd_recruit'=>array('block'=>1),
        'hd_link'=>array('block'=>1),
        'open_city'=>array('block'=>1),
        'arc_list'=>array('block'=>1),
        'hd_channel'=>array('block'=>1),
        'hd_page'=>array('block'=>1),
        'hd_page_seo'=>array('block'=>0),
        'hd_seo'=>array('block'=>0),
        'header' => array('block'=>0),
    );

    public function _css($attr, $content) {
        $file = trim($attr['file']);
        switch ($file) {
            case 'alice':
                return '<link type="text/css" rel="stylesheet" href="' . __ROOT__ . '/public/css/base.css"/>';
            case 'bootstrap':
                return '<link type="text/css" rel="stylesheet" href="' . __ROOT__ . '/public/css/bootstrap/bootstrap.min.css"/>';
            case 'jqueryUI.bootstrap':
                return '<link type="text/css" rel="stylesheet" href="' . __ROOT__ . '/public/css/jqueryUI.bootstrap/jquery-ui-1.8.16.custom.css"/>';
        }
        return '<link type="text/css" rel="stylesheet" href="' . $file . '"/>';
    }

    public function _js($attr, $content) {
        $file = buildAttrVar(trim($attr['file']));
        switch ($file) {
            case 'bootstrap':
                return '<script type="text/javascript" src="' . __ROOT__ . '/public/js/bootstrap/bootstrap.min.js"></script>';
            case 'jquery':
                return '<script type="text/javascript" src="' . __ROOT__ . '/public/js/jquery-1.7.2.min.js"></script>';
            case 'jqueryUI':
                return '<script type="text/javascript" src="' . __ROOT__ . '/public/js/jquery-ui-1.8.21.custom.min.js"></script>';
            case 'jqueryUI.dialog':
                return '<script type="text/javascript" src="' . __ROOT__ . '/public/js/jqueryUI/jquery-ui-1.8.22.dialog.min.js"></script>';
            case 'jquery.validate':
                return '<script type="text/javascript" src="' . __ROOT__ . '/public/js/jqueryValidate/jquery.validate.min.js"></script>';
            case 'datepicker':
                return '<script type="text/javascript" src="' . __ROOT__ . '/public/js/My97DatePicker/WdatePicker.js"></script>';
            case 'jquery.treeview':
                return '<link type="text/css" rel="stylesheet" href="' . __ROOT__ . '/public/js/treeview/jquery.treeview.css"/><script type="text/javascript" src="' . __ROOT__ . '/public/js/treeview/jquery.treeview.js"></script>';
                break;
            case 'dialog':
                $str = '';
                $str.= '<script type="text/javascript" src="' . __ROOT__ . '/public/js/jquery-1.7.2.min.js"></script>';
                $str.= "<script type='text/javascript' src='" . __HDPHP__ . "/org/hdjs/dialog.js'></script>\n";
                $str.="<link  rel='stylesheet' type='text/css' href='" . __HDPHP__ . "/org/hdjs/css/dialog.css'/>";
                return $str;
        }
        return '<script type="text/javascript" src="' . $file . '"></script>';
    }

    public function _linkage($attr, $content) {
        //处理标签参数
        if (!isset($attr['field'])) {
            error('linkage标签必须设置field属性。');
        }
        $field_array = explode('#', $attr['field']); //表单的依次name值
        $attr['name'] = trim($field_array[0]); //表单名称
        $defaults = isset($attr['defaults']) ? ",defaults:'" . buildAttrVar($attr['defaults']) . "'" : ''; //确保属性中有变量也解析
        $attr['data'] = isset($attr['data']) ? $attr['data'] : error('linkage标签必须设置data属性，查看<a href="###">linkage标签</a>使用方法。'); //联动数据分类ID
        $attr['style'] = isset($attr['style']) ? $attr['style'] : 1; //联动风格
        $attr['attr'] = isset($attr['attr']) ? $attr['attr'] : ''; //附加html属性
        $attr['tips']=isset($attr['tips']) ? ',tips:'.'"'.$attr['tips'].'"' : ''; //附加html属性
        $checkbox = isset($attr['checkbox']) && $attr['checkbox']=='true' ? ',checkbox:true': ''; //是否可以多选
        $max = isset($attr['max']) ? ',max:' . intval($attr['max']) : ''; //最多选择几项
        $data="linkage_{$attr['data']}";
        if($attr['data']=='city'){
            $data='city';
        }
        $str = '';
        $style='linkage_style_'.$attr['style'];
        //生成HTML表单
        if ($attr['style'] == 1) {
            $str.='<select style="margin-right:3px;" id="' . $attr['name'] . '" name="' . $attr['name'] . '" ' . $attr['attr'] . '></select>';
        } else {
            $str.='<input type="text" id="' . $attr['name'] . '" title="" value="" ' . $attr['attr'] . ' />';
        }
        $str.='<script>$(function(){$("#' . $attr['name'] . "\").$style({
                data:$data,
                field:'{$attr['field']}',
                html_attr:'{$attr['attr']}'" . $defaults .$attr['tips']. $checkbox . $max . '
                })});</script>';
        return $str;
    }
    /**
     * 单网页
     * @param attr['field'] 取出的字段
     * @param attr['pid'] 父级栏目
     */
    public function _hd_page($attr,$content)
    {
        $field='title,href,id';
        if(isset($attr['field'])){
            $field.=','.$attr['field'];
        }
        $pid='pid!=0';
        if(isset($attr['pid'])){
            $pid='pid ='.$attr['pid'];
        }
        $limit='';
        if(isset($attr['nums'])){
            $limit=$attr['nums'];
        }
        $str="<?php \$db=M('channel');\$pages=\$db->field('".$field."')->where('type=2 && ".$pid."')->limit(".$limit.")->findall();?>";
        $str.='<?php if(is_array($pages)):?>';
        $str.='<?php foreach($pages as $page):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }

    //模型字段
    public function _hd_field($attr, $content) {
        if(!isset($attr['model'])){
            error('hd_field标签必须设置model属性。即模型表ID，如不清楚使用方式。请查看系统部署手册');
        }
        $model = intval($attr['model']);
        $type = 'add'; //edit 编辑or发布
        if (isset($attr['type'])) {
            $type = $attr['type'];
        }
        $db = M('model');
        $mdata = $db->find($model);
        $upload = '';
        $validate = '';
        if ($mdata['upload']) {
            $upload = 'enctype="multipart/form-data"';
        }
        if ($mdata['validate']) {
            $validate = 'validate="true"';
        }
        $tpl = '{include file="' . PATH_ROOT . '/caches/model/model_' . $model . '_' . $type . '.html" /}';
        if(!file_exists(PATH_ROOT.'/caches/model/model_'.$model.'_'.$type.'.html')){
            $field=new field($model);
            $field->build_field();
            header('Location:'.__URL__);
        }
        /*$tpl = '<?php include \'./caches/model/model_' . $model . '_' . $type . '.html\';?>';*/
        $str = '<form ' . $validate . ' method="post" action="' . __METH__ . '" ' . $upload . '>';
        if ($type == 'edit') {
            $str = '<?php if(is_array($field)){extract($field);}?><form ' . $validate . ' method="post" action="' . __METH__ . '" ' . $upload . ' />';
        }
        $str.=$tpl;
        $str.=$content;
        $str .= '</form>';
        return $str;
    }
    
    //招聘信息attr: order排序方式，limit数目
    function _hd_recruit($attr, $content){
        $order=isset($attr['order']) ? $attr['order'] : 'views DESC,created DESC';
        $limit=isset($attr['nums']) ? $attr['nums'] : 30;
        $where=isset($attr['where']) ? $attr['where'].".' AND state=1 AND verify=1 AND expiration_time > '.time()" : "'state=1  AND verify=1 AND expiration_time > '.time()";
        if(isset($attr['city']) && !empty($attr['city'])){
            $where.=".' AND city='.".$attr['city'];
        }
        $field='recruit_id,recruit_name,company_name,uid';
        if(isset($attr['field'])){
            $field.=','.rtrim($attr['field'],',');
        }
        $str="<?php \$db=M('recruit');
        \$recruits=\$db->field('".$field."')->where(".$where.")
        ->order('".$order."')->limit(".$limit.")->findall();?>";
        $str.='<?php if(is_array($recruits)):?>';
        $str.='<?php foreach($recruits as $recruit):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }

    function _spread($attr, $content) {
        $str = '<?php $db = M("spread");';
        $str.='$spread_cate=$db->field("cate_id,color")->where("recruit_id=".' . $attr['id'] . ')->findall();?>';
        $str.=$content;
        return $str;
    }

     //最新简历,attr : flag="avatar"
    function _new_resume($attr, $content){
        $field='resume.resume_id,resume_name,created,avatar,views';
        $cond='';
        if(isset($attr['flag']) && $attr['flag']='avatar'){
            $cond='avatar !=""';
        }
        if(isset($attr['field'])){
            $field.=','.trim($attr['field'],',');
        }
        $convert='';
        $views='';
        $limit='';
        if(isset($attr['nums'])){
            $limit="->limit({$attr['nums']})";
        }
        if(isset($attr['join'])){
            $join=$attr['join'];
            $views="\$db->view=array('".$attr['join']."'=>array('type'=>'left','on'=>'resume.resume_id=".$attr['join'].".resume_id'));";
            $convert='<?php $data=new data("'.$join.'");$resume=$data->convert($resume);?>';
        }
        $str="<?php \$db=V('resume');".$views."\$resumes=\$db->field('".$field."')->where('".$cond."')->order('created DESC')".$limit."->findall();?>";
        $str.='<?php if(is_array($resumes)):?>';
        $str.='<?php foreach($resumes as $resume):?>';
        $str.=$convert;
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }

    //最新招聘
    public function _new_recruit($attr, $content) {
        $cond = isset($attr['cond']) ? $attr['cond'] : "''";
        $nums = isset($attr['nums']) ? ',' . $attr['nums'] : ",5";
        $order = isset($attr['order']) ? ",'{$attr['order']}'" : ",'start_time desc'";
        $field = isset($attr['field']) ? ",'{$attr['field']}'" : ",'recruit_id,recruit_name,recruit_num,start_time,expiration_time,effective_time,verify'";
        $str = '<?php
            $db=K(\'company\');
            $lists=$db->newRecruit(' . $cond . $nums . $order . $field . ');
            ?>';
        $str.='<?php if(is_array($lists)):?>';
        $str.='<?php foreach($lists as $list):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }
    /**
     * 栏目标签
     * @param attr['pid'] 取某个栏目的子栏目
     * @param attr['type'] 1,分类栏目 2 单网页
     * @param attr['nums'] 数量
     */
    public function _hd_channel($attr,$content)
    {
        $field='id,title,pinyin,href';
        if(isset($attr['field'])){
            $field.=','.$attr['field'];
        }
        $pid=0;
        $type=1;
        $nums=10;
        if(isset($attr['nums'])){
            $nums= $attr['nums'];
        }
        if(isset($attr['pid'])){
            $pid= $attr['pid'];
        }
        if(isset($attr['type'])){
            $pid= $attr['type'];
        }
        $cond='pid='.$pid.' AND state=1 AND type='.$type;
        $str="<?php \$db=M('channel');
        \$channels=\$db->cache(86400)->field('".$field."')->where('".$cond."')->order('sort')->limit($nums)->findall();?>";
        $str.='<?php if(is_array($channels)):?>';
        $str.='<?php foreach($channels as $channel):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }
    /**
     * 文章标签
     * @param attr['field']  需要的字段
     * @param attr['cid']    栏目ID
     * @param attr['page']   是否需要分页，true 可以使用{$page}调出页码
     */
    public function _arc_list($attr, $content)
    {
        $field='title,id,created,updated,href';
        if(isset($attr['field'])){
            $field.=','.$attr['field'];
        }
        $cond="'state=1'";
        if(isset($attr['cid'])){
            if(strpos($attr['cid'],'$')===FALSE){
                $cond="'state=1 AND cid={$attr['cid']}'";
            }else{
                $cond="'state=1 AND cid='.".$attr['cid'];
            }
        }
        $nums=5;
        if(isset($attr['nums'])){
            $nums=$attr['nums'];
        }
        if(isset($attr['page']) && $attr['page']=='true'){
            $limit='';
            $page_nums=10;
            if(isset($attr['nums'])){
                $page_nums=$attr['nums'];
            }
            $str_page="\$nums=\$db->where($cond)->count();
            \$page_c=new page(\$nums,".$page_nums.");\$page=\$page_c->show();";
            $page_limit="\$page_c->limit()";
        }else{
            $page_limit=$str_page='';
            $limit="->limit($nums)";
        }
        $str="<?php \$db=M('article');".$str_page."
            \$arc_list=\$db->field('".$field."')->where($cond)".$limit."->order('updated desc')->findall(".$page_limit.");?>";
        $str.='<?php if(is_array($arc_list)):?>';
        $str.='<?php foreach($arc_list as $arc):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }

    //关键字
    function _hd_keyword($attr, $content){
        $limit=8;
        if(isset($attr['nums'])){
           $limit=$attr['nums'];
        }
        $str="<?php \$db=M('keywords');\$keywords=\$db->field('keyword,red')->order('nums DESC')->limit(".$limit.")->findall();?>";
        $str.='<?php if(is_array($keywords)):?>';
        $str.='<?php foreach ($keywords as $keyword):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }

    //广告
    function _hd_ads($attr, $content){
        $limit = isset($attr['nums']) ? $attr['nums'] : 3;
        $where='';
        if(!isset($attr['cate'])){
            error('hd_ads标签必须设置cate属性，即广告位置。');
        }elseif(preg_match('/^[a-z]+$/i', $attr['cate'])){
            $db=M('ads_cate');
            $cate=$db->field('id')->where("tname='{$attr['cate']}'")->find();
            $attr['cate']=$cate['id'];
        }
        if(isset($attr['city'])){
            $where.=' AND city=".'.$attr['city'].'."';
        }

        $str="<?php \$db = M('ads');";
        $str.="\$adss =\$db->field('href,text,path,color,width,height,uid')
        ->where(\" state=1 AND cate =" . $attr['cate'].$where." AND endtime >\".time())
        ->limit(".$limit.")->findall();?>";
        $str.='<?php if(is_array($adss)):?>';
        $str.='<?php foreach ($adss as $ads):?>';
        $str.=$content;
        $str.='<?php endforeach;?>';
        $str.='<?php endif;?>';
        return $str;
    }
    function _open_city($attr, $content){
        $limit=isset($attr['nums']) ? $attr['nums'] : 15;
        $cache=isset($attr['cache']) ? $attr['cache'] : 86400;
        $str="<?php \$db=M('city');\$citys=\$db->cache($cache)->field('name,pinyin')->where('is_open=1')->limit(".$limit.")->findall();?><?php if (is_array(\$citys)):?><?php foreach (\$citys as \$city):?>";
        $str.=$content;
        $str.="<?php endforeach;endif;?>";
        return $str;
    }

    function _hd_link($attr, $content){
        $limit=8;
        if(isset($attr['nums'])){
           $limit=$attr['nums'];
        }
        $cate='';
        if(isset($attr['cate']) && (empty($attr['cate']))){
           $cate=' and cate_id="'.$attr['cate'].'"';
        }
        $cache=isset($attr['cache']) ? $attr['cache'] : 86400;
        $str="<?php \$db=M('link');\$links=\$db->cache($cache)->field('web_name,href,logo')->where('state=1 $cate')->order('sort')->limit(".$limit.")->findall();?>";
        $str.='<?php if(is_array($links)):?>';
        $str.='<?php foreach ($links as $link):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }

    //职位列表
    public function _recruit_list($attr, $content) {
        $condition = isset($attr['condition']) ? $attr['condition'] : "''";
        $_GET['nums'] = isset($_GET['nums']) ? $_GET['nums'] : 10;
        $nums = isset($attr['nums']) ? ',' . $attr['nums'] : ',$_GET["nums"]';
        $order = isset($attr['order']) ? ",'{$attr['order']}'" : ",'start_time desc'";
        $field = isset($attr['field']) ? ",'{$attr['field']}'" : ",'recruit_id,recruit_name,recruit_num,start_time,expiration_time,effective_time'";
        $str = '<?php
            $db=K(\'company\');
            $lists=$db->recruitList(' . $condition . $nums . $order . $field . ');
                $page=$lists["page"];
            ?>';
        $str.='<?php if(is_array($lists["data"])):?>';
        $str.='<?php foreach($lists["data"] as $list):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }


    //简历是否被企业下载
    function _resume_has_downloaded($attr, $content){
        $resume_id="\$_GET['id']";
        if(isset($attr['id'])){
            $resume_id=$attr['id'];//如果有设置简历ID
        }
        $str="<?php \$db=M('resume_download');\$downloaded=\$db->where('resume_id='.".$resume_id.".' AND company_id='.\$_SESSION['uid'])->count();?>";
        return $str;
    }

    //是否为企业用户
    function _company($attr, $content){
        $str ="<?php if(in_array(3,\$_SESSION['role']['rid'])){?>";
        $str.=$content;
        $str.='<?php } ?>';
        return $str;
    }

    //是否登录
    function _logged_in($attr, $content){
        $str="<?php if(isset(\$_SESSION['logged_in']) && \$_SESSION['logged_in']){?>";
        $str.=$content;
        $str.='<?php } ?>';
        return $str;
    }

    //导航
    function _hd_nav($attr, $content){
        $limit=8;
        if(isset($attr['nums'])){
           $limit=$attr['nums'];
        }
        $cache=isset($attr['cache']) ? $attr['cache'] : 86400;
        $str="<?php \$db=M('nav');\$navs=\$db->cache($cache)->field('href,title,target')->where('state=1')->order('sort ASC')->limit($limit)->findall();?>";
        $str.='<?php if(is_array($navs)):?>';
        $str.='<?php foreach ($navs as $nav):?>';
        $str.=$content;
        $str.='<?php endforeach;endif;?>';
        return $str;
    }


    //调用默认seo设置
    public function _hd_seo($attr, $content)
    {
        $str='<meta name="keywords" content="'.C('KEYWORD').'" />
<meta name="description" content="'.C('DESC').'" />';
        return $str;
    }
    //页面seo设置
    function _hd_page_seo($attr, $content){
        if(!isset($attr['cate'])){
            error('hd_page_seo标签必须设置cate属性，即导航英文名。');
        }
        $db=M('nav');
        $seos=$db->field('seo_keywords,seo_desc')
        ->where('state=1 AND mark="'.$attr['cate'].'"')->find();
        $str='<meta name="keywords" content="'.$seos['seo_keywords'].'" />
<meta name="description" content="'.$seos['seo_desc'].'" />';
        return $str;
    }

    public function _editor($attr, $content) {
        $type = C("EDITOR_TYPE");
        $content = isset($attr['content']) ? (strpos($attr['content'], "$") !== FALSE ? '<?php echo htmlspecialchars_decode(' . $attr['content'] . ');?>' : $attr['content']) : ''; //默认内容
        $maximumWords = isset($attr['max']) ? $attr['max'] : C("EDITOR_MAX_STR"); //允许输入的最大字数
        $width = isset($attr['width']) ? 'style=" width:' . intval($attr['width']) . 'px"' : 'style="width:' . c("EDITOR_WIDTH") . '"';
        $height = isset($attr['height']) ? intval($attr['height']) : intval(C("EDITOR_HEIGHT"));
        $filenum = C("EDITOR_FILE_NUM"); //上传文件数量
        $filesize = intval(C("EDITOR_FILE_SIZE")); //上传文件大小
        if (!isset($attr['name'])) {
            error(L("hdbasetag__editor"), false);
        }
        $name = $attr['name'];
        $str = '';
        switch ($type) {
            case 1:
                $toole = '';
                if (isset($attr['style'])) {//精简或全部toole
                    switch ($attr['style']) {
                        case 1:
                            $toole = "toolbars:[['Undo', 'Redo','Bold','Italic','Underline','JustifyLeft', 'JustifyCenter', 'JustifyRight','InsertOrderedList','InsertUnorderedList','FormatMatch','Link','Horizontal']],";
                            break;
                        case 2:
                            $toole = "";
                            break;
                    }
                }
                $path ='__HDPHP__/org/ueditor/';
                $str.='<script type="text/javascript">HD_UEDITOR_ROOT="' . $path . '";</script>';
                $str.='<script type="text/javascript" src="' . $path . 'editor_config.js"></script>
		<script type="text/javascript" src="' . $path . 'editor_all.js"></script>
		<link rel="stylesheet" href="' . $path . 'themes/default/ueditor.css">';
                $str.='<script type="text/plain" name="' . $name . '" id="' . $attr['id'] . '" ' . $width . '>' .
                        $content . '</script>';
                $str.='<script type="text/javascript" >';
                $str.= 'var editorOption = {
                         ' . $toole . '
                         imageUrl:"__CONTROL__/ueditorupload",
                         imagePath:"",
                         fileUrl:"__CONTROL__/ueditorupload",
                         filePath:"",
                         maximumWords:' . $maximumWords . ',
                         minFrameHeight:' . $height . '
                    };';
                $str.='var editor = new baidu.editor.ui.Editor(editorOption);
                    editor.render("' . $attr['id'] . '")';
                $str.='</script>';
                return $str;
        }
    }
   

}