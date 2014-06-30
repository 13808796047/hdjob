<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-7-25 上午10:27:48
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
class upload {

    //上传类型
    public $exts = array();
    //上传文件大小
    public $size;
    //上传路径 
    public $path;
    //错误信息
    public $error;
    //缩略图处理
    public $thumb_on;
    //缩略图参数
    public $thumb = array();
    //是否加水印
    public $waterMark_on;
    //上传成功文件信息
    public $uploadedFile = array();

    /**
     * 构造函数
     * @param sring $path		上传路径 
     * @param array $exts		允许的文件类型,传入数组如array('jpg','jpeg','png','doc')
     * @param array $size		允许上传大小,如array('jpg'=>200000,'rar'=>'39999')
     *                                  如果不设置系统会依据配置项C("UPLOAD_EXT_SIZE")值
     * @param $waterMark_on             是否加水印
     * @param boolean $thumb_on         是否生成缩略图
     * @param number   $thumb           缩略图处理参数  只接收3个参数 1缩略图宽度 2缩略图高度  3缩略图生成规则
     */
    public function __construct($path = '', $exts = '', $size = '', $waterMark_on = '', $thumb_on = '', $thumb = array()) {
        $this->path = empty($path) ? C("UPLOAD_PATH") : $path; //上传路径
        $this->exts = empty($exts) && !is_array($exts) ? array_keys(C("UPLOAD_EXT_SIZE")) : $exts; //上传类型
        $exts = array();
        foreach ($this->exts as $v) {
            $exts[] = strtoupper($v);
        }
        $this->exts = $exts;
        $this->size = $size ? $size : array_change_key_case_d(C("UPLOAD_EXT_SIZE"), 1);
        $this->waterMark_on = empty($waterMark_on) ? C("WATER_ON") : $waterMark_on;
        $this->thumb_on = empty($thumb_on) ? C("THUMB_ON") : $thumb_on;
        $this->thumb = $thumb;
    }

    /**
     * 上传文件
     * @return boolean 
     */
    public function upload() {
        if (!$this->checkDir($this->path)) {
            $this->error = $this->path . L("upload_upload_error");
            return false;
        }
        $files = $this->format();
        //验证文件
        foreach ($files as $v) {
            $info = pathinfo($v ['name']);
            $v ["ext"] = isset($info ["extension"]) ? $info['extension'] : '';
            $v['filename'] = isset($info['filename']) ? $info['filename'] : '';
            if (!$this->checkFile($v)) {
                continue;
            }
            $uploadedFile = $this->save($v);
            if ($uploadedFile) {
                $this->uploadedFile [] = $uploadedFile;
            }
        }
        return $this->uploadedFile;
    }

    /**
     *  储存文件
     * @param type $file
     * @param string $uploadFileName    储存的文件名
     * @return boolean 
     */
    private function save($file) {
        $is_img = 0;
        $uploadFileName = mt_rand(1, 9999) . time() . "." . $file['ext'];
        $filePath = $this->path . '/' . $uploadFileName;
        if (in_array(strtolower($file ['ext']), array("jpeg", "jpg", "bmp", "gif", "png")) && getimagesize($file ['tmp_name'])) {
            $imgDir = C("UPLOAD_IMG_DIR") ? "/" . C("UPLOAD_IMG_DIR") : "";
            $filePath = $this->path . $imgDir . '/' . $uploadFileName;
            if (!$this->checkDir($this->path . $imgDir)) {
                $this->error = L("upload_save_error1");
                return false;
            }
            $is_img = 1;
        }
        if (!move_uploaded_file($file ['tmp_name'], $filePath)) {
            $this->error(L("upload_save_error2"));
            return false;
        }

        if (!$is_img) {
            $filePath = ltrim(str_replace(PATH_ROOT, '', $filePath), '/');
            return array("path" => $filePath, 'fieldname' => $file['fieldname']);
        }
        //处理图像类型文件
        $img = new image ();
        //对原图进行缩放
        if (C("UPLOAD_IMG_RESIZE_ON")) {
            $img->thumb($filePath, $uploadFileName, '', C("UPLOAD_IMG_MAX_WIDTH"), C("UPLOAD_IMG_MAX_HEIGHT"), 5);
        }
        //生成缩略图
        if ($this->thumb_on) {
            $args = array();
            if (empty($this->thumb)) {
                array_unshift($args, $filePath);
            } else {
                array_unshift($args, $filePath, "", "");
                $args = array_merge($args, $this->thumb);
            }
            $thumbFile = call_user_func_array(array($img, "thumb"), $args);
        }
        //加水印
        if ($this->waterMark_on) {
            $img->water($filePath);
        }
        $filePath = ltrim(str_replace(PATH_ROOT, '', $filePath), '/');
        if ($this->thumb_on) {
            $thumbFile = ltrim(str_replace(PATH_ROOT, '', $thumbFile), '/');
            return array("path" => $filePath, "thumb" => $thumbFile, 'fieldname' => $file['fieldname']);
        } else {
            return array("path" => $filePath, 'fieldname' => $file['fieldname']);
        }
    }

    //将上传文件整理为标准数组
    public function format() {
        $files = $_FILES;
        if (!isset($files)) {
            $this->error = L("upload_format_error");
            return false;
        }
        $info = array();
        $n = 0;
        foreach ($files as $name => $v) {
            if (is_array($v ['name'])) {
                $count = count($v ['name']);
                for ($i = 0; $i < $count; $i++) {
                    foreach ($v as $m => $k) {
                        $info [$n] [$m] = $k [$i];
                    }
                    $info [$n] ['fieldname'] = $name; //字段名
                    $n++;
                }
            } else {
                $info [$n] = $v;
                $info [$n] ['fieldname'] = $name; //字段名
                $n++;
            }
        }
        return $info;
    }

    /**
     * 验证目录
     * @param string $path		目录
     */
    public function checkDir($path) {
        return dir::create($path) && is_writeable($path) ? true : false;
    }

    public function checkFile($file) {
        if ($file ['error'] != 0) {
            $this->error($file ['error']);
            return false;
        }
        $ext = strtoupper($file ['ext']);
        $ext_size = is_array($this->size) && isset($this->size[$ext]) ? $this->size[$ext] : $this->size;
        if (!in_array($ext, $this->exts)) {
            $this->error = L("upload_checkFile_error1");
            return false;
        }

        if ($file ['size'] > $ext_size) {
            $this->error = L("upload_checkFile_error2") . get_size($ext_size);
            return false;
        }

        if (!is_uploaded_file($file ['tmp_name'])) {
            $this->error = L("upload_checkFile_error3");
            return false;
        }
        return true;
    }

    private function error($error) {
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE :
                $this->error = L("upload_error_error1");
                break;
            case UPLOAD_ERR_FORM_SIZE :
                $this->error = L("upload_error_error2");
                break;
            case UPLOAD_ERR_PARTIAL :
                $this->error = L("upload_error_error3");
                break;
            case UPLOAD_ERR_NO_FILE :
                $this->error = L("upload_error_error4");
                break;
            case UPLOAD_ERR_NO_TMP_DIR :
                $this->error = L("upload_error_error5");
                break;
            case UPLOAD_ERR_CANT_WRITE :
                $this->error = L("upload_error_error6");
                break;
        }
    }

    public function geterror() {
        return $this->error;
    }

}