<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: mysqlDriver      10:58:56
 * @author               向军
 * Link                   http://www.houdunwang.com       
 * E-mail                 houdunwang@gmail.com
 */
load_file(PATH_HD . '/libs/usr/session/sessionAbstract.class.php');

class mysqlSessionDriver extends sessionAbstract {

    private $db; //数据库连接对象

    public function open() {
        $session_table = C("SESSION_TABLE_NAME");
        $this->db = M($session_table);
        return true;
    }

    public function destroy($id) {

        $id = addslashes($id);
        return $this->db->del("id='" . $id . "'");
    }

    public function gc() {
        $where = "atime<" . (time() - $this->session_lifetime);
        $this->db->where($where)->delete();
        $this->db->optimize();
        return TRUE;
    }

    public function read($id) {
        $id = addslashes($id);
        $data = $this->db->field("data,ip,atime")->cache(0)->find("sessid='" . $id . "' AND card='" . $this->card . "'");
        return $data['data'];
    }

    public function write($id, $data) {
        $id = addslashes($id);
        $card = $this->card; //SESSION令牌
        $replace_data = array(
            "sessid" => $id,
            "card" => $card,
            "data" => $data,
            "atime" => time(),
            "ip" => ip_get_client()
        );
        $this->db->replace($replace_data);
    }

}

?>
