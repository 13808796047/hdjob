<?php

/**
 * Copyright              [HD框架] (C)2011-2012 后盾网，Inc. 
 * Encoding               UTF-8
 * Version                $Id: xml      18:25:36
 * @author               向军
 * Link                   http://www.houdunwa   ng.com       
 * E-mail                 houdunwang@gmail.com
 */
class xml {

    private $xml_data; //xml数组数据

    private function compile($xml) {
        if (is_file($xml))
            $xml = file_get_contents($xml);
        $xmlRes = xml_parser_create('utf-8');
        xml_parser_set_option($xmlRes, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($xmlRes, XML_OPTION_CASE_FOLDING, 0);
        xml_parse_into_struct($xmlRes, $xml, $arr, $index);
        xml_parser_free($xmlRes);

        return $arr;
    }

    /**
     *
     * @param array $data       数据
     * @param string $root      根据节点
     * @param string $encoding  编码
     * @return string           XML字符串 
     */
    public function xml_create($data, $root = null, $encoding = "UTF-8") {
        $xml = '';
        $root = is_null($root) ? "root" : $root;
        $xml.= "<?xml version=\"1.0\" encoding=\"$encoding\"?>";
        $xml.="<$root>";
        $xml.=self::format_xml($data);
        $xml.="</$root>";
        return $xml;
    }

    protected function format_xml($data) {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        $xml = '';
        foreach ($data as $k => $v) {
            if (is_numeric($k)) {
                $k = "item id=\"$k\"";
            }
            $xml.="<$k>";
            if (is_object($v) || is_array($v)) {
                $xml.=self::format_xml($v);
            } else {
                $xml.=str_replace(array("&", "<", ">", "\"", "'", "-"), array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"), $v);
            }
            list($k, ) = explode(" ", $k);
            $xml.= "</$k>";
        }
        return $xml;
    }

    /**
     * 将XMLL字符串或文件转为数组
     * @param string $xml   XML字符串或XML文件
     * @return array        解析后的数组
     */
    public function xml_to_array($xml) {
        $arrData = self::compile($xml);
        $arr = array();
        $k = 1;
        return $arrData ? self::get_data($arrData, $k) : false;
        return $arr;
    }

//解析编译后的内容为数组
    private function get_data($arrData, &$i) {
        $data = array();
        for ($i = $i; $i < count($arrData); $i++) {
            $name = $arrData[$i]['tag'];
            $type = $arrData[$i]['type'];
            switch ($type) {
                case "attributes":
                    $data[$name]['att'][] = $arrData[$i]['attributes'];
                    break;
                case "complete"://内容标签
                    $data[$name] = $arrData[$i]['value'];
                    break;
                case "open"://块标签
                    $k = isset($data[$name]) ? count($data[$name]) : 0;
                    $data[$name][$k] = self::get_data($arrData, ++$i);
                    break;
                case "close":
                    return $data;
            }
        }
        return $data;
    }

}

?>