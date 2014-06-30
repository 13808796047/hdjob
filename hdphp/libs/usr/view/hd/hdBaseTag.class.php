<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: hdFunctionTag.php      2012-2-6  16:36:09
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
class hdBaseTag {

//block 块标签          1为块标签  0独立标签 
    public $tag = array(
        'foreach' => array('block' => 1, 'level' => 4),
        'while' => array('block' => 1, 'level' => 4),
        'if' => array('block' => 1, 'level' => 3),
        'elseif' => array('block' => 0),
        'else' => array('block' => 0),
        'switch' => array('block' => 1),
        'case' => array('block' => 1),
        'break' => array('block' => 0),
        'default' => array('block' => 0),
        'load' => array('block' => 0),
        'include' => array('block' => 0),
        'loadfile' => array('block' => 0),
        'list' => array('block' => 1, 'level' => 3),
        'js' => array('block' => 0),
        'css' => array('block' => 0),
        'noempty' => array('block' => 0),
        'editor' => array('block' => 0),
        'jquery' => array('block' => 0),
        'upload' => array('block' => 0), //uploadif上传组件
        'zoom' => array('block' => 0), //图片放大镜
        'jsconst' => array("block" => 0), //定义JS常量
        'define' =>array("block"=>0),
    );
    public function _define($attr,$content){
        $name = $attr['name'];
        $value=$attr['value'];
        $str = "";
        $str.="<?php ";
        $str.="define('{$name}',$value);";
        $str.=";?>";
        return $str;
    }

    //设置js常量 
    public function _jsconst($attr, $content) {
        $const = get_defined_constants(true);
        $arr = preg_grep("/http/", $const['user']);
        $str = "<script type='text/javascript'>\n";
        foreach ($arr as $k => $v) {
            $str.="\t\t" . str_replace("_", '', $k) . " = '" . $v . "';\n";
        }
        $str.="</script>";
        return $str;
    }
    public function _js()
    {
        $file = trim($attr['file']);
       return '<script type="text/javascript" src="' . $file . '"></script>';
    }
    public function _css($attr, $content) {
        $file = trim($attr['file']);
       return '<link type="text/css" rel="stylesheet" href="' . $file . '"/>'; 
    }

    //图片放大镜
    public function _zoom($attr, $content) {
        if (!isset($attr['data']) || !isset($attr['pid']) || !isset($attr['sid'])) {
            error(L("hdbasetag__zoom"), false); //zoom标签必须设置 pid、sid、data属性，检查一下看哪个没有设置
        }

        $data = $attr['data'];
        $pid = $attr['pid'];
        $sid = $attr['sid'];
        $left = isset($attr['left']) ? $attr['left'] : 20;
        $top = isset($attr['right']) ? $attr['right'] : 20;
        $width = isset($attr['width']) ? $attr['width'] : 300;
        $height = isset($attr['height']) ? $attr['height'] : 250;
        $swfupload_path = __ORG__ . '/jqzoom_ev23'; //插件目录
        $str = '';
        $str.="<link rel = 'stylesheet' href = '" . $swfupload_path . "/css/jquery.jqzoom.css' type = 'text/css'>";
        $str.= "<script src = '" . $swfupload_path . "/js/jquery.jqzoom-core.js' type = 'text/javascript'></script>";
        $str.="
            <script type = 'text/javascript'>
            $(function() {
            $('#$pid').append(triumph);
            $('#$sid').append(smalls);
            $('.jqzoom').jqzoom({
            zoomType: 'standard',
            lens:true,
            preloadImages: false,
            alwaysOn:false,
            zoomWidth: $width,
            zoomHeight: $height,
            xOffset:$left,
            yOffset:$top
            });
            });
            </script>
                    ";
        $str.="<script type = 'text/javascript'>";
        $str.="
            var triumph = \" <a href='<?php echo " . $data . "[0][2]?>' class='jqzoom' rel='gal1'  title='triumph' >\
            <img src='<?php echo " . $data . "[0][1]?>'  title='triumph'  style='border: 4px solid #666;'>\
        </a>\";";
        $str.="var smalls=\"";
        $str.="<?php foreach( " . $data . ' as $k=>$v):?>';
        $str.='<?php $zoomThumbActive =  $k==0?"zoomThumbActive":""?>';
        $str.="
        <li>\
            <a class='<?php echo \$zoomThumbActive?>' href='javascript:void(0);' rel=\\\"{gallery: 'gal1', smallimage: '<?php echo \$v[1];?>',largeimage: '<?php echo \$v[2];?>'}\\\">\
                <img src='<?php echo \$v[0];?>'>\
            </a>\
        </li>\
         ";
        $str.="<?php endforeach;?>";
        $str.="\";</script>";
        return $str;
    }

