<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-9-10
 * Describe   : 
 */

class searchControl extends Control {

    function index() {//搜索职位
        //处理GET参数
        $keywords = array();
        $db = M('keywords');
        //处理关键字
        if (isset($_GET['keywords'])) {
            $_GET['keywords']=urldecode(strip_tags($_GET['keywords']));
            $keywords = array_keys(string::split_word($_GET['keywords']));
            if (!empty($_GET['keywords'])) {
                //将关键字插入数据库
                foreach ($keywords as $value) {
                    $has = $db->table('keywords')->where("keyword='$value'")->find();
                    if (!$has) {
                        $db->table('keywords')->insert(array('keyword' => $value));
                    }
                    $db->table('keywords')->inc('nums', "keyword='$value'", 1); //再更新nums
                }
            }
        }
        $condition = array_map('intval', getCleanUriArg(array('keywords', C('PAGE_VAR'))));
        //处理时间条件
        if (isset($condition['refresh_date'])) {
            $condition['refresh_date'] = array(
                'lt' => time(),
                'gt' => strtotime('-' . $condition['refresh_date'] . 'days')
            );
        }
        if (!empty($keywords)) {
            $keyword_cond = '(';
            foreach ($keywords as $value) {
                $keyword_cond.='recruit_name LIKE "%' . $value . '%" OR ';
            }
            $keyword_cond = rtrim($keyword_cond, 'OR ');
            $condition[] = $keyword_cond . ')';
        }
        //职位附加条件
        $condition[] = 'expiration_time >' . time();//未过期
        $condition[]='state=1';//已开启
        $condition[]='verify=1';//审核成功
        //查找职位信息
        $field = 'recruit.recruit_id,recruit_name,company_name,jobs_property,address,city,town,refresh_date,work_exp,company_property,company_scope,salary,degree,seo_desc,recruit.uid';
        $lnum = 10; //每页显示数量
        $now_page = isset($_GET[C('PAGE_VAR')]) ? $_GET[C('PAGE_VAR')] : 1;
        $spreads = array();
        if ($now_page <= 1) {
            $db = V('recruit');
            $db->view = array(
                'spread' => array(
                    'ON' => 'recruit.recruit_id=spread.recruit_id',
                    'type' => 'INNER'
                )
            );
            $spreads = $db->field($field, 'cate_id,color')->where($condition)->order('cate_id DESC')->findall();
        }
        $db = M('recruit');
        $count = $db->field($field)->where($condition)->count();
        $page = new page($count, $lnum);
        $jobs = $db->field($field)->where($condition)->findall($page->limit());
        $page->show();
        //处理选项和联动数据为具体值
        $convert = new data;
        if ($jobs) {
            foreach ($jobs as $key => $value) {
                $jobs[$key] = $convert->convert($value);
            }
        }
        if ($spreads) {
            foreach ($spreads as $key => $value) {
                $spreads[$key] = $convert->convert($value);
            }
        }

        $db = M('model_field');
        $linkages = $db->field('title,lcgid,field_name')->where('join_index=1 and field_type="linkage" and dmid=5')->findall();

        //处理选项条件
        $switchs = array();
        foreach ($convert->fields as $key => $value) {
            if ($value['join_index'] && $value['field_type'] == 'switch') {
                $switchs[$key]['title'] = $value['title'];
                $switchs[$key]['switch'] = $value['data'];
            }
        }

        $keywords_replace = array();
        foreach ($keywords as $value) {
            $keywords_replace[] = '<span style="color:#F00">' . $value . '</span>';
        }
        $this->assign('filterCond',$this->_getFilterCond());
        $this->assign('switchs', $switchs);//选项条件
        $this->assign('keywords', $keywords);//关键字
        $this->assign('keywords_replace', $keywords_replace);//替换的关键字
        $this->assign('jobs', $jobs);
        $this->assign('spreads', $spreads);
        $this->assign('linkages', $linkages);
        $this->assign('page',$page->show());//页码
        $this->display('search');
    }

    /**
     * 获取筛选条件
     */
    function _getFilterCond(){
        $filterCond=array();
        $db=M('city');
        $filterCond['address']=$db->cache(86400)->field('id,name')->where('pid=0')->findall();//地区
        if(isset($_GET['address'])){
            $filterCond['sonAddress']=$db->cache(86400)->field('id,name')->where('pid='.intval($_GET['address']))->findall();//地区子类
        }
        $filterCond['industry']=$db->table('linkage')->cache(86400)->field('laid,title')->where('lcgid=3 AND pid!=0')->findall();//职位行业
        $need_class=array();
        $class_level_one=$db->cache(86400)->table('linkage')->field('laid')->where('lcgid=4 AND pid=0')->findall();//职位分类
        foreach ($class_level_one as $value) {
            $need_class[]=$value['laid'];
        }
        $filterCond['jobClass']=$db->table('linkage')->cache(86400)->field('laid,title')->where('lcgid=4')->in(array('pid'=>$need_class))->findall();//职位分类
        if(isset($_GET['class'])){
            $filterCond['sonJobClass']=$db->table('linkage')->cache(86400)->field('laid,title')->where('lcgid=4 AND pid='.$_GET['class'])->findall();//分类子类
        }
        return $filterCond;
    }

