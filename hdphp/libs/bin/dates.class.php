<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: dates.class.php      2012-1-9  22:05:24
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
final class dates {
    private $runtime = array(); //运行时间 
    /**
     * 运行时间 
     * @param type $start       开始标记
     * @param type $end         结束标记
     * @param type $decimals   运行时间的小数位数
     * @return type 
     */

    static function runtime($start, $end = '', $decimals = 3) {
        if ($end != '') {
            self::$runtime [$end] = microtime();
            return number_format(self::$runtime [$end] - self::$runtime [$start], $decimals);
        }
        $runtime [$start] = microtime();
    }

}

?>
