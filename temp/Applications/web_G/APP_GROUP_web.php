<?php  if(!defined('PATH_HD')){exit;}
/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-9-21
 * Describe   : 数据库备份
 */
class DB_backup{
    private $table;//当前操作表名
    private $limit;//每次查询数量
    private $path;//文件目录
    private $db;//数据库链接
    private $Copyright;//版权信息
    public $error;
    public $filesize;//每个文件的大小,默认2M

    function __construct($filesize=2048000){
        $this->filesize=$filesize;
        $this->path=PATH_ROOT.'/caches/db/'.$_GET['folder'];
        $this->limit=isset($_GET['limit'])?$_GET['limit']:500;//默认每次查询500条
    }
    function backup($table){
        $tables = include $this->path.'/backup_tables.php';
        if(empty($tables)){//没有要备份的表
            $this->error="要备份的数据表为空！";
            return FALSE;
        }
        if(!isset($tables[$table])){
            return TRUE;//'操作完成'
        }
        $this->table=$tables[$table];//需要操作的表
        $this->db = M($this->table,TRUE);
        $nums=$this->db->count()+1;

        $this->Copyright="-- 该程序通过HDPHP框架构建\n-- Copyright :后盾网-张博\n-- version 2012.09\n-- http://www.houdunwang.com\n-- 主机: ".__WEB__."\n-- 备份日期: ".date('Y-m-d H:i:s')."\n-- 服务器版本: ".$this->db->db->link->server_info."\n-- PHP 版本: ".phpversion()."\n\n";

        $page=new page($nums,$this->limit);

        $wfile=$_GET['file'];//分卷文件标识
        $file_plus='';
        $file_path=$this->path.'/sql_'.date('Ymd').'_'.$wfile.'.sql';
        
        $handle=fopen($file_path, 'ab');
        if(filesize($file_path) > $this->filesize){
            $wfile+=1;
            $file_plus='/file/'.$wfile;
        }

        if($table==0 && $_GET['page']==1){
            //fwrite($handle, $this->Copyright);//写入版权信息
        }
        if($_GET['page']==1){//写入建表语句
            fwrite($handle, $this->getCreateTableSyntax());
        }
        $data=$this->db->findall($page->limit());
        if($_GET['page']<=$page->total_page && $data){
            // fwrite($handle, $this->db->get_last_sql()."\n");//写入SQL查询语句
            fwrite($handle, $this->getInsertSyntax($data));//写入插入数据语句
            go(url_remove_param('page').'/page/'.($_GET['page']+1).$file_plus);
            //fclose($handle);
        }else{//开始备份下一张表
            $_GET['page']=1;//将page还原为1
            go(url_remove_param('table').'/table/'.($_GET['table']+1));
        }
    }
    //创建表结构语句
    function getCreateTableSyntax() {
        $result=$this->db->query('SHOW CREATE TABLE '.$this->table);
        return str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $result[0]['Create Table'].";\n\n---------houdunwang-------------\n\n");
    }

    //创建插入数据语句
    function getInsertSyntax($data){
        $sql="INSERT INTO `".$this->table."` (".$this->db->db->opt['field'].") VALUES";
        foreach ($data as $value) {
            $sql.='(';
                foreach($value as $v){
                    $sql.=is_numeric($v)?$v.',':"'".addslashes($v)."'".',';
                }
                $sql=rtrim($sql,',');
            $sql.="),";
        }
        $sql=rtrim($sql,',');
        $sql.=";\n\n---------houdunwang-------------\n\n";
        return $sql;
    }
}

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

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-4
 * Describe   : 权限验证
 */
class auth {

    private $auth_model;
    private $_banned;
    private $_ban_reason;
    public $error;

    function __construct() {
        $this->auth_model = new authModel; //载入权限验证模型        
        $this->autologin(); //检测是否有自动登录
    }

    /**
     * 用户登录
     * @param type $login 登录POST参数
     * @param type $super_user 超级用户名
     */
    function auth_user_login($login, $password, $remember = 0, $super_user = 'hdroot') {
        $result = FALSE; //
        $login_mode_func = 'get_user_by_username';
        if (!empty($login) AND !empty($password)) {
            if (C('AUTH_USERNAME_LOGIN') && C('AUTH_EMAIL_LOGIN')) {//用户名和Email都可以登录
                $login_mode_func = 'get_login';
            } else if (C('AUTH_EMAIL_LOGIN')) {//使用EMAIL登录
                $login_mode_func = 'get_user_by_email';
            } else {//使用用户名登录
                $login_mode_func = 'get_user_by_username';
            }
            $login_mode_func_temp = $login_mode_func . '_temp';
            $query = $this->auth_model->$login_mode_func($login);
            if ($query) {
                if ($query['banned'] > 0) {
                    // 设置用户禁用
                    $this->_banned = TRUE;
                    $this->_ban_reason = $query['ban_reason'];
                } else {//匹配密码
                    if (md5_d($password) == $query['password']) {
                        $this->_auth_set_session($query);
                        if ($query['newpass']) {
                            // 清除重置密码
                            $this->auth_model->clear_newpass($query['uid']);
                        }
                        if ($remember) {
                            // 创建自动登录
                            $this->_auth_create_autologin($query['uid']);
                        }

                        // 设置上次登录ip和时间
                        $this->_set_last_ip_and_last_login($query['uid']);
                        // 清楚登录尝试
                        $this->_clear_login_attempts();
                        $result = TRUE;
                    } else {//密码错误，设置错误次数
                        $this->_increase_login_attempt();
                        $this->error = L('username_or_password_error');
                    }
                }
            } else if ($this->auth_model->$login_mode_func_temp($login)) {//查找用户是否已注册，但是未激活
                $this->error = sprintf(L('user_not_activation'), $login);
            } else {
                // 增加登录尝试
                $this->_increase_login_attempt();
                // 设置错误信息
                $this->error = L('login_username_not_exist'); //用户不存在
            }
        } else {
            $this->error = L('no_username_or_pwd');
        }
        return $result;
    }
    

