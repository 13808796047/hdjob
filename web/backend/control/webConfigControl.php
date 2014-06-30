<?php

/*
 * Encoding   : UTF-8
 * Author     : xiwangzishi
 * Email      : zhangbo1248@gmail.com
 * Created on : 2012-6-27
 * Describe   : 网站配置
 */

class webConfigControl extends myControl {

    private $web_config;
    private $point_rule;

    function __construct() {
        parent::__construct();
        $this->web_config = K('webConfig');
        $this->point_rule = K('point_rule'); //积分模型
    }
    function websiteConfig() {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $_POST['CACHE_DEFAULT_TIME']=(int)$_POST['CACHE_DEFAULT_TIME'];
            $this->_setConfig();
            go('websiteConfig');
        }
        $this->display();
    }
    /**
     * 用户设置
     */
    public function userConfig()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $_POST['AUTH_MAX_LOGIN_ATTEMPTS']=(int)$_POST['AUTH_MAX_LOGIN_ATTEMPTS'];
            $_POST['EMAIL_ACTIVATE_EXPIRE']=(int)$_POST['EMAIL_ACTIVATE_EXPIRE'];
            $_POST['AUTH_AUTOLOGIN_COOKIE_LIFE']=(int)$_POST['AUTH_AUTOLOGIN_COOKIE_LIFE'];
            $this->_setConfig();
            go('userConfig');
        }
        $this->display();
    }
    /**
     * 更改配置文件
     * @param array $data,POST数据
     * @param string $config 更改的文件
     */
    private function _setConfig($data=array(),$config='app')
    {
        if(empty($data)){
            $data=$_POST;
        }
        $config_path = PATH_ROOT . '/config/app.php';
        $config = include $config_path;
        $config = '<?php if (!defined("PATH_HD")) exit;return ' . str_replace(array("'TRUE'","'FALSE'"),array('TRUE','FALSE'),var_export(array_merge($config, $_POST), true)) . ';?>'; //新的配置文件
        file_put_contents($config_path, $config);
    }
    //以下为邮件设置
    //邮件服务器设置
    function emailConfig() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editEmail'])) {
            unset($_POST['editEmail']);
            $this->_setConfig();
        }
        $this->display();
    }

    function testEmail() {
        $email = new mail();
        if ($email->send($_POST['email'], '测试用户', '这是一封测试邮件！', '这是一封测试邮件！你看到此邮件，说明你的邮箱已经配置好了。欢迎来到' . '<a href="' . __WEB__ . '">' . C('WEB_NAME') . '</a><br /><a href="http://www.houdunwang.com"><img src="http://bbs.houdunwang.com/static/image/common/logo.png" alt="后盾PHP培训" /></a>')) {
            $this->success('邮件发送成功');
        }
        $this->success('邮件发送失败');
    }

    function emailTpl() {
        $db = M('mail_tpl');
        $type = $_GET['type'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($db->where("type='$type'")->update($_POST) >= 0) {
                $filename = PATH_ROOT . '/caches/email';
                if (!file_exists($filename)) {
                    dir_create($filename);
                }
                $filename.= '/' . $type . '.php';
                file_put_contents($filename, '<?php return ' . var_export(array('subject' => $_POST['subject'], 'content' => $_POST['content']), true) . '?>');
                $this->success('更新成功', __CONTROL__ . '/emailConfig/action/3');
            }
        }
        $placeholder = array(
            'web_name' => '网站名称',
            'web_host' => '网站域名'
        );
        switch ($type) {
            case 'register_active':
                $placeholder = array_merge($placeholder, array('username' => '用户名', 'email' => '邮箱', 'password' => '密码', 'activate_url' => '激活地址', 'expire' => '有效时间（小时）'));
                break;
            case 'register_info':
                $placeholder = array_merge($placeholder, array('username' => '用户名', 'email' => '邮箱', 'password' => '密码'));
                break;
            default:
                break;
        }
        $tpl = $db->where("type='$type'")->find();
        $this->assign('tpl', $tpl);
        $this->assign('placeholder', $placeholder);
        $this->display();
    }
    public function hotKeyword()
    {
        $db=M('keywords');
        $nums=$db->count();
        $page=new page($nums,12);
        $keywords=$db->order('nums desc')->findall($page->limit());
        $this->assign('keywords', $keywords);
        $this->assign('page', $page->show());
        $this->display();
    }
    /**
     * 删除关键字
     */
    public function delKeyword()
    {
        $db=M('keywords');
        $db->in(array('keyword'=>$_POST['wd']))->del();
        echo 1;
        exit;
    }
    /**
     * 描红关键字
     */
    public function redkeyword()
    {
        $db=M('keywords');
        $db->in(array('keyword'=>$_POST['wd']))->update(array('red'=>$_GET['red']));
        echo 1;
        exit;
    }
    /**
     * 更改关键字次数
     */
    public function updateKeyword()
    {
        $db=M('keywords');
        foreach ($_POST['nums'] as $key => $value) {
            $db->where(array('keyword'=>$key))->update(array('nums'=>$value));
        }
        go('hotKeyword');
    }

    function validaterule() {
        $allRule = $this->web_config->getRule();
        $this->assign('allRule', $allRule);
        $this->display();
    }

    function addRule() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addRule'])) {
            $return = $this->web_config->addRule($_POST);
            $success = array("添加成功", 'validateRule', 1);
            $error = array("添加失败", 'validateRule', 2);
            $this->success_error($return, $success, $error);
        }
    }

    function editRule() {
        $condition = array(
            'vrid' => $_GET['vrid']
        );
        $result = $this->web_config->editRule($condition, $_POST);
        $success = array("修改成功", 'validateRule', 1);
        $error = array("修改失败", 'validateRule', 2);
        $this->success_error($result, $success, $error);
    }

    /**
     * 删除验证规则 
     */
    function delRule() {
        $vrid = $_GET['vrid'];
        $result = $this->web_config->delRule($vrid);
        $success = array("删除成功", 'validateRule', 1);
        $error = array("删除失败", 'validateRule', 2);
        $this->success_error($result, $success, $error);
    }

    

    //积分规则
    function pointRuleList() {
        $rules = $this->point_rule->ruleList();
        $this->assign('rules', $rules);
        $this->display();
    }

    //添加积分规则
    function addPointRule() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->point_rule->addPointRule($_POST);
            if ($result) {
                $this->success('添加积分规则成功', 'pointRuleList');
            }
        }
    }

    //删除积分规则
    function delPointRule() {
        if ($this->point_rule->point->del($_POST['id'])) {
            echo 1;
            exit;
        }
        echo 0;
        exit;
    }

    //修改积分规则
    function editPointRule() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->point_rule->editPointRule($_POST['id'], $_POST)) {
                $this->success('修改积分规则成功', 'pointRuleList');
            }
        }
        $rule = $this->point_rule->getPointRule($_GET['name'], NULL);
        $this->assign('rule', $rule);
        $this->display();
    }

    function siteNavigation() {
        $db = M('nav');
        $navs = $db->order('sort')->findall();
        $this->assign('navs', $navs);
        $this->display();
    }

    //添加导航
    function addNavigation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = M('nav');
            if ($db->insert()) {
                echo 1;
                exit;
            }
            echo 0;
            exit;
        }
    }

    function delNavigation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = M('nav');
            if ($db->del($_POST['id'])) {
                echo 1;
                exit;
            }
            echo 0;
            exit;
        }
    }

    //排序导航
    function sortNavigation() {
        $db=M('nav');
        if($_SERVER['REQUEST_METHOD']=='POST'){
            foreach ($_POST['sort'] as $key => $value) {
                $db->where('id='.$key)->update(array('sort'=>$value));
            }
            go('siteNavigation');
        }
    }
    //修改导航
    function editNavigation(){
        $db=M('nav');
        $con='id='.$_GET['id'];
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if($db->where($con)->update($_POST)>=0){
                $this->success('修改导航成功!','siteNavigation');
            }
        }
        $nav=$db->where($con)->find();
        $this->assign('nav',$nav);
        $this->display();
    }
    public function dbBackup()
    {
        //有POST先将要备份的表存到文件
        //然后go(__METH__.'/table/1/page/1')
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $created=time();
            $filename=PATH_ROOT.'/caches/db/'.$created;
            dir::create($filename);
            $tables=$_POST['table'];
            file_put_contents($filename.'/backup_tables.php','<?php return '.var_export($tables,true).';');
            $limit='/limit/500';
            $filesize='/filesize/2048000';
            if(isset($_POST['limit'])){
                $limit='/limit/'.$_POST['limit'];
            }
            if(isset($_POST['filesize'])){
                $filesize='/filesize/'.$_POST['filesize'];
            }
            go(__METH__.'/table/1/page/1/file/1/folder/'.$created.$limit.$filesize);
        }
        if(isset($_GET['table'])){
             $table_index=$_GET['table']-1;
             $db_backup = new DB_backup($_GET['filesize']);
             if($db_backup->backup($table_index)){
                $this->success('数据备份成功！','dbTool');
             }
         }
    }
    /**
     * 数据库工具 
     */
    function dbTool(){
        $db=M(NULL);
        $table=$db->query('SHOW TABLE STATUS');
        $this->assign('table',$table);
        $this->assign('dbSize',$db->get_size());
        $this->display();
    }
    
    //查看备份数据
    public function viewBackUp($value='')
    {
        $path=PATH_ROOT.'/caches/db/';
        $sql_file=dir::tree($path);
        $this->assign('sql_file',$sql_file);
        $this->display('dbRestore');
    }

    public function delBackUp()
    {
        foreach ($_POST['folder'] as $folder) {
            dir::del(PATH_ROOT.'/caches/db/'.$folder);
        }
        echo 1;
        exit;
    }
    /**
     * 数据恢复
     */
    public function dbRestore()
    {
        $link = mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PASSWORD')) or die ('MySQL连接失败 : ' . mysql_error());
        mysql_query("SET NAMES UTF8");
        mysql_select_db(C('DB_DATABASE'), $link) or die ('无法选择数据库 : ' . mysql_error());
        $path=PATH_ROOT.'/caches/db/'.$_GET['folder'];
        $sql=dir::tree($path,array('sql'));
        foreach ($sql as $value) {
            $content=file_get_contents($value['path']);
            $sql_item=array_filter(array_map('trim', explode('---------houdunwang-------------', $content)));
            if(!empty($sql_item)){
                foreach ($sql_item as $item) {
                    mysql_query($item);
                }
            }
        }
        $this->success('数据导入成功!','dbTool');
    }

    /**
     * 查看表结构
     */
    public function showCreateTable()
    {
        $db=M(NULL);
        $struct=$db->query('SHOW CREATE TABLE '.$_POST['table']);
        dump($struct[0]['Create Table']);
        exit;
    }
    /**
     * 操作数据表,优化，修复，查看表结构
     */
    public function optTable()
    {
        $db=M(NULL);
        switch($_GET['type']){
            case 'optimize':$sql='OPTIMIZE TABLE '.$_GET['table'];
            break;
            case 'repair':$sql='REPAIR TABLE '.$_GET['table'];
            break;
        }
        $db->query($sql);
        go('dbTool');
    }
    //友情链接
    function links(){
        $db=V('link');
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $_POST['created']=time();
            if($_FILES['logo']['error']==0){
                C('UPLOAD_IMG_DIR','');
                $upload=new upload('uploads/link',array('jpg','png','gif'),2465792);
                $up_info=$upload->upload();
                if($up_info){
                    $_POST['logo']=$up_info[0]['path'];
                }
            }
            if($db->table('link')->insert($_POST)){
                    $this->success('添加友情链接成功！');
            }
        }
        $db->view=array(
            'link_category'=>array(
                'type'=>'INNER',
                'on'=>'link.cate_id=link_category.lcid'
            )
        );
        $links=$db->findall();
        $cates=$db->table('link_category')->join(NULL)->findall();
        $this->assign('cates',$cates);
        $this->assign('links',$links);
        $this->display();
    }

    public function delLink()
    {
        $db=M('link');
        $db->del($_POST['id']);
        echo 1;
        exit();
    }
    //修改友链分类
    function editLinkCate(){
        $db=M('link_category');
        $db->where('lcid='.$_POST['lcid'])->update($_POST);
        go('links');
    }

    //添加友链分类
    public function addLinkCate()
    {
        $db=M('link_category');
        $db->insert($_POST);
        $this->success('添加友链分类成功！','links');
    }

    public function delLinkCate()
    {
        $db=M('link_category');
        $db->del($_POST['id']);
        echo 1;
        exit();
    }

}
