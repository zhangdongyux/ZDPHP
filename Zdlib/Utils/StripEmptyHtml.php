<?php
/**
 * HTML空标签过滤
 *
 * @author 陈文瑞
 * @version V1.0
 * @example
 * $str = "<a href='http://www.baidu.com'></a><p>test</p>";
 * echo Utils_StripEmptyHtml::strip_html($str);
 */
class Utils_StripEmptyHtml {
    /**
     * 过滤HTML中的空标签
     *
     * @param string $str	欲过滤字符串
     * @return string	    过滤后字符串
     */
    public static function strip_html($str) {
        $str = preg_replace("/<a[^>]*>([\s]?)*<\/a>/i", '', $str);
        $str = preg_replace("/<p[^>]*>([\s]?)*<\/p>/i", '', $str);
//         $str = preg_replace("/<div[^>]*>([\s]?)*<\/div>/i", '', $str);
        $str = preg_replace("/<span[^>]*>([\s]?)*<\/span>/i", '', $str);
        $str = preg_replace("/<font[^>]*>([\s]?)*<\/font>/i", '', $str);
//         $str = preg_replace("/<b[^>]*>([\s]?)*<\/b>/i", '', $str);
        $str = preg_replace("/<b([\s][^>]*)?>[\s]*<\/b>/i", '', $str);
        $str = preg_replace("/<b([\s][^>]*)?>[\s]*<\/b>/i", '', $str);
        $str = preg_replace("/<strong><br[\s]*.*?><\/strong>/i", '', $str);
        
        return $str;
    }
}
