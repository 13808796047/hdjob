<?php 
  if(!defined('PATH_HD'))exit;
$cache_4b394ba0dd557ea697753da0fb14842c = Array
	(
	0=>Array
		(
		'field'=>'id',
		'type'=>'smallint(5) unsigned',
		'null'=>'NO',
		'key'=>1,
		'default'=>'',
		'extra'=>'auto_increment'
		),
	1=>Array
		(
		'field'=>'cate_name',
		'type'=>'varchar(35)',
		'null'=>'NO',
		'key'=>'',
		'default'=>'',
		'extra'=>''
		),
	2=>Array
		(
		'field'=>'cate_minday',
		'type'=>'smallint(5) unsigned',
		'null'=>'NO',
		'key'=>'',
		'default'=>10,
		'extra'=>''
		),
	3=>Array
		(
		'field'=>'cate_maxday',
		'type'=>'smallint(5) unsigned',
		'null'=>'NO',
		'key'=>'',
		'default'=>10,
		'extra'=>''
		),
	4=>Array
		(
		'field'=>'cate_point',
		'type'=>'int(10) unsigned',
		'null'=>'NO',
		'key'=>'',
		'default'=>3,
		'extra'=>''
		),
	5=>Array
		(
		'field'=>'max_nums',
		'type'=>'smallint(5) unsigned',
		'null'=>'YES',
		'key'=>'',
		'default'=>'',
		'extra'=>''
		),
	6=>Array
		(
		'field'=>'is_sys',
		'type'=>'tinyint(3) unsigned',
		'null'=>'NO',
		'key'=>'',
		'default'=>'0',
		'extra'=>''
		),
	7=>Array
		(
		'field'=>'state',
		'type'=>'tinyint(3) unsigned',
		'null'=>'NO',
		'key'=>'',
		'default'=>1,
		'extra'=>''
		),
	8=>Array
		(
		'field'=>'cate_desc',
		'type'=>'varchar(300)',
		'null'=>'YES',
		'key'=>'',
		'default'=>'',
		'extra'=>''
		)
	);
?>