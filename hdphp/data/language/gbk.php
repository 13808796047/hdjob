<?php
/**
 * Copyright              [HD���] (C)2011-2012 houdunwang ��Inc. 
 * Encoding               ${encoding} 
 * Version                $Id: {name}      ${date} ${time}
 * @author                ���
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
//���� ����(����)��__�����д����
if (!defined('PATH_HD'))
    exit;
return array(
    //������HDPHP/libs/boot/funtions.php
    'functions_k_is_file' => '��û�ж���ģ���ļ�',
    'functions_k_error' => 'ģ���ඨ����������������Model��β����һ���ֲ�ɡ���',
    'functions_control_error' => ' �������ļ���û�ж�����',
    'functions_load_file_is_file' => '�ļ������ڣ�����ʧ��',
    'functions_load_file_debug' => '�ļ�������',
    'functions_error_debug' => '�鿴��ϸ������Ϣ���������֣� �� �鿴��վ��־�ļ�  �� ��������ģʽ',
    //������HDPHP/libs/boot/control.php
    "control_error_msg" => "������",
    "control_success_msg" => "�����ɹ�",
    //HDPHP/HD.PHP
    //'hd_mkdirs_app_group'=> 'Ӧ����Ŀ¼����ʧ�ܣ����޸�Ȩ�ޣ�',
    'hd_mkdirs_path_temp' => '��ʱĿ¼����ʧ�ܣ����޸�Ȩ�ޣ�',
    'hd_mkdirs_path_log' => '��־Ŀ¼����ʧ�ܣ����޸�Ȩ�ޣ�',
    'hd_mkdirs_path_session' => 'SESSIONĿ¼Ŀ¼����ʧ�ܣ����޸�Ȩ�ޣ�',
    //HDPHP/libs/usr/db/driver/mysql.class.php
    'mysql_select_error' => 'û�пɲ��������ݱ�',
    'mysql_insert_error' => 'û���κ�����Ҫ����,ϵͳ�Ὣ$_POSTֵ�Զ����룬Ҳ�����ֶ������ݴ��������ORM��ʽ����鿴HD�ֲ�ѧϰ',
    'mysql_insert_error2' => '�������ݴ���ԭ�����Ϊ1����������Ϊ��   2���ֶ����Ƿ�����һ��HD����ֲ�ɣ�',
    'mysql_replace_error1' => 'û���κ�����Ҫ����,ϵͳ�Ὣ$_POSTֵ�Զ����룬Ҳ�����ֶ������ݴ��������ORM��ʽ����鿴HD�ֲ�ѧϰ',
    'mysql_replace_error2' => '�������ݴ���ԭ�����Ϊ1����������Ϊ��   2���ֶ����Ƿ�����һ��HD����ֲ�ɣ�',
    'mysql_update_error1' => 'û���κ�����Ҫ����,ϵͳ�Ὣ$_POSTֵ�Զ����£�Ҳ�����ֶ������ݴ��������ORM��ʽ����鿴HD�ֲ�ѧϰ',
    'mysql_update_error2' => 'UPDATE������������������,������������б�������ֶ�Ҳ������Ϊ����ʹ��',
    'mysql_update_error3' => '�������ݴ���ԭ�����Ϊ1����������Ϊ��   2���ֶ����Ƿ�',
    'mysql_delete_error' => 'DELETEɾ����������������,���ɾ�������б�������ֶ�Ҳ������Ϊ����ʹ�ã���������Ϳ�һ��HD�ֲ��',
    'mysql_where_error' => '�Ĳ���û�����ã���������ʹ�÷�ʽ��鿴HD�ֲ�ѧϰ',
    'mysql_in_error' => '�Ĳ�������Ϊ�գ���������ʹ�÷�ʽ��鿴HD�ֲ�ѧϰ',
    'mysql_field_error' => '�Ĳ�������Ϊ�գ���������ʹ�÷�ʽ��鿴HD�ֲ�ѧϰ',
    'mysql_limit_error' => '�Ĳ�������Ϊ�գ���������ʹ�÷�ʽ��鿴HD�ֲ�ѧϰ',
    'mysql_order_error' => '�Ĳ�������Ϊ�գ���������ʹ�÷�ʽ��鿴HD�ֲ�ѧϰ',
    'mysql_group_error' => '�Ĳ�������Ϊ�գ���������ʹ�÷�ʽ��鿴HD�ֲ�ѧϰ',
    //HDPHP/libs/usr/db/driver/mysqliDriver.php
    "mysqlidriver_connect_db" => '���ݿ����ӳ��������������ļ��еĲ���',
    //HDPHP/libs/usr/model/model.class.php
    "model__call_error" => 'ģ���в����ڷ���',
    "model_update_error" => '�����ˡ�����ִ��INSERT()����ʱ��û���κβ������ݣ����Խ�������Ϊinsert()�������룬ϵͳҲ�����Զ���$_POST����ȡ��������������ο�HD����ֲ�',
    "model_insert_error" => '�����ˡ�����INSERT��������Ϊ�գ�',
    "model_replace_error" => '�����ˡ�����INSERT��������Ϊ�գ�',
    "model_validate_error" => '��֤����������ǲ��Ǵ���ˣ�����ܰ����ֲ�ѧϰһ�°�',
    //HDPHP/libs/usr/model/driver/relationModel.class.php
    "relationmodel_check_error0" => "����ģ�Ͷ��������鿴���HD����ֲ�ѧϰ",
    "relationmodel_check_error1" => '����������ı��ģ������typeֵû�ж��壬��������ʹ�ù淶�������HD��ܰ����ֲ�',
    "relationmodel_check_error2" => '����������ı��ģ������typeֵ������HAS_ONE��HAS_MANY��BELONGS_TO��MANY_TO_MANY�е�һ���������ִ�Сд����������ʹ�ù淶�������HD��ܰ����ֲ�',
    "relationmodel_check_error3" => 'ʹ�ö�����MANY_TO_MANY�ı�û�ж���relation_table���Լ��м��������������ʹ�ù淶�������HD��ܰ����ֲ�',
    "relationmodel_check_error4" => 'ʹ�ö�����MANY_TO_MANY�ı�û�ж���relation_table_parent_key���ԣ���������ʹ�ù淶�������HD��ܰ����ֲ�',
    "relationmodel_check_error5" => 'ʹ�ö�����MANY_TO_MANY�ı�û�ж���relation_table_foreign_key���ԣ���������ʹ�ù淶�������HD��ܰ����ֲ�',
    "relationmodel_get_parent_key" => '������������ڣ��ֶ����������������ָ��ģ�͵�parent_keyֵ���ԣ������оͿ��ֲ�ѧϰһ�°ɣ��ܼ򵥵ģ�',
    "relationmodel_get_foreign_key1" => 'MANY_TO_MANY����ʧ�ܣ�2�ֽ�����������ù������������ָ��ģ�͵�foreign_keyֵ',
    "relationmodel_get_foreign_key2" => '�������ģ�ͱ���ָ��foreign_keyֵ����������ʹ�ù淶�������HD��ܰ����ֲ�',
    "relationmodel_select" => 'ģ�͵�parent_key���Զ������,���ܲ����ڴ��ֶ�,�������������в���parent_key�ֶ�',
    "relationmodel_insert" => '�����ˡ�����ִ��INSERT()ʱû���κβ������ݣ��������ݿ�����$_POSTҲ����ֱ�Ӵ���INSERT()�����У�HD����ֲ��ܰﵽ�㣡',
    "relationmodel_update" => '�����ˡ�����ִ��INSERT()ʱû���κβ������ݣ��������ݿ�����$_POSTҲ����ֱ�Ӵ���INSERT()�����У�HD����ֲ��ܰﵽ�㣡',
    //HDPHP/libs/usr/model/driver/viewModel.class.php
    "viewmodel_get_join_args1" => '������ͼ����ָ��ONֵ����������ʹ�ù淶�������HD��ܰ����ֲ�',
    "viewmodel_get_join_args2" => '��ͼģ�Ͷ���typeֵ�������type����Ϊleft, right, inner֮һ�����Բ�����typeֵ,�����ý�ʹ��INNER JOIN ���Ӳ�������������ʹ�ù淶�������HD��ܰ����ֲ�',
    //HDPHP/libs/usr/view/view.class.php
    //"view_getTemplateFile_error1"=>'ģ��Ŀ¼����Ϊ�գ����޸�������TPL_DIR',
    "view_getTemplateFile_error2" => 'ģ���ļ���չ������Ϊ�գ����޸�������TPL_FIX',
    "view_getTemplateFile_error3" => 'ģ���ļ�������',
    //HDPHP/libs/usr/view/hd/hdBaseTag.class.php
    "hdbasetag__zoom" => 'zoom��ǩ�������� pid��sid��data���ԣ����һ�¿��ĸ�û������',
    "hdbasetag__upload" => '�ϴ���ǩupload����ָ��name���ԣ���������ʹ�ù淶��鿴���HD����ֲ�',
    "hdbasetag__upload1" => 'foreach ģ���ǩ������from����',
    "hdbasetag__upload2" => 'foreach ģ���ǩ������value����',
    "hdbasetag__load" => 'foreach ģ���ǩ������value����',
    "hdbasetag__if" => 'if ģ���ǩ������value����',
    "hdbasetag__while" => 'whileģ���ǩ������value����',
    "hdbasetag__empty" => 'emptyģ���ǩ������value����',
    "hdbasetag__editor" => '�������ñ༭����name����,����$_POST����',
    //HDPHP/libs/boot/debug/debug.class.php
    "debug_show1" => '��������Ϣ',
    "debug_show2" => '�ͻ��˴���',
    "debug_show3" => 'PHP�汾',
    "debug_show4" => '������',
    "debug_show5" => '����ʽ',
    "debug_show6" => 'ͨ��Э��',
    "debug_show7" => '��ǰ�ű�',
    "debug_show8" => '�ỰID',
    "debug_show9" => 'ģ������ļ�',
    "debug_show10" => '˳��',
    "debug_show11" => 'SQL����',
    "debug_show12" => '������',
    "debug_show13" => '��SQL����',
    "debug_show14" => '�ű���ִ��ʱ��',
    "debug_show15" => '�ڴ��ֵ',
    "debug_show16" => 'ģ���ļ�',
    "debug_show18" => '�����ļ�',
    "_nohavedebugstart"=>"û�����õ��Կ�ʼ�㣺",
    //HDPHP/libs/boot/debug/exceptionHD.class.php
    "exceptionhd_getexception1" => '������Ϣ',
    "exceptionhd_getexception2" => '�ļ�',
    "exceptionhd_getexception3" => '�к�',
    "exceptionhd_show" => '�鿴��ϸ������Ϣ���������֣� �� �鿴��վ��־�ļ�  �� ��������ģʽ',
    "exceptionhd_error1" => '������Ϣ',
    "exceptionhd_error2" => '�ļ�',
    "exceptionhd_error3" => '�к�',
    "exceptionhd_error4" => '������Ϣ',
    "exceptionhd_error5" => '�ļ�',
    "exceptionhd_error6" => '�к�',
    "exceptionhd_notice" => 'NOTICE',
    //HDPHP/libs/boot/application.php
    "application_apprun1" => '������',
    "application_apprun2" => '�еķ���',
    "application_apprun3" => '������',
    #http://localhost/hdcms/setup/index.php?m=delcache&temp=D:/wamp/www/hdcms/temp
    "application_apprun4" => 'Ӧ�ò�����',
    "application_apprun_createapp"=>'�������Ӧ��',
    //HDPHP/libs/boot/debug/tpl/debug.html
    "debug_html_tplcompile" => 'ģ������ļ�',
    "debug_html1" => '���Ӧ��',
    "debug_html2" => '����RBAC',
    "debug_html3" => '����SESSION',
    "debug_html4" => '���ص��Խ���',
    "debug_html5" => 'ɾ����������',
    "debug_html6" => 'ϵͳ��Ϣ',
    "debug_html7" => '���ݿ����',
    "debug_html8" => '��������',
    "debug_html9" => '�������ʾ',
    "debug_html10" => "�����κ����ѣ����¼
            <a href='http://www.houdunwang.com'target='_blank'>�����</a>&nbsp;��&nbsp;
            <a href='http://bbs.houdunwang.com' target='_blank'>�����̳</a>&nbsp;Ҳ����&nbsp;
            <a href='mailto:houdunwangxj@gmail.com'>�ύHD��ܵ�BUG</a>",
    //HDPHP/libs/boot/debug/tpl/hd_error.html
    "hd_error_html1" => '�������ʾ',
    "hd_error_html2" => "�����κ����ѣ����¼<a href='http://www.houdunwang.com'target='_blank'>�����</a>
        &nbsp;��&nbsp;<a href='http://bbs.houdunwang.com' target='_blank'>�����̳</a>&nbsp;Ҳ����&nbsp;
        <a href='mailto:houdunwangxj@gmail.com'>�ύHD��ܵ�BUG</a>",
    //HDPHP/libs/boot/debug/tpl/hd_exception.html
    "hd_exception0" => '���HDPHP��ܡ�����Ŀ����',
    "hd_exception1" => '�����HD���-�쳣����',
    "hd_exception2" => '��������',
    "hd_exception3" => '���ݿ�',
    "hd_exception4" => '�������ʾ',
    "hd_exception5" => '�����κ����ѣ����¼<a href="http://www.houdunwang.com" target="_blank">�����</a>
                &nbsp;��&nbsp;<a href="http://bbs.houdunwang.com" target="_blank">�����̳</a>
                &nbsp;Ҳ����&nbsp;<a href="mailto:houdunwangxj@gmail.com">�ύHD��ܵ�BUG</a>',
    //HDPHP/libs/bin/cart.class.php
    "cart_add_error" => '���ﳵADD���������������²���һ������û������<br/>id��Ʒid��num��Ʒ������name��Ʒ����price�۸��뿴HD����ֲ�ѧϰʹ��',
    "cart_update_error" => '���ﳵupdate������������ȱ��sid��numֵ',
    "cart_del_error" => '���ﳵupdate������������ȱ��sidֵ',
    //HDPHP/libs/bin/html.class.php
    "html_create_error1" => '��ָ�������ļ�������[html_file]����ο����HD����ֲ�',
    "html_create_error2" => '����Ŀ¼ʧ�ܣ�����Ŀ¼Ȩ��',
    //HDPHP/libs/bin/page.class.php
    "page_nowpage" => '��',
    "page_count1" => '��',
    "page_count2" => 'ҳ',
    "page_count3" => '����¼',
    "page_show_case1" => '�ܼ�',
    //HDPHP/libs/bin/rbac.class.php
    "rbac_rbac_user_login1" => '�û������ô������������ļ�������û���',
    "rbac_rbac_user_login2" => '�û�������',
    "rbac_rbac_user_login3" => '�����������',
    "rbac_rbac_user_login4" => '�������κ��飬û�з���Ȩ��',
    //HDPHP/libs/bin/upload.class.php
    "upload_upload_error" => 'ͼƬ�ϴ�Ŀ¼����ʧ�ܻ򲻿�д',
    "upload_save_error1" => 'ͼƬ�ϴ�Ŀ¼����ʧ�ܻ򲻿�д',
    "upload_save_error2" => '�ƶ���ʱ�ļ�ʧ��',
    "upload_format_error" => 'û���κ��ļ��ϴ�',
    "upload_checkFile_error1" => '�ļ����Ͳ�����',
    "upload_checkFile_error2" => '�ϴ��ļ�����',
    "upload_checkFile_error3" => '�Ƿ��ļ�',
    "upload_error_error1" => '�ϴ��ļ�����PHP.INI�����ļ�����Ĵ�С',
    "upload_error_error2" => '�ļ����������ƴ�С',
    "upload_error_error3" => '�ļ�ֻ���в����ϴ�',
    "upload_error_error4" => 'û���ϴ��ļ�',
    "upload_error_error5" => 'û���ϴ���ʱ�ļ���',
    "upload_error_error6" => 'д����ʱ�ļ��г���',
    //HDPHP/libs/bin/validate.class.php
    "validate__maxlen1" => '��',
    "validate__maxlen2" => '��ֵ������Ϊ����',
    "validate__maxlen3" => '��֤�����maxlen�������ô��󣬱���Ϊ����',
    "validate__minlen1" => '��',
    "validate__minlen2" => '��ֵ����Ϊ����',
    "validate__minlen3" => '��֤�����maxlen�������ô��󣬱���Ϊ����',
    //HDPHP/data/language/success.html
    "success_html_title" => '�������ʾ���������ɹ�',
    "success_html_hd_error_html_h2" => '�����ɹ�!',
    "success_html_span1" => '���Ӻ󽫽���',
    "success_html_span2" => '��ת',
    "success_html_span3" => 'Ҳ����',
    "success_html_span4" => '������ҳ',
    //HDPHP/data/language/error.html
    "error_html_title" => '�������ʾ��������ʧ��',
    "error_html_hd_error_html_h2" => '����ʧ��!',
    "error_html_span1" => '���Ӻ󽫽���',
    "error_html_span2" => '��ת',
    "error_html_span3" => 'Ҳ����',
    "error_html_span4" => '������ҳ',
    //url.class.php
    "_nohaveapp"=>"��aΪӦ��get�����������ΪӦ������",
	#"_parse_route1"=>"������·��ð��ǰ������_=/�ȷ��������ֱ���",
);
?>