<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-9-12
 * Describe   : 广告管理
 */

class adsControl extends myControl {

    public $ads_type = array(
        1 => '文字', 2 => '图片'
    );
    private $ads_cate;
    private $ads;

    function __construct() {
        parent::__construct();
        $this->ads_cate = M('ads_cate');
        $this->ads = M('ads');
    }

    function _updateAdsJs($cate=2){
        $cond=array(
            'cate'=>$cate,
            'endtime > '.time(),
            'state'=>1
        );
        $limit=5;
        if(isset($_GET['limit'])){
            $limit=intval($_GET['limit']);
        }
        $db=V('ads');
        $db->view = array(
            'ads_cate' => array(
                'type' => 'INNER',
                'on' => 'ads.cate=ads_cate.id',
                'field'=>'type'
            )
        );
        $ads=$db->where($cond)->order('sort')->limit($limit)->findall();
        $str="document.write('";
        if(is_array($ads)){
            foreach ($ads as $value) {
                $img='';
                $text='';
                if(!empty($text)){
                    if(!empty($value['color'])){
                        $color='style="color:'.$value['color'].'"';
                    }
                    $text='<span '.$color.'>'.$text.'</span>';
                }
                if(trim(substr($value['href'], 0,4))=='http'){
                    $href=$value['href'];
                }else{
                    $href=__ROOT__.'/'.$value['href'];
                }
                if(trim(substr($value['path'], 0,4))=='http'){
                    $img='<img src="'.$value['path'].'" />';
                }else{
                    $img='<img src="'.__ROOT__.'/'.$value['path'].'" />';
                }
                $str.='<li title="'.$value['text'].'"><a href="'.$href.'">'.$text.$img.'</a></li>';
            }
        }
        $str.="');";
        //file_put_contents(PATH_ROOT.'/text_'.$limit.'.js', $str);
    }

    function addAds() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(empty($_POST['uid'])){
                unset($_POST['uid']);
            }
            if (isset($_POST['path']) && !empty($_POST['path']['web_url'])) {
                $_POST['path'] = $_POST['path']['web_url'];
            } else {
                $_POST['path'] = $_POST['path'][1][0];
            }
            $_POST['addtime'] = time();
            $_POST['starttime'] = empty($_POST['starttime']) ? time() : strtotime($_POST['starttime']);
            $_POST['endtime'] = empty($_POST['endtime']) ? time() + 864000 : strtotime($_POST['endtime']);
            if ($this->ads->insert()) {
                //更新广告js文件
                //$this->_updateAdsJs($cate);
                $this->success('添加广告成功!', __METH__ . '/action/1');
            }
        }
        $cates = $this->ads_cate->findall();
        $db = V('ads');
        $db->view = array(
            'ads_cate' => array(
                'type' => 'INNER',
                'on' => 'ads.cate=ads_cate.id',
            )
        );
        $ads = $db->field('ads.id,ads_title,cate,href,addtime,starttime,endtime,sort,text,path,uid,color,state,width,height,title,type')
                    ->order('addtime desc')
                    ->findall();
        $this->assign('cates', $cates);
        $this->assign('ads', $ads);
        $this->assign('ads_type', $this->ads_type);
        $this->display('ads');
    }

    function editAds() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['starttime'] = empty($_POST['starttime']) ? time() : strtotime($_POST['starttime']) + 300;
            $_POST['endtime'] = empty($_POST['endtime']) ? time() + 864000 : strtotime($_POST['endtime']) + 300;
            if (isset($_POST['path']) && !empty($_POST['path']['web_url'])) {
                $_POST['path'] = $_POST['path']['web_url'];
            } else {
                $_POST['path'] = $_POST['path'][1][0];
            }
            $r = $this->ads->where('id=' . $_GET['id'])->update($_POST);
            if ($r >= 0) {
                $this->success('修改成功', __CONTROL__ . '/addAds/action/1');
            }
        }
        $db = V('ads');
        $db->view = array(
            'ads_cate' => array(
                'type' => 'INNER',
                'on' => 'ads.cate=ads_cate.id',
                'field'=>'title,tname,type'
            )
        );
        $ads = $db->where('hd_ads.id=' . $_GET['id'])->find();
        $cates = $this->ads_cate->findall();
        $this->assign('cates', $cates);
        $this->assign('ads', $ads);
        $this->display();
    }
    
    //删除广告
    function delAds() {
        $path = $this->ads->field('path')->find($_POST['id']);
        //同时删除广告文件
        if (!empty($path['path']) && substr($path['path'], 0, 4) != 'http') {
            @unlink(PATH_ROOT . '/' . $path['path']);
        }
        echo $this->ads->del($_POST['id']);
        exit;
    }

    /**
     * 添加广告位 
     */
    function addAdvert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->ads_cate->insert()) {
                $this->success('添加广告位成功！');
            }
        }
    }

    //删除广告位
    function delAdvert() {
        echo $this->ads_cate->del($_POST['id']);
        exit;
    }

    //修改广告位
    function editadvert() {
        echo $this->ads_cate->where('id=' . $_POST['id'])->update($_POST) >= 0;
        exit;
    }


    public function _hd_edit_upload($attr, $content) {
        $str = '';
//拆分原图，小缩略图，大缩络图
        $str .= '<?php $picture = explode("|",$data["picture"]);';
        $str .= '$thumb_mini = explode("|",$data["thumb_mini"]);';
        $str .= '$thumb_max = explode("|",$data["thumb_max"]);';
        $str .= '$str = "<script language = \'javascript\'>window.onload = function(){";';
//循环组合SWFUpload参数数组
        $str .= 'for ($i = 0; $i < count($picture); $i++) :';
        $str .= '$oldImgFile = array("id" => "SWFUpload_0_0", "index" => $i , "size" => "" , "name" => $i,);';
        $str .= '$oldImgData = array("state" => "SUCCESS","fid" => $i,';
        $str .= '"thumb" => array("file" => "' . __ROOT__ . '/". $picture[$i],"w" => ' . C("SWFUPLOAD_THUMB_WIDTH") . ',"h" => ' . C("SWFUPLOAD_THUMB_HEIGHT") . ',),';
        $str .= '"file" => array(0 => array("path" => $picture[$i],"url" => "' . __ROOT__ . '/". $picture[$i],),';
        $str .= '1 => array("path" => $thumb_mini[$i],),2 => array("path" => $thumb_max[$i],),),);';
//调用SWFUpload上传图片成功函数
        $str .= '$str .= "uploadSuccess(" . json_encode($oldImgFile) . " , " . var_export(json_encode($oldImgData),true) . " );";';
        $str .= 'endfor;';
        $str .= '$str .= "}</script>";';
        $str .= 'echo $str;?>';
        return $str;
    }

}

