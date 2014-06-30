<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: hdFunctionTag.php      2012-2-6  16:36:09
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
class compile extends HDPHP {

    private $assignVar; //assign分配的变量
    private $const; //用户常量
    private $tplFile; //模板文件
    private $compileFile; //模板缓存文件
    public $content; //编译内容
    private $left; //模板左标签
    private $right; //模板右标签
    private $condition = array(
        "\s+neq\s+" => " <> ", "\s+eq\s+" => " == ",
        "\s+gt\s+" => " > ", "\s+egt\s+" => " >= ",
        "\s+lt\s+" => " < ", "\s+elt\s+" => " <= ",
        "\s+=\s+" => "=="
    );

    /**
     *
     * @param type $tplFile     模板文件
     * @param type $compileFile 模板编译文件
     * @param type $assignVar   assign的变量
     */
    function __construct($tplFile, $compileFile, $assignVar) {
        $const = get_defined_constants(true); //获得常量
        $this->const = $const['user']; //获得用户定义常量
        $this->tplFile = $tplFile; //模版文件
        $this->assignVar = $assignVar; //assign分配的变量
        $this->compileFile = $compileFile; //编译文件
        $this->left = C("TPL_TAG_LEFT"); //左侧标签
        $this->right = C("TPL_TAG_RIGHT"); //右侧标签
    }

    //运行编译
    public function run() {
        $this->content = file_get_contents($this->tplFile); //获得模板内容
        $this->replaceAssignVar();
        $this->loadParseTags(); //加载标签库  及解析标签
        $this->compile(); //解析全局内容
        $this->replaceConst(); //将所有常量替换   如把__APP__进行替换
        $this->content = '<?php if(!defined("PATH_HD"))exit;?>' . "\r\n" . $this->content;
        if (!is_dir(CACHE_COMPILE_PATH)) {
            dir::create(CACHE_COMPILE_PATH);
        }
        file_put_contents($this->compileFile, $this->content);
    }

//assign变量加后缀
//    private function replaceAssignVar() {
//        $vars = array();
//        foreach ($this->assignVar as $k => $v) {
//            $vars[] = '/\$' . substr($v, 0, -1) . '(?![_\w])/i'; //去掉右连的_线
//            $this->assignVar[$k] = '$' . $v;
//        }
//        $this->content = preg_replace($vars, $this->assignVar, $this->content);
//    }

    /**
     * 加载标签库与解析标签 
     */
    private function loadParseTags() {
        $tagClass = array(); //标签库类
        $tags = C('TPL_TAGS'); //加载扩展标签库
        if (!empty($tags)) {//如果配置文件中存在标签定义
            $tags = preg_split('/[,\s，;]/', $tags); //得到所有标签
            foreach ($tags as $k) {//加载其他模块或应用中的标签库
                $arr = preg_split('/\/|\./', $k); //如果拆分后大于1的为其他模块或应用的标签定义
                $count = count($arr);
                $tagClass[] = $arr[$count - 1]; //压入标签库类
                if (class_exists($arr[$count - 1])) {
                    continue;
                }
                if ($count > 1) {
                    switch (count($arr)) {
                        case 2:
                            $tagFile = PATH_APP_GROUP . '/' . $arr[0] . '/libs/' . $arr[1] . '.class.php';
                            break;
                        case 3:
                            $tagFile = PATH_APP_GROUP . '/' . $arr[0] . '/' . $arr[1] . '/libs/' . $arr[1] . '.class.php';
                            break;
                    }
                    if (!file_exists($tagFile)) {
                        error("标签库文件" . $tagFile . "不存在");
                    }
                    loadFile($tagFile);
                }
            }
        }
        load_file(PATH_HD . '/libs/usr/view/hd/hdBaseTag.class.php'); //加载基标签库
        $tagClass[] = 'hdBaseTag';
        $this->parseTagClass($tagClass);
    }

