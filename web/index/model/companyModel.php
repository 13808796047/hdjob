<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-7-24
 * Describe   : 
 */

class companyModel extends Model {

    public $company;
    public $recruit;
    public $interview;

    function __construct() {
        $this->company = M('company_info');
        $this->recruit = M('recruit');
        $this->interview = M('interview');
    }

    /**
     * 通过企业ID取得公司资料
     * @param type $condition 查找的条件,可以为企业ID，企业用户名，企业名称
     * @return array 企业资料return '你好';
     */
    function getCompanyDataById($company_id = '', $field = '') {
        if (empty($company_id)) {
            $company_id = $_SESSION['uid'];
        }
        $data = $this->company->field($field)->where('uid=' . $company_id)->find();
        return $data;
    }

    /**
     * 更新企业资料
     * @param type $data  更新资料
     * @param type $condition 更新条件
     */
    function updateCompanyData($data, $condition) {
        $result = $this->company->where($condition)->update($data);
        return $result;
    }

    /**
     * 获取企业发布的最新招聘列表
     * @param mixed $condition 查询条件
     * @param int $limit 数目
     * @param string $order 排序
     * @param string $field 字段
     * @return array 招聘信息列表
     */
    function newRecruit($condition = array(), $limit = 5, $order = 'start_time desc', $field = 'recruit_id,recruit_name,recruit_num,start_time,expiration_time,effective_time,verify') {
        if (empty($condition)) {
            $condition = array(
                'uid' => $_SESSION['uid']
            );
        }
        $data = $this->recruit->field($field)->where($condition)->order($order)->limit($limit)->findall();
        return $data;
    }

    /**
     * 企业所有的招聘信息 
     */
    function recruitList($condition = array(), $page_nums = 15, $order = 'start_time desc', $field = 'recruit_id,recruit_name,recruit_num,start_time,expiration_time,effective_time,state,verify,refresh_date') {
        if (empty($condition)) {
            $condition = array(
                'uid' => $_SESSION['uid']
            );
        }
        $nums = $this->recruit->where($condition)->count();
        $page = new page($nums, $page_nums); //传入总记录数，用于计算出分页
        $data = array();
        $db = R('recruit');
        $db->join=array(
            'spread'=>array(
                'type'=>'HAS_MANY',
                'foreign_key'=>'recruit_id',
                'parent_key'=>'recruit_id',
                'field'=>'cate_id,color',
                'where'=>'endtime > '.time(),//确定职位没有过期
                'other'=>true
            )
        );
        $data['data']=$db->field($field)->where($condition)->order($order)->findall($page->limit());
        $data['page'] = $page->show(); //显示分页页码
        return $data;
    }

    /**
     * 获取企业发布招聘的数目
     * @param type $cond 查询条件
     */
    function recruitNums($cond = array()) {
        if (empty($cond)) {
            $cond = array('uid' => $_SESSION['uid']);
        }
        return $this->recruit->where($cond)->count();
    }
    /**
     * 收到的职位申请数目
     */
    public function receiveNums($cond=array())
    {
        if (empty($cond)) {
            $cond = 'company_id='.$_SESSION['uid'];
        }
        return $this->interview->where($cond)->count();//收到的职位申请数目
    }
    /**
     * 企业操作日志
     */
    public function optLog($cond)
    {
        $db=M('opt_log');
        $nums=$db->where($cond)->count();
        $page=new page($nums,15);
        $logs=array();
        $logs['log']=$db->where($cond)->order('created desc')->findall($page->limit());
        $logs['page']=$page->show();
        return $logs;
    }
    function recruitItem($cond=array(),$field='recruit_name,recruit_id'){
        if(empty($cond)){
            $cond=array(
                'uid' => $_SESSION['uid'],
                'expiration_time'=>array(
                    'gt'=>time()
                    ),
            );
        }
        return $this->recruit->field($field)->where($cond)->order('refresh_date DESC,created DESC')->findall();
    }

}