    public function _upload($attr, $content) {
        if (!isset($attr['name'])) {
            error(L("hdbasetag__upload"), false); //上传标签upload必须指定name属性，如果不清楚使用规范请查看后盾HD框架手册
        }
        $swfupload_input_name = $attr['name']; //隐藏input名
        $session_name = session_name();
        $session_id = session_id();
        $size = isset($attr['size']) ? $attr['size'] . "MB" : "2MB";
        $swfupload_size = (int) $size * 1000000; //PHP upload所需要上传大小
        $limit = isset($attr['limit']) ? $attr['limit'] : "10"; //上传文件数量
        $text = isset($attr['text']) ? $attr['text'] : "选择文件";
        $dir = isset($attr['dir']) ? $attr['dir'] : "";
//        $thumbon = isset($attr['thumbon']) ? $attr['thumbon'] : 0;
        $water_on = isset($attr['wateron']) ? $attr['wateron'] : 0;
        $url = __CONTROL__ . "/swfupload"; //PHP处理脚本
        $imagesize = isset($attr['imagesize']) ? $attr['imagesize'] : '';
        $upload_display_width = isset($attr['width']) ? $attr['width'] : 200;
        $upload_display_height = isset($attr['height']) ? $attr['height'] : 200;
        $type = isset($attr['type']) ? preg_split("/,|，|;/i", $attr['type']) : "*.*";
        if ($type !== "*.*" && $type !== '*') {
            $type = trim('*.' . implode(";*.", $type), ';');
        }
        $swfupload_path = __ORG__ . '/swfupload250';

        $str = '';
        $str.='<?php $swfupload_path="' . $swfupload_path . '"?>';
        $str.='<?php $url="' . $url . '"?>';
        $str.='<?php $delurl="' . __CONTROL__ . '/swfuploaddel"?>';
        $str.='<?php $size="' . $size . '"?>';
//        $str.='<?php $thumbon="' . $thumbon . '"? >';
        $str.='<?php $water_on="' . $water_on . '"?>';
        $str.='<?php $upload_display_width="' . $upload_display_width . '"?>';
        $str.='<?php $upload_display_height="' . $upload_display_height . '"?>';
        $str.='<?php $imagesize="' . $imagesize . '"?>';
        $str.='<?php $swfupload_size="' . $swfupload_size . '"?>';
        $str.='<?php $limit="' . $limit . '"?>';
        $str.='<?php $text="' . $text . '"?>';
        $str.='<?php $dir="' . $dir . '"?>';
        $str.='<?php $session_name="' . $session_name . '"?>';
        $str.='<?php $session_id="' . $session_id . '"?>';
        $str.='<?php $type="' . $type . '"?>';
        $str.='<?php $swfupload_id=isset($swfupload_id)?++$swfupload_id:0?>';
        $str.='<?php $input_hidden="' . $swfupload_input_name . '"?>';

        $str.='<?php if($swfupload_id==0):?>';
        $str.="
<link href='" . $swfupload_path . "/css/default.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='" . $swfupload_path . "/js/handlers.js'></script>
<script type='text/javascript' src='" . $swfupload_path . "/swfupload/swfupload.js'></script>
<script type='text/javascript' src='" . $swfupload_path . "/swfupload/swfupload.queue.js'></script>
<script type='text/javascript' src='" . $swfupload_path . "/js/fileprogress.js'></script>  
  <script type='text/javascript' src='" . $swfupload_path . "/hd_set.js'></script>
";
        $str.='<?php endif;?>';

        $str.=file_get_contents(PATH_HD . '/org/swfupload250/hd_set.php');
        $str.="
            <!--上传显示DIV-->
        <div class='fieldset flash' id='fsUploadProgress<?php echo \$swfupload_id;?>'>
        </div>

        <div class='swfupload_button'  href='javascript:;'>
            <span id='spanButtonPlaceHolder'></span>
            <i class='ico-btn ib-upload' style='position:absolute;left:10px;top:5px;'></i>
        </div>
        <div id='swfupload_message<?php echo \$swfupload_id;?>'></div>
        <div  class='swfupload_file_show' id='swfupload_file_show<?php echo \$swfupload_id;?>'>
        <?php if(\$swfupload_id==0):?>
            <div class='swfupload_input'></div>
            <?php endif;?>
            <ul></ul>
        </div>
        ";
        return $str;
    }
    //编辑器
    public function _editor($attr,$content){
        $type =isset($attr['type'])?$attr['type']:C("EDITOR_TYPE");
        $editor= $type==1?"_ueditor":"_keditor";
        $attr['style']=isset($attr['style'])?$attr['style']:C("EDITOR_STYLE");

        //编辑器内容
        $attr['content'] = isset($attr['content'])?$attr['content']:"";
        //表单name</script>
        if(!isset($attr['name'])){
            error("editor 标签必须有name属性",false);
        }
        $attr['name'] = $attr['name'];
        //宽度高度
        $attr['width'] = isset($attr['width'])?trim($attr['width'],'px').'px':"100%";
        $attr['height'] = isset($attr['height'])?trim($attr['height'],'px').'px':"300px;";
        return $this->$editor($attr,$content);
    }
    //kindeditor
    protected function _keditor($attr,$content){
        static $id=0;
        $id++;
        $attr['content'] = isset($attr['content']) ? (strstr($attr['content'], "$") ? '<?php echo ' . $attr['content'] . '?>' : $attr['content']) : ''; //默认内容
        //kingeditor变量
        $editor = "kingeditor".$id;
        //kingeditor路径 
        $kindeitor_path = __ORG__.'/kindeditor';

        $str = '';
        $str.="<script charset='utf-8' src='$kindeitor_path/kindeditor.js'></script>";
        $str.="<script charset='utf-8' src='$kindeitor_path/lang/zh_CN.js'></script>";
        switch($attr['style']){
            case 1:
        $str.="<script>
                     var options={
                         width : '".$attr['width']."',
                         height:'".$attr['height']."',
                         uploadJson : '".__CONTROL__."/keditorupload',
                     }
                </script>";
            break;
            case 2:
            $str.="<script>
               var options= {
                    width : '".$attr['width']."',
                    height:'".$attr['height']."',
                    resizeType : 1,
                    allowPreviewEmoticons : false,
                    allowImageUpload : false,
                    items : [
                        'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                        'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                        'insertunorderedlist', '|', 'emoticons', 'image', 'link']
                }
            </script>";
        }
        $str.="<textarea id='$editor' name='".$attr['name']."'>".$attr['content']."</textarea>";
        $str.="<script>
                    var $editor;
                    KindEditor.ready(function(K) {
                            editor = K.create('#{$editor}',options);
                    });
                </script>";
        return $str;
    }
    //百度编辑器
    protected function _ueditor($attr, $content) {
        $style = $attr['style'];
        $id = "hd_ueditor" . mt_rand(1, 1000);

        $content = isset($attr['content']) ? (strstr($attr['content'], "$") ? '<?php echo ' . $attr['content'] . '?>' : $attr['content']) : ''; //默认内容
        //允许输入的最大字数
        $maximumWords = isset($attr['max']) ? $attr['max'] : C("EDITOR_MAX_STR"); 
        
        // $width = isset($attr['width']) ? "style=' width:" . intval($attr['width']) . "px'" : "style='width:888px'";
        $width=$attr['width'];
        $height = (int)$attr['height'];
        // $height = isset($attr['height']) ? intval($attr['height']) : intval(C("EDITOR_HEIGHT"));
        if (!isset($attr['name'])) {
            error(L("hdbasetag__editor"), false);
        }
        $name = $attr['name'];
        $str = '';
                $toole = '';
               //精简或全部toole
                    switch ($attr['style']) {
                        case 1:
                            $toole = "";
                            break;
                        case 2:
                            $toole = "toolbars:[['FullScreen', 'Source', 'Undo', 'Redo','Bold']],";
                            break;
                    }
               
                $path = __HDPHP__ . '/org/ueditor';
                $str.='<script type="text/javascript">HD_UEDITOR_ROOT="' . __HDPHP__ . '/org/ueditor/";</script>';
                $str.='<script type="text/javascript" src="' . $path . '/editor_config.js"></script>
		<script type="text/javascript" src="' . $path . '/editor_all.js"></script>
		<link rel="stylesheet" href="' . $path . '/themes/default/ueditor.css">';
                $str.='<script type="text/plain" name="' . $name . '" id="' . $id . '" ' . $width . '>' .
                        $content . '</script>';
                $str.='<script type="text/javascript" >';
                $str.= 'var editorOption = {
                        autoFloatEnabled:false,
                         ' . $toole . '
                         imageUrl:"__CONTROL__/ueditorupload",
                         imagePath:"",
                         fileUrl:"__CONTROL__/ueditorupload",
                         filePath:"",
                         maximumWords:' . $maximumWords . ',
                         minFrameHeight:' . $height . '
                    };';
                $str.='var editor = new baidu.editor.ui.Editor(editorOption);
                    editor.render("' . $id . '")';
                $str.='</script>';
                return $str;
    }
    
    public function _jquery($attr, $content) {
        return '<script type="text/javascript" src="' . __ROOT__ . '/public/js/jquery-1.7.2.min.js"></script>';
    }

    public function _list($attr, $content) {
        static $list_id = 0;
        $list_id++;
        $from = $attr['from'];
        $name = $attr['name'];
        $row = isset($attr['row']) ? $attr['row'] : 100;
        $step = isset($attr['step']) ? $attr['step'] : 1;
        $php = '';
        $php.='<?php if(!empty(' . $from . ')){$list_id' . $list_id . '=0;?>';
        $php.='<?php foreach(' . $from . ' as $key=>' . $name . '){?>';
        $php.='<?php if($list_id' . $list_id.'=='.$row.') break;?>';
        $php.='<?php $list_id' . $list_id . '+=' . $step . ';?>';
        $php.=$content;
        $php.='<?php }?>';
        $php.='<?php }?>';

        return $php;
    }

    public function _foreach($attr, $content) {
        if (empty($attr['from'])) {
            error(L("hdbasetag__upload1"), false); //foreach 模板标签必须有from属性
        }
        if (empty($attr['value'])) {
            error(L("hdbasetag__upload2"), false); //foreach 模板标签必须有value属性
        }
        $php = ''; //组合成PHP
        $from = $attr['from'];
        $key = isset($attr['key']) ? $attr['key'] : false;
        $value = $attr['value'];
        $php.="<?php if(is_array($from)):?>";
        if ($key) {
            $php.='<?php ' . " foreach($from as $key=>$value){ ?>";
        } else {
            $php.='<?php ' . " foreach($from as $value){ ?>";
        }
        $php.=$content;
        $php.='<?php }?>';
        $php.="<?php endif;?>";
        return $php;
    }

    public function _load($attr, $content) {
        if (!isset($attr['file'])) {
            error(L("hdbasetag__load"), false); //load标签必须有file属性
        }
        $file = $attr['file'];
        $preg = array(
            '/__TPL__/',
            '/__PUBLIC__/',
            '/__ROOT__/'
        );
        $replace = array(
            PATH_TPL,
            PATH_TPL . '/public',
            PATH_ROOT
        );
        $file = preg_replace($preg, $replace, $file);
        $view = new hdView($file);
        $view->fetch($file);
        return $view->get_compile_content();
    }

    public function _loadfile($attr, $content) {
        return $this->_load($attr, $content);
    }

    public function _include($attr, $content) {
        return $this->_load($attr, $content);
    }

    public function _switch($attr, $content, $res) {
        $value = $attr['value'];
        $php = ''; //组合成PHP
        $php.='<?php ' . " switch($value){ ?>";
        $php.=strip_space($content);
        $php.='<?php }?>';
        return $php;
    }

    public function _case($attr, $content, $res) {
        $value = $attr['value'];
        $php = ''; //组合成PHP
        $php.='<?php ' . " case $value :{ ?>";
        $php.=strip_space($content);
        $php.='<?php }?>';
        return $php;
    }

    public function _break($attr, $content, $res) {
        return '<?php break;?>';
    }

    public function _default($attr, $content, $res) {
        return '<?php default;?>';
    }

    public function _if($attr, $content, $res) {
        if (empty($attr['value'])) {
            error(L("hdbasetag__if"), false); //if 模板标签必须有value属性
        }
        $value = $attr['value'];
        $php = ''; //组合成PHP
        $php.='<?php if(' . $value . '){?>';
        $php.=$content;
        $php.='<?php }?>';
        return $php;
    }

    public function _elseif($attr, $content, $res) {
        $value = $attr['value'];
        $php = ''; //组合成PHP
        $php.='<?php ' . " }elseif($value){ ?>";
        $php.=$content;
        return $php;
    }

    public function _else($attr, $content, $res) {
        $php = ''; //组合成PHP
        $php.='<?php ' . " }else{ ?>";
        return $php;
    }

    public function _while($attr, $content, $res) {
        if (empty($attr['value'])) {
            error(L("hdbasetag__while"), false); //while模板标签必须有value属性
        }
        $value = $attr['value'];
        $php = ''; //组合成PHP
        $php.='<?php ' . " while($value){ ?>";
        $php.=$content;
        $php.='<?php }?>';
        return $php;
    }

    public function _empty($attr, $content, $res) {
        if (empty($attr['value'])) {
            error(L("hdbasetag__empty"), false); //empty模板标签必须有value属性
        }
        $value = $attr['value'];
        $php = "";
        $php.='<?php ' . ' if(@empty(' . $value . ')){?>';
        $php.=$content;
        $php.='<?php }?>';
        return $php;
    }

    public function _noempty($attr, $content) {
        return '<?php }else{ ?>';
    }

}