    function jobs() {//职位内容页
        $id = intval($_GET['id']);
        $db = M('recruit');
        $verify=' AND verify=1';
        if(in_array(1,$_SESSION['role']['rid']) or in_array(3,$_SESSION['role']['rid'])){
            $verify='';
        }
        $cond='recruit_id=' . $id.' AND state=1'.$verify;
        $job = $db->where($cond)->find();
        if(!$job){
            $this->error('对不起，没有该职位的信息！');
        }
        $db->inc('views',$cond,1);
        $data = new data('recruit');
        $job = $data->convert($job);
        $job['job_desc']=htmlspecialchars_decode($job['job_desc']);
        $this->assign('job', $job);
        $this->display('jobs');
    }

    //搜索简历
    public function resume()
    {
        $auth = new auth;
        if (!$auth->is_logged_in()) {
            $this->error(L('please_login'), 'auth/index');
        }
        if (!$auth->check_uri_permissions()) {
            $this->error($auth->error);
        }

        $db=M('model_field');
        $filterCond=array();
        //处理选项搜索字段
        $switch_field=$db->field('title,field_name')->where('field_type="switch" and join_index=1 and dmid=6')->findall();
        $model_struct=include PATH_ROOT.'/caches/model/field/m_resume_basic.php';
        foreach ($switch_field as $value) {
            $filterCond['switchs'][$value['field_name']]=$model_struct[$value['field_name']]['data'];
        }

        //处理选项联动搜索字段
        $linkage_filed=$db->field('title,field_name,lcgid')->where('field_type="linkage" and join_index=1 and dmid=6')->findall();
        foreach ($linkage_filed as $value) {
            $data=$db->table('linkage')->cache(86400)->field('laid,title')->where('lcgid='.$value['lcgid'])->findall();;
            $filterCond['linkages'][$value['field_name']]=array('title'=>$value['title'],'data'=>$data);
        }

        //处理选项地区搜索字段
        $filterCond['address']=$db->table('city')->cache(86400)->field('id,name,direct')->where('pid=0')->findall();//地区
        if(isset($_GET['address'])){
            $filterCond['sonAddress']=$db->table('city')->cache(86400)->field('id,name')->where('pid='.$_GET['address'])->findall();//地区子类
        }

        $where=array('open'=>1,'verify'=>1);//resume表的条件：公开已验证
        if(isset($_GET['address'])){//地址
            $where[]='hope_provice='.intval($_GET['address']);
        }
        if(isset($_GET['city'])){//地址
            $where[]='hope_city='.intval($_GET['city']);
        }
        if(isset($_GET['work_exp'])){//工作经验
            $where[]='work_exp='.intval($_GET['work_exp']);
        }
        if(isset($_GET['updated'])){//更新时间
            $where['updated']='updated >'.strtotime('-'.$_GET['updated'].'days');
        }
        $keywords=array();
        if(!empty($_GET['keywords'])){//关键字
            $_GET['keywords']=strip_tags($_GET['keywords']);
            $keywords=array_keys(string::split_word($_GET['keywords']));
            $keyword_cond = '';
            foreach ($keywords as $value) {
                $keyword_cond.='resume_name LIKE "%' . $value . '%" OR ';
            }
            $keyword_cond = rtrim($keyword_cond, 'OR ');
            $where[]=$keyword_cond;
        }
        $db=V('resume');
        $db->view=array(
            'resume_basic'=>array(
                'type'=>'INNER',
                'on'=>'resume.resume_id=resume_basic.resume_id',
            )
        );
        $nums=$db->where($where)->count();
        $page=new page($nums,10);
        $resumes=$db->where($where)->findall($page->limit());
        if($resumes){
            $data_class=new data('resume_basic');
            foreach ($resumes as $key => $value) {
                $resumes[$key]=$data_class->convert($value);
            }
        }
        $this->assign('resumes',$resumes);
        $this->assign('filterCond',$filterCond);
        $this->assign('page',$page->show());
        $this->display('search-resume');
    }

}