<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-6-27
 * Describe   : 数据模型控制器
 */

class dataModelControl extends myControl {

    private $data_model;
    private $view_array_index;
    private $cache_dir;
    private $web_config;
    private $not_length;//不需要length的字段类型

    function __construct() {
        parent::__construct();
        $this->cache_dir = rtrim(C('CACHE_DIR'), '/') . '/';
        $this->data_model = K('data');
        $this->web_config = K('webConfig');
        $this->view_array_index = array(
            //状态
            'state' => array(
                0 => '关闭',
                1 => '开启'
            ),
            //条件
            'isnot' => array(
                0 => '否',
                1 => '是'
            ),
            //联动风格
            'linkage_style' => array(
                1 => '多级联动',
                2 => 'DIV弹出层'
            ),
            //编辑器风格
            'editor_style' => array(
                1 => '简洁',
                2 => '完全',
            )
        );
    }

    /**
     * 模型列表 
     */
    function modelList() {
        $model = $this->data_model->modelList();
        $this->assign('model_list', $model);
        $this->assign('model_tpl', $this->_listModaltpl());
        $this->display();
    }

    /**
     * 删除模型 
     */
    function delModel() {
        if (isset($_GET['dmid'])) {
            $result = $this->data_model->delModel($_GET['dmid']);
            if ($result) {
                echo '<script>alert("删除成功");window.history.go(-1)</script>';
            } else {
                echo '<script>alert("删除失败");window.history.go(-1)</script>';
            }
        }
    }

    /**
     * 列出前台风格的模型视图界面
     */
    private function _listModaltpl(){
        $dir=array();
        $front_config=include PATH_ROOT.'/config/app.php';
        $style=$front_config['TPL_DIR'].'/'.$front_config['TPL_STYLE'];
        $dir=dir::tree($style.'/model');
        return $dir;
    }
    /**
     * 添加模型 
     */
    function addModel() {
        if (isset($_POST['addModel'])) {
            if ($this->data_model->addModel($_POST)) {
                $this->success('添加模型成功', 'modelList');
            }
        }
        $category = $this->data_model->cateList();
        $this->assign('category', $category);
        $this->assign('model_tpl', $this->_listModaltpl());
        $this->display();
    }