    /**
     * 注册用户 
     * @param type $data 注册的信息 用户名密码神马之类的。
     */
    function register($data) {
        $result = FALSE;
        $new_user = $data;
        $new_user['password'] = md5_d($data['password']);
        $new_user['last_ip'] = ip_get_client();
        // 发送电子邮件来激活用户
        if (C('AUTH_EMAIL_ACTIVATE')) {
            // 添加激活密钥到new_user array
            $new_user['activation_key'] = token();

            // 在数据库中创建的临时用户,用户仍然未激活。
            $insert = $this->auth_model->create_temp($new_user);
        } else {
            // 创建用户
            $insert = $this->auth_model->create_user($new_user);
            // 增加用户积分/创建用户资料……
            $this->_user_activated($insert,$new_user['rid']);
        }
        if ($insert) {
            // 原始密码
            $new_user['password'] = $data['password'];

            $result = $new_user;

            // 按照配置文件中的信息发送电子邮件
            // 如果用户需要使用电子邮件激活帐户
            if (C('AUTH_EMAIL_ACTIVATE')) {
                // 创建Email
                $from = C('email_username');
                $subject = sprintf(L('auth_activate_subject'), C('WEB_NAME'));
                // 激活链接
                $new_user['activate_url'] = __WEB__ . '/index/auth/activate/username/' . $new_user['username'] . '/key/' . $new_user['activation_key'];

                // 触发事件，并获得电子邮件的内容
                $new_user['expire'] = (C('EMAIL_ACTIVATE_EXPIRE') / 3600) . '小时';
                $content = getEmailTpl('register_active', $new_user);

                // 发送激活链接到邮件
                $this->_send_email($new_user['email'], $from, $content['subject'], $content['content']);
            } else {
                // 没有开启邮箱验证注册，但是注册后会发送账户信息
                if (C('EMAIL_ACCOUNT_INFO')) {
                    $from = C('email_username');
                    $subject = sprintf(L('auth_account_subject'), C('WEB_NAME'));
                    $content = getEmailTpl('register_info', $new_user);
                    $this->_send_email($new_user['email'], $from, $content['subject'], $content['content']);
                }
            }
        }

        return $result;
    }

    private function _send_email($tomail, $toname, $title, $body) {
        $email = new mail();
        $email->send($tomail, $toname, $title, $body);
    }

    //创建用户资料成功，增加用户积分
    function _user_activated($uid,$role=4) {
        $table='user_info';
        if($role==3){//企业用户
            $table='company_info';
        }
        $db=M($table);
        $db->insert(array('uid'=>$uid));
        $point=getPointRule('newUser');
        $db->table('user_point')->insert(array('uid'=>$uid,'point'=>$point));
    }

    //修改密码
    function change_password($data){
        $result=FALSE;
        $info=$this->auth_model->get_user_by_id($_SESSION['uid']);
        if(md5_d($data['old_pwd'])==$info['password']){
            if($this->auth_model->set_user($_SESSION['uid'],array('password'=>md5_d($data['password'])))>=0){
                $result=TRUE;
            }
        }else{
            $this->error='原密码错误！';
        }
        return $result;
    }

    //激活用户
    function activate($username, $key) {
        $result = FALSE;
        if (C('AUTH_EMAIL_ACTIVATE')) {
            //删除到期的激活
            $this->auth_model->delExpireActivate();
        }
        // 激活用户
        if ($userinfo = $this->auth_model->activate_user_info($username, $key)) {
            // 创建用户。 插入user表和user_role表
            $userinfo['email_verify']=1;//同时Email已经验证通过
            if ($uid=$this->auth_model->create_user($userinfo)) {
                $this->_user_activated($uid,$userinfo['rid']);//创建用户资料，增加用户积分
                //删除临时表中的数据
                $this->auth_model->delete_temp_user($userinfo['id']);

                $result = TRUE;
            }
        }

        return $result;
    }
    //自动登录
    function autologin() {
        $result = FALSE;
        $auto = isset($_COOKIE[C('AUTH_AUTOLOGIN_COOKIE_NAME')]) ? $_COOKIE[C('AUTH_AUTOLOGIN_COOKIE_NAME')] : FALSE;
        if (!$this->is_logged_in() && $auto) {
            $auto = unserialize($auto);
            if (isset($auto['key_id']) AND $auto['key_id'] AND $auto['user_id']) {

                $query = $this->auth_model->get_key($auto['key_id'], $auto['user_id']);

                if ($query) {
                    $this->_set_last_ip_and_last_login($auto['user_id']);//设置登录IP和时间
                    $this->_auth_set_session($query);
                    $this->_auto_cookie($auto);
                    $result = TRUE;
                }
            }
        }
        return $result;
    }

    //增加登录错误次数
    private function _increase_login_attempt() {
        if (C('AUTH_COUNT_LOGIN_ATTEMPTS') AND !$this->is_max_login_attempts_exceeded()) {
            $this->auth_model->increase_attempt(ip_get_client());
        }
    }

    //是否超过最大登录尝试次数
    function is_max_login_attempts_exceeded() {
        return ($this->auth_model->check_attempts(ip_get_client())) >= C('AUTH_MAX_LOGIN_ATTEMPTS');
    }

    private function _set_last_ip_and_last_login($uid) {
        $login_log = array(
            'last_ip' => ip_get_client(),
            'last_login' => time()
        );
        $this->auth_model->set_user($uid, $login_log);
    }

    //清楚登录尝试
    private function _clear_login_attempts() {
        if (C('AUTH_COUNT_LOGIN_ATTEMPTS')) {
            $this->auth_model->clear_attempts(ip_get_client());
        }
    }