    /**
     * 解析所有标签
     * @param array $tagClass    所有标签类
     */
    private function parseTagClass($tagClass) {
        foreach ($tagClass as $class) {
            $tagObj = new $class(); //标签类对象
            $tagMethod = get_class_methods($class); //标签库中的标签方法
            foreach ($tagMethod as $tagName) {
                $block = 1; //是否为块标签  默认为块标签
                $level = 1; //嵌套层级
                $tagName = substr($tagName, 1); //标签名，因为标签类中以_开始，所以要截取
                if (isset($tagObj->tag)) {//标签属性是否存在
                    $tagSet = $tagObj->tag; //标签设置
                    $block = isset($tagSet[$tagName]['block']) ? $tagSet[$tagName]['block'] : 1; //检测是否为块标签
                    $level = isset($tagSet[$tagName]['level']) ? $tagSet[$tagName]['level'] : 1; //嵌套层级
                }
                for ($i = 0; $i < $level; $i++) {
                    if (!$this->compileTag($tagName, $tagObj, $block)) {
                        $i = 100;
                    }
                }
            }
        }
    }

    /**
     * 编译标签
     * @param string $tagName       标签名
     * @param type $tagObj             标签类对象
     * @return type  $block             是否为块标记 
     */
    private function compileTag($tagName, &$tagObj, $block = 1) {
        $arr = ''; //标签内容  所有含属性 及内容
        if ($block) {
            #<if>
            #<if value="abc"
            $preg = '/' . $this->left . $tagName . "(?:\s+(.*)" . $this->right . "|".$this->right.")(.*)" . $this->left . "\/" . $tagName . $this->right . '/isU'; //对标签正则
        } else {
            $preg = '/' . $this->left . $tagName . '[\s\/>]*(.*)\/?' . $this->right . "/isU"; //独立正则
        }
        $stat = preg_match_all($preg, $this->content, $arr, PREG_SET_ORDER); //找到所有当前标签名的内容区域

        if (!$stat) {//模板中不存在当前标签 返回
            return false;
        }

        foreach ($arr as $k) {
            $k[1] = $this->replaceCondition($k[1]); //替换GT LT等
            $attr = $this->getTagAttr($k[1]); //属性数组
            $k[2] = isset($k[2]) ? $k['2'] : ''; //内容部分
            $content = call_user_func_array(array($tagObj, '_' . $tagName), array($attr, $k[2], array($k[1], $k[2])));
            $this->content = str_replace($k[0], $content, $this->content);
        }
        return true;
    }

    /**
     * 获得标签所有属性  组成为数组
     * @param type $attrCon         标签属性行文本内容
     * @param type $tagName         标签名如foreach
     */
    private function getTagAttr($attrCon) {
        $pregAttr = '/\s*' . '(\w+)\s*=\s*(["\'])(.*)\2/iU'; //属性正则
        $attrs = ''; //属性集合字符串
        preg_match_all($pregAttr, $attrCon, $attrs, PREG_SET_ORDER);
        $attrArr = array();
        foreach ($attrs as $k) {
            $k[3] = trim($this->parsePhpVar($k[3])); //格式化变量
            $attrArr[$k[1]] = strstr($k[3], '$') ? $k[3] : (is_numeric($k[3]) ? $k[3] : (defined($k[3]) ? $k[3] : $k[3] )); //格式化规则属性
        }
        return array_change_key_case($attrArr);
    }

    /**
     * 统一解析变量如$_GET $_POST $hd
     * @param type $content
     * @param type $type 
     */
    private function parseVar($content) {
        $content = stripslashes($content);
        $content = $this->parseConst($content);
        $content = $this->parsePhpVar($content, 1);
        return $content;
    }

    /**
     * 替换连接标题  如gt 替换 >
     * @param type $attr 
     */
    private function replaceCondition($content) {
        foreach ($this->condition as $k => $v) {
            $content = preg_replace("/$k/", $v, $content);
        }
        return $content;
    }

    /**
     * 将内容中的所有PHP变量进行格式化
     * @param type $content   要解析的内容 里面有变量或没有
     */
    # $b.c     $b|date:y-m-d h:i:s,@@
    private function parsePhpVar($content, $type = 0) {
        $content = $this->removeEmpty($content); //去除变量空格
        if ($type == 0) {
            $preg = '/([\'\"]?)(\$[^=!<>\s\)\(]+)\1/is'; //得到所有变量表示如$c.a.d|date:"y-m-d",@@
        } else {
            $preg = '/([\'\"]?)(\$[^=!<>\)\(]+)\1/is'; //得到所有变量表示如$c.a.d|date:"y-m-d",@@    
        }
        $vars = false; //内容中的变量数组
        preg_match_all($preg, $content, $vars, PREG_SET_ORDER);
        if (empty($vars)) {
            return $content;
        }
        foreach ($vars as $v) {
            $v[2] = trim($v[2]); //清除空格 
            $content = str_replace($v[2], $this->formatVar($v[2]), $content);
        }
        return $content;
    }

