<?php
/**
 * utf-8和GBK字符串截取函数
 * 
 * 过滤过程中自动去除HTML代码与HTML实体字符
 * 
 * @package Utils
 * @access public
 * @param mixed $str 需过滤字符串
 * @param mixed $start 开始位置 默认0
 * @param mixed $length 截取长度 默认0
 * @param mixed $charset 编码 默认utf-8
 * @return string 截取后的的字符串
 * @author 张东宇
 * @version 1.0
 */

class Utils_Strcut
{
	function Strcut($str,$length=0,$start=0,$charset="utf-8",$suffixAuto=0)
	{
		$str=eregi_replace("&[a-zA-Z]*;",'',strip_tags($str));
		$charset=strtolower($charset);
		if($length==0)
			$length=$this->mc_strlen($str);
	   	if(function_exists("mb_substr"))
		{
			$slice=mb_substr($str, $start, $length, $charset);
				if($suffixAuto&&mb_strlen($str,$charset)>$length)
					return $slice; 
			else
				return $slice;
		}
		else{
			 $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";   
			 $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";  
			 $re['gbk']   = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";  
			 $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";   
			 preg_match_all($re[$charset], $str, $match);  
			 $slice = join("",array_slice($match[0], $start, $length)); 
			 if($suffixAuto&&mc_strlen($str,$charset)>$length)
					return $slice; 
			 else
				return $slice;
		}
	}
	
	function mc_strlen($str,$charset='utf-8')
	{
		if($charset == 'utf-8')
			return $this->strlen_utf8($str);
		else
			return $this->strlen_gb($str);
	}
	
	function strlen_utf8($str)
	{
		$i = 0;
		$count = 0;
		$len = strlen ($str);
		while ($i < $len)
		{
			$chr = ord ($str[$i]);
			$count++;
			$i++;
			if($i >= $len)
				break;
			if($chr & 0x80)
			{
				$chr <<= 1;
				while ($chr & 0x80)
				{
					$i++;
					$chr <<= 1;
				}
			}
		}
		return $count;
	} 
	
	function strlen_gb($str){
	    $count = 0;
	    for($i=0; $i<strlen($str); $i++){
	        $s = substr($str, $i, 1);
	        if (preg_match("/[\x80-\xff]/", $s)) ++$i;
	        ++$count;
	    }
	    return $count;
	}
}

//$str="<a href='123.com'>中华人民共和国&nbsp;&;</a><img src='123'></img>";
//echo str_filter($str,0,7,7);
//echo mc_strlen('aaa张东宇');
?>