    /**
     * 创建自动登录
     * @param type $uid 
     */
    private function _auth_create_autologin($uid) {
        $result = FALSE;

        //如果用户想要记住登录
        $user = array(
            'key_id' => substr(token(), 0, 16),
            'user_id' => $uid
        );
        // 先清楚用户之前的自动登录信息
        $this->auth_model->prune_keys($user['user_id']);
        if ($this->auth_model->store_key($user['key_id'], $user['user_id'])) {
            //设置用户自动登录cookie
            $this->_auto_cookie($user);
            $result = TRUE;
        }

        return $result;
    }

    private function _auto_cookie($data) {

        $cookie = array(
            'name' => C('AUTH_AUTOLOGIN_COOKIE_NAME'),
            'value' => serialize($data),
            'expire' => C('AUTH_AUTOLOGIN_COOKIE_LIFE')
        );
        setcookie($cookie['name'], $cookie['value'], time() + $cookie['expire'], '/');
    }

    /**
     * 用户登录成功设置SESSION值
     * @param type $data 
     */
    private function _auth_set_session($data) {
        $role_data = $this->_get_role_data($data['uid']);
        $_SESSION = array(
            'uid' => $data['uid'],
            'username' => $data['username'],
            'last_ip' => $data['last_ip'],
            'last_login' => $data['last_login'],
            'logged_in' => TRUE,
            'point'=>0,
            'role' => array(
                'rid' => $role_data['role_id'],
                'rname' => $role_data['role_name'],
                'rtitle' => $role_data['role_title'],
                'parent_rid' => $role_data['parent_roles_id'],
                'parent_rname' => $role_data['parent_roles_name'],
                'parent_rtitle' => $role_data['parent_roles_title'],
            )
        );
        $_SESSION['hd_auth'] = array(
            'permission' => $role_data['permission'],
            'parent_permissions' => $role_data['parent_permissions'],
        );
    }

    private function _get_role_data($uid) {
        //初始化变量
        $data = array();
        $role_name = array();
        $role_id = array();
        $role_title = array();
        $parent_roles_id = array();
        $parent_roles_name = array();
        $parent_roles_title = array();
        $permission = array();
        $parent_permissions = array();

        $query = $this->auth_model->get_user_role_by_id($uid); //通过id获取用户角色
        if ($query) {
            foreach ($query as $key => $value) {
                $role_id[] = $value['rid'];
                $role_name[] = $value['rname'];
                $role_title[] = $value['title'];
                if ($value['pid'] > 0) {
                    $parent_roles_id[] = $value['pid'];
                    $finished = FALSE;
                    $parent_id = $value['pid'];
                    //获取所有的父级角色
                    while ($finished == FALSE) {
                        $i_query = $this->auth_model->get_role_by_rid($parent_id);
                        // 如果角色存在
                        if ($i_query) {
                            // 保存值
                            $i_role = $i_query;

                            // 如果角色没有父级角色
                            if ($i_role['pid'] == 0) {
                                // 取得最后的父级角色信息
                                $parent_roles_name[] = $i_role['rname'];
                                $parent_roles_title[] = $i_role['title'];
                                // 停止循环
                                $finished = TRUE;
                            } else {
                                // 改变parent id 开始下次循环
                                $parent_id = $i_role['pid'];
                                $parent_roles_id[] = $parent_id;
                                $parent_roles_name[] = $i_role['rname'];
                                $parent_roles_title[] = $i_role['title'];
                            }
                        } else {
                            //parent_id没有找到，删除最后的parent_roles_id
                            array_pop($parent_roles_id);
                            $finished = TRUE;
                        }
                    }
                }
            }
        }
        //获取用户的权限
        $permission = $this->auth_model->get_permissions_data($role_id);
        //获取用户父级角色权限
        if (!empty($parent_roles_id)) {
            $parent_permissions = $this->auth_model->get_permissions_data($parent_roles_id);
        }
        $data['role_name'] = $role_name;
        $data['role_id'] = $role_id;
        $data['role_title'] = $role_title;
        $data['parent_roles_id'] = $parent_roles_id;
        $data['parent_roles_name'] = $parent_roles_name;
        $data['parent_roles_title'] = $parent_roles_title;
        $data['permission'] = $permission;
        $data['parent_permissions'] = $parent_permissions;
        return $data;
    }

    /**
     * 检验验证码是否正确
     */
    function captcha_check($validate_code) {
        $result = TRUE;
        if (strtolower($validate_code) != strtolower($_SESSION['code'])) {
            $result = FALSE;
        }
        return $result;
    }

    function is_banned() {
        return $this->_banned;
    }

    function get_ban_reason() {
        return $this->_ban_reason;
    }

    /**
     * 用户是否登录 
     */
    function is_logged_in() {
        return isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : FALSE;
        //return FALSE;
    }