    /**
     * 去除变量中的多余空格如 date | "y-m-d"中的|左右空格就要清除
     */
    private function removeEmpty($content) {
        $content = preg_replace('/\$hd.(\w+)\./ise', '\'\$_\'.strtoupper("\1").".";', $content);
        $preg = array(
            '/[{}]/',
            '/\s*\|\s*/',
            '/\s*:\s*/',
            '/\s*,\s*/',
        );
        $replace = array(
            '',
            '|',
            ':',
            ',',
        );
        return preg_replace($preg, $replace, $content); //将变量空格删除，否$a | date等带空格的不能识别 
    }

    /**
     * 将一个PHP变量进行格式化  组合成函数或普通变量的形式
     * @param type $var         一定要是模版表示的变量内容字符串
     */
    private function formatVar($var) {
        $varArr = preg_split("/\s*\|\s*/", $var); //通过|拆分数组  如果有函数
        $var = array_shift($varArr); //变量名
        $func = $varArr; //函数数组
        $preg = array(
            "/\.\'/",
            "/'\./",
            '/\."/',
            '/"\./',
            '/{/',
            '/}/',
        );
        $replace = array(
            "/\./",
            "/\./",
            '/\./',
            '/\./',
            '/{/',
            '/}/',
        );
        $con = preg_replace($preg, $replace, $var); 
        //将变量字符串组合成以.进行分隔的字符串
        $var = explode('.', $con);
        //得到变量名
        $varName = array_shift($var); 
        $varStr = ''; //变量字符串
        if (count($var) > 0) {
            foreach ($var as $v) {
                $varStr.=is_numeric($v) || strstr($v, '$') ? "[{$v}]" : '[\'' . $v . '\']';
            }
        }
        $varName.=str_replace("]'", "']", $varStr);
        if (!empty($func)) {
            if (!function_exists("replaceyinhao")) {
                function replaceyinhao($con) {
                    return "'" . str_replace(":", "####", $con[2]) . "'";
                }

            }
            foreach ($func as $function) {
                //将内容中的:替换为####以免与标签中的:产生二义
                $function = preg_replace_callback('/(\'|")(.*)\1/i', "replaceyinhao", $function); 
                $funcArr = explode(":", $function); //拆分函数  是否有参数
                $funcName = array_shift($funcArr); //函数名称
                if (isset($funcArr[0])) {//检测函数是否存在参数
                    if (strstr($funcArr[0], "@@")) {
                        $varName = str_replace("@@", trim($varName, ','), $funcArr[0]); //将@@替换为变量
                    } else {
                        $varName = trim($varName, ',') . ',' . $funcArr[0];
                    }
                }
                $varName = str_replace("####", ":", $varName);
                $varName = $funcName . '(' . trim($varName, ',') . '),';
            }
        }
        $varName = trim($varName, ',');
        return $varName;
    }

    /**+
     * 将变量或常量进行替换
     * @return boolean 
     */
    private function compile() {
        $preg = '/{\s*(\$[^=!<>\)\(]+)}/ieU'; //以{$或$开头的进行解析处理
        $this->content = preg_replace($preg, '\'<?php echo \'. $this->parseVar(\'\1\').\';?>\';', $this->content);
    }

    /**
     * 系统常量解析
     * @param type $content     模板内容
     * @param int    $type          1加php  0 不加
     */
    private function parseConst($content) {
        $preg = '/\$hd.const.([^=!<>]*)/ise';
        $replace = '"\1"';
        return preg_replace($preg, $replace, $content);
    }

    /**
     * 将全局常量进行解析替换 
     */
    private function replaceConst() {
        foreach ($this->const as $k => $v) {
            if (!strstr($k, '__'))
                continue;
            $this->content = str_replace($k, $v, $this->content);
        }
    }

}

?>