    function modelExist() {
        if ($this->data_model->modelExist($_POST['name'])) {
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }

    function editModel() {
        $condition = array('dmid' => $_GET['dmid']);
        $result = $this->data_model->editModel($condition, $_POST);
        $success = array('模型数据更新成功', 'modelList');
        $error = array('模型数据更新失败', 'modelList');
        $this->success_error($result, $success, $error);
    }

    /**
     * 添加模型分类 
     */
    function addModelCate() {
        if (isset($_POST['addCate'])) {
            if ($this->data_model->addModelCate($_POST)) {
                $this->success('添加模型成功', 'modelList');
            }
        }
    }

    /**
     * 字段管理 
     */
    function fieldList() {
        $condition = array(
            'dmid' => $_GET['dmid']
        );
        $field_list = $this->data_model->fieldList($condition);
        $this->assign('field_list', $field_list);
        $this->display();
    }

    /**
     * 所有字段指定排序和列表页显示
     */
    function sortField() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sort'])) {
            $sort = $_POST['sort_id'];
            asort($sort);
            $this->updateStructTpl($_GET['dmid']); //更新修改和发布时的模板
            $this->data_model->sortField($sort, 'sort', 'field_name');
            echo '<script>window.history.back();</script>';
        }
    }

    /**
     * 添加字段 
     */
    function addField() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addField'])) {
            $insert = $this->data_model->addField($_POST);
            $this->updateStructTpl($_POST['dmid']); //更新修改和发布时的模板
            $success = array('添加字段成功', "fieldList?dmid={$_POST['dmid']}&tableName={$_POST['table_name']}");
            $error = '添加字段失败';
            $this->success_error($insert, $success, $error);
        }
        $data = array(
            'field_type' => $this->data_model->getFieldType(),
            'linkage_cate' => $this->data_model->linkageCateList(),
            'linkage_field' => $this->data_model->getLinkageField("lcgid>0"),
            'view_array_index' => $this->view_array_index,
            'validate_rule' => $this->web_config->getRule()
        );
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 删除字段 
     */
    function delField() {
        $cond = array(
            'mfid' => $_GET['mfid'],
            'field_name' => $_GET['field_name'],
            'table' => $_GET['table'],
            'dmid' => $_GET['dmid']
        );
        $result = $this->data_model->delField($cond);
        $this->updateStructTpl($_GET['dmid']); //更新修改和发布时的模板
        if ($result) {
            echo 1;
            exit;
        }
    }

    /**
     * 修改字段 
     */
    function editField() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!isset($_POST['setting'])){
                $_POST['setting']=array();
            }
            $result = $this->data_model->editField($_POST);
            $this->updateStructTpl($_POST['dmid']); //更新修改和发布时的模板
            $success = array('修改成功', 'modelList');
            $error = array('修改失败');
            $this->success_error($result, $success, $error);
        }
        $mfid = $_GET['mfid'];
        $condition = array(
            'mfid' => $mfid
        );
        $data = array(
            'field_info' => $this->data_model->getFieldInfo($condition),
            'field_type' => $this->data_model->getFieldType(),
            'linkage_field' => $this->data_model->getLinkageField("lcgid > 0 AND dmid = {$_GET['dmid']}"),
            'linkage_cate' => $this->data_model->linkageCateList(),
            'view_array_index' => $this->view_array_index,
            'validate_rule' => $this->web_config->getRule()
        );
        $this->assign('data', $data);
        $this->display();
    }
    /**
     * 更新修改和发布时的模板
     * 路径: PATH_ROOT . '/' . APP_GROUP . '/caches/model/model_';
     * @param type $model 模型ID
     */
    function updateStructTpl($model) {
        $field=new field($model);
        $field->build_field();
    }

    /**
     * 联动数据分类列表 
     */
    function linkageCateList() {
        $linkage_cate = $this->data_model->linkageCateList();
        $this->assign('linkage_cate', $linkage_cate);
        $this->display();
    }

    function addLinkage() {
        $linkage_cate = $this->data_model->linkageCateList();
        $this->assign('linkage_cate', $linkage_cate);
        $this->display();
    }

    /**
     * 添加联动分类 
     */
    function addLinkageCate() {
        if (isset($_POST['add_cate'])) {
            $result = $this->data_model->addLinkageCate($_POST);
            if ($result) {
                $this->success('添加联动分类成功', 'linkageCateList');
            }
        }
    }

    /**
     * 联动数据排序 
     */
    function sortLinkage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sortLinkage'])) {
            $this->data_model->sortLinkage($_POST['sort']);
            $lcgid = $_GET['lcgid'];
            $pid = $_GET['pid'];
            $cache_name = "linkage_{$lcgid}_{$pid}"; //配置缓存名称
            $cache_dir = $this->cache_dir . 'linkage';
            cache_del($cache_name, $cache_dir); //删除缓存
            echo '<script>window.history.back();</script>';
        }
    }

    /**
     * 管理联动数据 
     */
    function manageLinkageData() {
        $pid = isset($_GET['pid']) ? $_GET['pid'] : 0;
        $condition = array('lcgid' => $_GET['lcgid'], 'pid' => $pid);
        $condition_all = array('lcgid' => $_GET['lcgid']);

        $linkage_data_all = $this->data_model->getLinkageDataAll($condition_all);
        $linkage_data = $this->data_model->getLinkageData($condition);
        $this->assign('pid', $pid);
        $this->assign('linkage_data', $linkage_data);
        $this->assign('linkage_data_all', $linkage_data_all);
        $this->display();
    }

    /**
     * 添加联动数据 
     */
    function addLinkageData() {
        $data = array();
        $title = str_replace(array('，'), ',', trim($_POST['title']));
        $exp_arr = explode(',', trim($title, ','));
        $data_nums = count($exp_arr);
        for ($i = 0; $i < $data_nums; $i++) {
            //组合插入数据
            $data[] = array(
                'pid' => $_POST['pid'],
                'title' => trim($exp_arr[$i]),
                'lcgid' => $_POST['lcgid'],
                'path' => $_POST['path']
            );
        }
        $insert = $this->data_model->addLinkageData($data);
        if ($insert) {
            //删除缓存分类缓存
            $cache_name = 'linkage_' . $_POST['lcgid'] . '_' . $_POST['pid']; //配置缓存名称
            cache_del($cache_name, $this->cache_dir . 'linkage');
            //删除所有缓存
            $cache_name_all = 'linkage_all_' . $_POST['lcgid'];
            cache_del($cache_name_all, $this->cache_dir . 'linkage');
            //更新JS文件
            $this->updateLinkageJs();
            $this->success('添加联动数据成功');
        } else {
            $this->success('添加联动数据失败');
        }
    }

    //所有分类数据写入js,包括地区
    function updateLinkageJs() {
        $js = new data;
        $js->writeCatesToJs();
        echo '<script>window.history.back();</script>';
    }

    //分类管理-地区管理
    function category(){
        $db=M('city');
        $pid=isset($_GET['pid'])?$_GET['pid']:0;
        $parents=$db->where("id=".$pid)->order('sort')->find();
        $citys=$db->where("pid=".$pid)->order('sort')->findall();
        $this->assign('parents',$parents);
        $this->assign('citys',$citys);
        $this->display();
    }
    function addCity(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $db=M('city');
            $_POST['name']=explode(',', str_replace('，', ',', $_POST['name']));
            $data=array();
            foreach ($_POST['name'] as$value) {
                $pinyin=string::pinyin($value);
                $data[]=array(
                    'name'=>$value,
                    'pid'=>$_POST['pid'],
                    'area'=>$_POST['area'],
                    'sort'=>$_POST['sort'],
                    'direct'=>isset($_POST['direct'])?1:0,//直辖市
                    'is_open'=>isset($_POST['is_open'])?1:0,//开通子站
                    'hot'=>isset($_POST['hot'])?1:0,//热门城市
                    'pinyin'=>$pinyin,
                    'ucfirst'=>strtoupper($pinyin{0})//取得城市首字母索引
                );
            }
            $db->insert($data);
            $this->success('添加地区成功！');
        }
    }

    //删除地区
    public function delArea()
    {
        $db=M('city');
        $cond=implode(',', $_POST['id']);
        $cond='id IN ('.$cond.') OR pid IN ('.$cond.')';
        if($db->where($cond)->del()){
            echo 1;
            exit();
        }
    }

    //地区排序
    public function sortArea()
    {
        $db=M('city');
        foreach ($_POST['sort'] as $key => $value) {
            $db->where('id='.$key)->update(array('sort'=>$value));
        }
        echo '<script>window.history.back();</script>';
    }
    public function editArea()
    {
        $_POST['direct']=isset($_POST['direct'])?1:0;//直辖市
        $_POST['is_open']=isset($_POST['is_open'])?1:0;//开通子站
        $_POST['hot']=isset($_POST['hot'])?1:0;//热门城市
        if(empty($_POST['area'])){
            unset($_POST['area']);
        }
        if(!empty($_POST['pinyin'])){
            $pinyin=$_POST['pinyin'];
        }else{
            $pinyin=string::pinyin($_POST['name']);
        }
        $_POST['pinyin']=$pinyin;
        $_POST['ucfirst']=strtoupper($pinyin{0});//取得城市首字母索引
        $db=M('city');
        if($db->where('id='.$_GET['id'])->update($_POST)>=0){
            //go('category');
            echo '<script>window.history.back();</script>';
        }
    }

    /**
     * 删除联动数据 
     */
    function delLinkageData() {
        $laid = $_GET['laid'];
        $result = $this->data_model->delLinkageData($laid);
        if ($result) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 修改联动数据 
     */
    function editLinkageData() {
        $db = M('linkage');
        $result = $db->update() >= 0;
        //删除缓存分类缓存
        $cache_name = 'linkage_' . $_POST['lcgid'] . '_' . $_POST['pid']; //配置缓存名称
        cache_del($cache_name, $this->cache_dir . 'linkage');
        //删除所有缓存
        $cache_name_all = 'linkage_all_' . $_POST['lcgid'];
        cache_del($cache_name_all, $this->cache_dir . 'linkage');
        //更新JS文件
        $this->updateLinkageJs();

        $success = array('修改成功！');
        $error = array('修改失败！');
        $this->success_error($result, $success, $error);
    }

    /**
     * 删除联动分类(同时会删除所有联动数据) 
     */
    function delLinkageCate() {
        $lcgid = $_GET['lcgid'];
        $this->data_model->delLinkageCate($lcgid);
        $this->updateLinkageJs();
        alert('删除成功');
    }

}