    /**
     * 数组中任意项是否在另外的数组中
     * @param type $needle
     * @param type $haystack 
     */
    function _array_in_array($needle, $haystack) {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        //合并允许访问的权限
        $haystack = array_unique(array_merge($haystack['permission'], $haystack['parent_permissions']));
        foreach ($needle as $value) {
            if (in_array($value, $haystack)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    //检查uri是否是不需要验证的方法
    private function _check_no_auth_uri($method) {
        $no_auth_method = C('NO_AUTH_METHOD');
        if (!is_array(C('NO_AUTH_METHOD'))) {
            $no_auth_method = array($no_auth_method);
        }

        if (in_array($method, $no_auth_method)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 检查访问权限 
     */
    function check_uri_permissions() {
        $app_name = '/' . APP . '/';
        $control = $app_name . CONTROL . '/';
        $method = $control . METHOD . '/';
        if ($this->_check_no_auth_uri($method) OR $this->_array_in_array(array('/', $method), $_SESSION['hd_auth'])) {
            return TRUE;
        } else {
            $this->error = L('no_permission');
        }
    }

}

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-29
 * Describe   : 
 */

class authModel extends Model {

    public $user;
    private $user_role;
    private $role;
    private $user_autologin;
    private $login_attempts;
    private $user_agent;
    private $user_temp;

    function __construct() {
        $this->user_agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];
        $this->user = M('user');
        $this->user_temp = M('user_temp');
        $this->role = M('role');
        $this->login_attempts = M('login_attempts');
        $this->user_autologin = M('user_autologin');
        $this->user_role = V('user_role');
        $this->user_role->view = array(
            'role' => array(//定义 user_info表规则
                'join_type' => "inner", //指定连接方式
                "field" => 'rid,rname,pid,title', //字段，email字段起别
                "on" => "user_role.rid=role.rid", //关联条件
            ),
        );
    }

    function get_role_by_rid($rid) {
        return $this->role->where(array('rid' => $rid))->find();
    }

    function get_login($login) {
        return $this->user->where("username = '$login' OR email = '$login'")->find();
    }
    function get_user_by_id($id) {
        return $this->user->where(array("uid" => $id))->find();
    }

    function get_user_by_email($email) {
        return $this->user->where(array("email" => $email))->find();
    }

    function get_user_by_username($username) {
        return $this->user->where(array("username" => $username))->find();
    }

    //user_temp
    function get_login_temp($login) {
        return $this->user_temp->where("username = '$login' OR email = '$login'")->find();
    }

    function get_user_by_email_temp($email) {
        return $this->user_temp->where(array("email" => $email))->find();
    }

    function get_user_by_username_temp($username) {
        return $this->user_temp->where(array("username" => $username))->find();
    }

    //user_temp结束
    function get_user_role_by_id($uid) {
        return $this->user_role->where("role.state=1")->where(array('uid' => $uid))->findall();
    }

    function set_user($user_id, $data) {
        return $this->user->where(array('uid' => $user_id))->update($data);
    }

    function clear_newpass($user_id) {
        $data = array(
            'newpass' => NULL,
            'newpass_key' => NULL,
            'newpass_time' => NULL
        );
        return $this->set_user($user_id, $data);
    }

    function get_permissions($roles_id) {
        $db = M('access');
        $data=$db->field('permissions')->in(array('rid'=>$roles_id))->findall();
        $permissions = array();
        if($data){
            foreach ($data as $value) {
                $permissions=array_merge($permissions,json_decode($value['permissions'],true));
            }
        }
        return $permissions;
    }

    //获取访问权限节点
    function get_permissions_data($roles_id) {
        return $this->get_permissions($roles_id);
    }

    //自动登录
    function prune_keys($uid) {
        $data = array(
            'user_id' => $uid,
            'user_agent' => substr($this->user_agent, 0, 149),
            'last_ip' => ip_get_client()
        );
        return $this->user_autologin->where($data)->delete();
    }

    function store_key($key, $user_id) {
        $user = array(
            'key_id' => md5_d($key),
            'user_id' => $user_id,
            'user_agent' => substr($this->user_agent, 0, 149),
            'last_ip' => ip_get_client(),
            'last_login' => time()
        );
        $this->user_autologin->insert($user);
        return $this->user_autologin->get_affected_rows();
    }

    function get_key($key, $user_id) {
        $db=V('user');
        $db->view=array(
            'user_autologin'=>array(
                'type'=>'inner',
                'on'=>'user_autologin.user_id=user.uid'
            )
        );
        $result =$db->field('user.uid,user.username,user.last_ip,user.last_login')
                    ->where("user.uid=$user_id AND user_autologin.key_id='" . md5_d($key) . "'")
                    ->find();
        //$result = $this->user_autologin->query($sql);
        return $result;
    }

    //尝试错误登录次数
    function increase_attempt($ip_address) {
        $data = array(
            'ip_address' => $ip_address,
            'time' => time()
        );
        $this->login_attempts->insert($data);
    }

    function check_attempts($ip) {
        $resule = $this->login_attempts->where(array('ip_address' => $ip))->findall();
        return $this->login_attempts->get_affected_rows();
    }

    function clear_attempts($ip) {
        $this->login_attempts->where(array('ip_address' => $ip))->delete();
    }

    function create_temp($data) {
        $data['created'] = time();
        return $this->user_temp->insert($data);
    }

    function create_user($data) {
        $data['created'] = time();
        $data['last_login']=time();
        $data['last_ip']=ip_get_client();
        $id = $this->user->insert($data);
        if ($id) {
            $this->user_role->insert(array('uid' => $id, 'rid' => $data['rid']));
            return $id;
        }
        return FALSE;
    }

    /**
     * 删除到期的激活账户信息 
     */
    function delExpireActivate() {
        $this->user_temp->where('created <' . time() - C('EMAIL_ACTIVATE_EXPIRE'))->del();
    }

    function activate_user_info($username, $key) {
        return $this->user_temp->where("username='$username' AND activation_key='$key'")->find();
    }

    function delete_temp_user($id) {
        $this->user_temp->del($id);
    }

}

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-8-24
 * Describe   : 查找、替换、生成一些数据
 */

class data {

    private $data_model;
    private $db_prefix; //数据库前缀
    public $fields; //模型的字段结构数组
    private $data; //原始数据

    /**
     * 
     * @param type $model 模型名
     */

    function __construct($model = 'recruit') {
        $this->data_model = K('backend/data');
        $this->db_prefix = rtrim(C('DB_PREFIX'), '_') . '_';
        $this->fields = include PATH_ROOT . '/caches/model/field/m_' . $model . '.php';
    }

    function convert($data) {
        $this->data = $data;
        foreach ($this->fields as $key => $value) {
            if (!isset($data[$key])) {
                continue;
            }
            $method = 'convert' . ucfirst($value['field_type']);
            if (method_exists($this, $method)) {
                $this->$method($key, $data[$value['field_name']]);
            }
        }
        return $this->data;
    }

    /**
     * 转换选项数据
     * @param type $field
     * @param type $value 
     */
    function convertSwitch($field, $value) {
        if (strpos($value, '#') !== FALSE) {
            $value_arr = explode('#', $value);
            $new_v = '';
            foreach ($value_arr as $v) {
                if(isset($this->fields[$field]['data'][$v])){
                    $new_v.= $this->fields[$field]['data'][$v] . '、';
                }
            }
            $this->data[$field] = rtrim($new_v, '、');
        } else {
            if(isset($this->fields[$field]['data'][$value])){
                $this->data[$field] = $this->fields[$field]['data'][$value];
            }
        }
    }

    /**
     * 取得联动分类或城市的缓存数据值
     * @param string $type city or linkage
     * @return 换成数据数组
     */
    private function _getLinkageCache($type='linkage')
    {
        $field=array('laid','title');
        if($type=='city'){
            $field=array('id','name');
        }
        $cache=array();
        $path=PATH_ROOT.'/caches/linkage';
        $file=$path.'/'.$type.'.php';
        if(file_exists($file)){
            $cache=include $file;
        }else{
            dir::create($path);
            $db = M($type);
            $linkages = $db->field(implode(',', $field))->findall();
            foreach ($linkages as $value) {
                $cache[$value[$field[0]]]=$value[$field[1]];
            }
            file_put_contents($file, '<?php if(!defined("PATH_HD")){exit;}return '.var_export($cache,TRUE).';');
        }
        return $cache;
    }

    /**
     * 转换联动数据
     * @param type $field
     * @param type $value 
     */
    function convertLinkage($field, $value) {
        $attached = json_decode($this->fields[$field]['attached'], TRUE);
        if (strpos($value, '#') !== FALSE) {
            $id = explode('#', $value);
        } else {
            $id = array($value);
        }
        //查找附属字段的
        foreach ($attached as $value) {
            if (strpos($this->data[$value], '#') !== FALSE) {
               $id = array_merge($id,explode('#', $this->data[$value]));
            } else {
                $id[] = $this->data[$value];
            }
        }
        $type='linkage';
        if($this->fields[$field]['lcgid']=='city'){//是城市数据,从城市表中取得数据转换
            $type='city';
        }
        $linkage=$this->_getLinkageCache($type);

        $cn = '';
        $delimiter = ' - ';
        foreach ($id as $value) {
            if(isset($linkage[$value])){
                $cn.=$linkage[$value] . $delimiter;
            }
        }
        $this->data[$field] = rtrim($cn, $delimiter);
    }

    //将所有的分类数据写入JS文件，形如：var city={},var linkage1={},var linkage2={}
    function writeCatesToJs() {
        C('DEBUG', 0);
        $db=M('linkage_category');
        $lcgid=$db->field('lcgid')->findall();
        $city=$db->table('city')->field('id,pid,name')->findall();
        $str='var city='.json_encode_cn(formatParentData2($city,array('id','pid','name'))).',';//城市的数据
        foreach ($lcgid as $value) {
            $condition = array(
                'lcgid' => $value['lcgid']
            );
            $result = $this->data_model->getCateLinkage($condition,'laid,title,pid');
           $str.='linkage_'.$value['lcgid'].' = '.json_encode_cn(formatParentData($result)).',';
        }
        $str=rtrim($str,',').';';
        $file_name = PATH_ROOT . '/caches/js/linkage_data.js';
        file_put_contents($file_name, $str);
    }

}

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-8-24
 * Describe   : 生成、更新模型视图。更新模型结构、缓存模型字段
 */

class field extends Control {
    private $model_info;
    private $model;
    private $model_field;
    private $html_struct=array();
    private $model_id;
    /**
     * @param int $model模型表 ID
     */
    public function __construct($model=null)
    {
        $this->model_id=$model;
        $this->model=M('model');
        $this->model_info=$this->model->find($model);
        if(!$this->model_info){
            $this->error('不存在该模型。');
        }
        $this->model_field=include PATH_ROOT.'/caches/model/field/m_'.$this->model_info['name'].'.php';
    }

    /**
     * 生成每个字段的机构。
     */
    public function build_field()
    {
        foreach ($this->model_field as $field) {
            if($field['state']){
                $this->html_struct[$field['field_name']]=array(
                    'title'=>$field['title'],
                    'field_tips'=>$field['field_tips'],
                    'add_html'=>'',
                    'edit_html'=>''
                );
                $this->build_struct($field);
            }
        }
        $data='<?php return '.var_export($this->html_struct,true).';';
        file_put_contents(PATH_ROOT.'/caches/model/struct/'.$this->model_info['name'].'.php', $data);
        $this->generate_html();
    }

    public function build_struct($data)
    {
        $data['rule']=json_decode($data['rule']);
        $tags = new MyTags;
        $rule = '';
        $validate = '';
        $width = empty($data['width']) ? '' : ' width:' . $data['width'] . 'px;';
        $height = empty($data['height']) ? '' : ' height:' . $data['height'] . 'px;';
        $style = '';
        if ($width . $height != '') {
            $style = ' style="' . $width . $height . '" ';
        }
        if (!empty($data['rule'])) {
            $validate = ' validate={';
            foreach ($data['rule'] as $rule_key => $rule_value) {
                $rule.='"' . $rule_key . '"' . ':' . $rule_value . ',';
            }
//组合错误消息
            if (trim($data['error_tips']) != '') {
                $rule.='"messages":"' . $data['error_tips'] . '"';
            }
            $validate .= trim($rule, ',') . '} ';
        }
        if (substr($data['field_type'], 0, 6) == 'switch' && $data['setting']['type'] == 'option') {
            $this->html_struct[$data['field_name']]['add_html'] = '<select name="' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $validate . '><option value="">请选择</option>';
            $this->html_struct[$data['field_name']]['edit_html'] = '<select name="' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $validate . '><option value="">请选择</option>';
        }
        if (substr($data['field_type'], 0, 5) == 'input') {
            $this->html_struct[$data['field_name']]['add_html'] = '<input type="text" name="' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . ' value="' . $data['default_val'] . '" />';
            $this->html_struct[$data['field_name']]['edit_html'] = "<input type=\"text\" name=\"" . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . $style . " value=\"<?php echo $" . $data['field_name'] . ";?>\" />";
        }
        if ($data['field_type'] == 'switch') {
            foreach ($data['data'] as $radio_v => $radio_n) {
                if ($radio_v == $data['default_val']) {
                    $checked = 'checked="checked"';
                    $selected = 'selected="selected"';
                } else {
                    $checked = '';
                    $selected = $checked;
                }
                $edit_checked = '<?php if(in_array("'.$radio_v.'",explode("#",$' . $data['field_name'] . '))):?>checked<?php endif;?>';
                $edit_selected = '<?php if($' . $data['field_name'] . '=="' . $radio_v . '"):?>selected<?php endif;?>';
                if ($data['setting']['type'] == 'radio') {
                    $this->html_struct[$data['field_name']]['add_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $checked />{$radio_n}</label>";

                    $this->html_struct[$data['field_name']]['edit_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $edit_checked />{$radio_n}</label>";
                }else if($data['setting']['type'] == 'checkbox'){
                    $this->html_struct[$data['field_name']]['add_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}[]\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $checked />{$radio_n}</label>";
                    $this->html_struct[$data['field_name']]['edit_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}[]\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $edit_checked />{$radio_n}</label>";
                }else {//下拉列表
                    $this->html_struct[$data['field_name']]['add_html'] .= '<option value="' . $radio_v . '" ' . $selected . '>' . $radio_n . '</option>';
                    $this->html_struct[$data['field_name']]['edit_html'] .= '<option value="' . $radio_v . '" ' . $edit_selected . '>' . $radio_n . '</option>';
                }
            }
        }
        if (substr($data['field_type'], 0, 6) == 'switch' && $data['setting']['type'] == 'option') {
            $this->html_struct[$data['field_name']]['add_html'] .= '</select>';
            $this->html_struct[$data['field_name']]['edit_html'] .= '</select>';
        }
        if ($data['field_type'] == 'editor') {//编辑器
            $editor_attr = array(
                'name' => $data['field_name'],
                'id' => $data['field_name'],
                'content' => $data['default_val'],
                'width' => $data['width'],
                'height' => $data['height'],
                'style' => $data['editor_style'],
            );
            if(isset($data['rule']['maxlength'])){
                $editor_attr['max'] = $data['rule']['maxlength']; //最大输入字数
            }
            $this->html_struct[$data['field_name']]['add_html'] = $tags->_editor($editor_attr, '');
            $editor_attr['content'] = '$' . $data['field_name'];
            $this->html_struct[$data['field_name']]['edit_html'] = $tags->_editor($editor_attr, '');
        }
        if ($data['field_type'] == 'textarea') {//文本域
            $this->html_struct[$data['field_name']]['add_html'] = '<textarea name = "' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . $style . ' >' . $data['default_val'] . '</textarea>';
            $this->html_struct[$data['field_name']]['edit_html'] = '<textarea name = "' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . $style . ' ><?php echo $' . $data['field_name'] . ';?></textarea>';
        }
        if ($data['field_type'] == 'linkage') {//联动数据
            $attr = array();
            $data['attached']=json_decode($data['attached']);
            if (!empty($data['attached'])) {
                $attr['field'] = $data['field_name'] . '#';
                $attr['edit_field'] = '$' . $data['field_name'] . '#$';
                $attr['field'].=implode('#', $data['attached']);
                $attr['edit_field'].=implode('#$', $data['attached']);
            } else {
                $attr['field'] = $data['field_name'];
                $attr['edit_field'] = '$' . $data['field_name'];
            }
            $attr['edit_field'] = rtrim($attr['edit_field'], '#$');

            $attr['data'] = $data['lcgid'];
            $attr['style'] = $data['linkage_style'];
            $attr['attr'] = $data['html_attr'] . $validate;
            $attr['checkbox'] = isset($data['setting']['checkbox']) ? 'true' : 'false';
            if (!empty($data['default_val'])) {
                $attr['defaults'] = $data['default_val'];
            }
            $this->html_struct[$data['field_name']]['add_html'] = $tags->_linkage($attr, '');
            $attr['defaults'] = $attr['edit_field'];
            $this->html_struct[$data['field_name']]['edit_html'] = $tags->_linkage($attr, '');
        }
    }

    /**
     * 根据组合好的模型表单结构、生成HTML视图
     * @param int $model 模型ID
     */
    public function generate_html()
    {
        $db=M('model_field');
        $cond = array('dmid' => $this->model_id);
        $struct = include PATH_ROOT.'/caches/model/struct/'.$this->model_info['name'].'.php';
        $tpl_path = PATH_ROOT . '/caches/model/model_';
        //查找联动数据id的和风格样式
        $cond[]='lcgid > 0';
        $linkage = $db->where($cond)->count();
        $style = $db->field('linkage_style')->where('lcgid != "0" AND dmid="' . $this->model_id . '"')->group('linkage_style')->findall();
        $script='';
        if($linkage) {
            $script='<script type="text/javascript" src="__ROOT__/caches/js/linkage_data.js"></script>';
        }
        if(is_array($style)){
            foreach ($style as $value) {
                $script.='<script type="text/javascript" src="__ROOT__/public/js/linkage/linkage_style_' . $value['linkage_style'] . '.js"></script>';
            }
        }
        $fields = array();
        foreach ($struct as $k => $v) {
            $fields[$k] = $v;
            $fields[$k]['html'] = $v['add_html'];
            unset($fields[$k]['add_html']);
            unset($fields[$k]['edit_html']);
        }


        //获取前端配置文件
        $front_config=include PATH_ROOT.'/config/app.php';
        $style=$front_config['TPL_DIR'].'/'.$front_config['TPL_STYLE'];

        //更新发布模板
        $this->assign('fields', $fields);
        $field_add = $this->display($style .'/model/'. $this->model_info['issue_tpl'], NULL, 'text/html', 'utf-8', FALSE);
        file_put_contents($tpl_path . $this->model_id . '_add.html', $script . $field_add);

        $fields = array();
        foreach ($struct as $k => $v) {
            $fields[$k] = $v;
            $fields[$k]['html'] = $v['edit_html'];
            unset($fields[$k]['add_html']);
            unset($fields[$k]['edit_html']);
        }
        $this->assign('fields', $fields);
        //更新修改模板
        $field_edit= $this->display($style .'/model/'. $this->model_info['edit_tpl'], NULL, 'text/html', 'utf-8', FALSE);
        file_put_contents($tpl_path . $this->model_id . '_edit.html', $script . $field_edit);
    }
    /**
     * 过滤前台表单的一些输入。如在标题输入<script></script>登不安全代码
     * @param array $fieldValue 前台处理值
     * @param array 过滤后的数组
     */
    public function filterField($fieldValue)
    {
        if(!is_array($fieldValue)){
            $this->error('数据有误！');
        }
        foreach ($this->model_field as $field_name => $field) {
            if($field['state']){
                switch ($field['field_type']) {
                    case 'input_varchar'://单行文本框
                        $fieldValue[$field_name]=strip_tags($fieldValue[$field_name]);
                        break;
                    case 'input_char'://单行文本框
                        $fieldValue[$field_name]=strip_tags($fieldValue[$field_name]);
                        break;
                    case 'input_float':
                        $fieldValue[$field_name]=floatval($fieldValue[$field_name]);
                        break;
                    case 'input_decimal':
                        $fieldValue[$field_name]=floatval($fieldValue[$field_name]);
                        break;
                    case 'input_double':
                        $fieldValue[$field_name]=floatval($fieldValue[$field_name]);
                        break;
                    case 'linkage'://联动数据
                        if(!isset($field['setting']['checkbox'])){
                            $fieldValue[$field_name]=intval($fieldValue[$field_name]);
                        }
                        break;
                    case 'editor'://编辑器
                        $fieldValue[$field_name]=htmlspecialchars($fieldValue[$field_name]);
                        break;
                    case 'textarea'://文本域
                        $fieldValue[$field_name]=htmlspecialchars($fieldValue[$field_name]);
                        break;
                    case 'input_int'://联动数据
                        $fieldValue[$field_name]=intval($fieldValue[$field_name]);
                        break;
                    case 'switch':
                        $fieldValue[$field_name]=strip_tags($fieldValue[$field_name]);
                        break;
                    default:
                        $fieldValue[$field_name]=htmlspecialchars($fieldValue[$field_name]);
                        break;
                }
            }
        }
        return $fieldValue;
    }
}

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-29
 * Describe   : 扩展核心控制器
 */

class myControl extends Control {

    protected $auth;

    public function __construct()
    {
        $this->auth = new auth;
        if (!$this->auth->is_logged_in()) {
            $this->error(L('please_login'), 'auth/index');
        }
        if (!$this->auth->check_uri_permissions()) {
            $this->error($this->auth->error);
        }
    }

    function is_logged_in() {
        return $this->auth->is_logged_in();
    }
    /**
     * 成功,失败时做的工作
     * @param type $success $this->success的参数
     * @param type $error 
     */
    function success_error($result, $success = array('成功', '', 3), $error = array('失败', '', 3)) {
        $success[1] = isset($success[1]) ? $success[1] : '';
        $success[2] = isset($success[2]) ? $success[2] : 3;
        $error[1] = isset($error[1]) ? $error[1] : '';
        $error[2] = isset($error[2]) ? $error[2] : 3;
        if ($result) {
            $this->success($success[0], $success[1], $success[2]);
        } else {
            $this->error($error[0], $error[1], $error[2]);
        }
    }

}

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-9
 * Describe   : 自定义扩展函数
 */

function alert($msg) {
    echo '<script>alert("' . $msg . '");window.history.back();</script>';
}
//创建一个32位随机令牌码
function token()
{
    return md5(str_shuffle(chr(mt_rand(32, 126)) . uniqid() . microtime(TRUE)));
}
/**
 * 删除编译模板，格式:应用/控制器/方法
 * @param type $path 
 */
function delCompileTpl($path) {
    $path_array = explode('/', trim($path, '/'));
    $path_nums = count($path_array);
    switch ($path_nums) {
        case 1:$temp = APP_GROUP . '_G/' . $path_array[0];
            break;
        case 3:$temp = APP_GROUP . '_G/' . $path_array[0] . '_A/' . $path_array[1] . '_C/' . $path_array[2];
            break;

        default:
            break;
    }
    $path = PATH_TEMP . '/Applications/' . $temp;
    dir::del($path);
}

function array_filter_trim($value) {
    if (trim($value) == '') {
        return FALSE;
    }
    return TRUE;
}

/*
 * 格式化层级数据
 */

function formatLevelData($data, $pid = 0) {
    $arr = array();
    foreach ($data as $v) {
        if ($v['pid'] == $pid) {
            $arr[$v['laid']] = $v;
            $arr[$v['laid']]["data"] = formatLevelData($data, $v['laid']);
        }
    }
    return $arr;
}

/**
 * 格式化数据为层级化数据
 * @param type $data 需要格式化的
 * @param type $format 数据的原索引格式[主键字段名(例如id),层级字段名(例如pid)]
 * @param type $pid
 * @return type 
 */
function formatLevelData2($data, $format, $pid = 0) {
    $arr = array();
    foreach ($data as $v) {
        if ($v[$format[1]] == $pid) {
            $arr[$v[$format[0]]] = $v;
            $arr[$v[$format[0]]]["son_data"] = formatLevelData2($data, $format, $v[$format[0]]);
        }
    }
    return $arr;
}

/**
 * 将数组格式化为父子级关系（2级）
 * @param type $data 原数据
 * @param type $format 数据的原索引格式[主键字段名(例如id),层级字段名(例如pid)]
 * @return type 格式化后的数据 
 */
function formatParentData($data) {
    $data_num = count($data);
    $new_data = array();
    for ($i = 0; $i < $data_num; $i++) {
        if ($data[$i]['pid'] == 0) {
            $new_data['first'][$data[$i]['laid']] = $data[$i]['title'];
        } else {
            $new_data[$data[$i]['pid']][$data[$i]['laid']] = $data[$i]['title'];
        }
    }
    return $new_data;
}

/**
 * 将数组格式化为父子级关系（2级）
 * @param type $data 原数据
 * @param type $format 数据的原索引格式[主键字段名(例如id),层级字段名(例如pid),主要字段名(例如title、name)]
 * @return type 格式化后的数据 
 */
function formatParentData2($data,$format) {
    $data_num = count($data);
    $new_data = array();
    for ($i = 0; $i < $data_num; $i++) {
        if ($data[$i][$format[1]] == 0) {
            $new_data['first'][$data[$i][$format[0]]] = $data[$i][$format[2]];
        } else {
            $new_data["{$data[$i][$format[1]]}"][$data[$i][$format[0]]] = $data[$i][$format[2]];
        }
    }
    return $new_data;
}

function urlencode_array($var)
{
    if (is_array($var))
    {
        return array_map('urlencode_array', $var);
    }
    else
    {
        return urlencode($var);
    }
}
//不编码中文
function json_encode_cn($arr){
    return urldecode(json_encode(urlencode_array($arr)));
}

/**
 * 组合属性变量 
 */
function buildAttrVar($attr) {
    $str = '';
    $attr = str_replace(' ', '', $attr);
    if (isset($attr)) {
        if (strpos($attr, '$') === FALSE) {
            $str = $attr;
        } else {
            $preg = '/(\$[a-zA-Z_]\w*)(\[(\'|")\w+(\'|")\])*/';
            $str = preg_replace($preg, '<?php echo ${0};?>', $attr);
        }
    }
    return $str;
}

/**
 * 取得除了a c m 等的GET参数
 * @param type $unset 除了acm需要删除的参数
 */
function getCleanUriArg($unset = array()) {
    $get = $_GET;
    unset($get['a']);
    unset($get['c']);
    unset($get['m']);
    if (!empty($unset)) {
        foreach ($unset as $value) {
            unset($get[$value]);
        }
    }
    return $get;
}

/**
 * 取得Email模板
 * @param type $data 数据数组
 * @param type $type 模板类型
 * @return type 
 */
function getEmailTpl($type, $data = array()) {
    if (!isset($data['web_name'])) {
        $data['web_name'] = C('WEB_NAME');
    }
    if (!isset($data['web_host'])) {
        $data['web_host'] = __ROOT__;
    }
    $filename = PATH_ROOT . '/caches/email/' . $type . '.php';
    if (file_exists($filename)) {
        $tpl = include $filename;
    } else {
        $db = M('mail_tpl');
        $tpl = $db->field('subject,content')->where("type='$type'")->find();
    }
    foreach ($data as $key => $value) {
        $tpl['subject'] = str_replace('{' . $key . '}', $value, $tpl['subject']);
        $tpl['content'] = str_replace('{' . $key . '}', $value, $tpl['content']);
    }
    return $tpl;
}
/**
 * 写入用户操作日志
 * @param type $con     操作内容
 * @param type $point   积分变化
 * @param type $uid     用户ID
 */
function writeOptLog($con,$point=0,$uid=NULL){
    if(is_null($uid)){
        $uid=$_SESSION['uid'];
    }
    $data=array(
        'uid'=>$uid,
        'content'=>$con,
        'point'=>$point,
        'created'=>time(),
        'ip'=>ip_get_client(),//操作ip
        'username'=>$_SESSION['username']
    );
    $db=M('opt_log');
    $db->insert($data);
}
/**
 * 获取某个积分的规则
 * @param type $type 积分的调用名称
 */
function getPointRule($type){
    $db=K('backend/point_rule');
    $data=$db->getPointRule($type);
    return $data['operator'];
}
/**
 * 扣除/增加用户积分。减少SESSION和数据库
 * @param int $point 扣除的积分
 * @param int $user 扣除的用户ID
 */
function deductPoint($point,$user=NULL){
    if(is_null($user)){
        $user=$_SESSION['uid'];
    }
    $db=M('user_point');
    $db->inc('point','uid='.$user,$point);
    $_SESSION['point']+=$point;
}
/**
 * 获取节点的所有子节点ID
 * @param int $nid 节点ID
 * @param array $field 主键字段名(如：nid、id)、父字段名(如：pid)。默认：array('nid','pid');
 * @param $data 默认的数据,一般不传
 * @return 子节点数组
 */
function node_son_id($nid,$field=array('nid','pid'),$data=NULL){
    $db=M('node');
    if($nid!==FALSE){
        $n=$db->field($field[0])->where($field[0].'='.$nid)->findall();
    }else{
        $n=$data;
    }
    $nodes=array($field[0]=>array());
    foreach ($n as $value) {
            $v=$db->field($field[0])->where($field[1].'='.$value[$field[0]])->findall();
            if($v){
                $a=node_son_id(FALSE,$field,$v);
                foreach ($a[$field[0]] as $v_a) {
                    $nodes[$field[0]][]=$v_a;
                }
            }
            $nodes[$field[0]][]=$value[$field[0]];
    }
    return $nodes;
} ?>