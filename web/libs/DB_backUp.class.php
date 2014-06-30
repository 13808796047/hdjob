<?php
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

