<?php
/**
 * 接口安全令牌类
 * 实现原理:
 * 客户端签名算法：md5(url除签名字段外正序排列+客户端分配密钥(可加salt))
 * 注意：
 * 1.服务端接口请求有效期限制默认一分钟
 * 2.客户端按照bcdiv(time(), $this->ltime)*$this->ltime,传入时间
 * 3.URL中附带debug=1可打印详细信息
 * @example
 * 
    echo '客户端：'.time().'<br>';
    $time = bcdiv(time(), 60)*60; //客户端传入时间,需客户端算好,此处仅为示例
    $sign = $_GET['sign'];//客户端签名字符串,需客户端算好，此处仅为示例
    echo '<br>传入程序时间：'.$time.'<br>';
    $dao = new Utils_AppToken($time,'xxxxx',$_GET['sign']);//词句为签名验证
    $flag = $dao->vaildSign();
    print_r( $flag);

 * @author zdy 2016.11
 */

class Utils_AppToken
{
    private $time = '';//客户端传入的当前系统时间
    private $keyId = '';//keyId,客户端分配Key标示
    private $keyStore = array('android'=>'xxxxx','iPhone'=>'yyyyy'); //密钥库
    private $sign = '';//客户端传入的签名字符串
    private $ltime = '60';//限制同一链接请求有效时长，默认60秒

    /**
     * 构造函数
     * @param unknown $time 客户端请求时间戳
     * @param unknown $keyId 客户端分配keyId
     * @param unknown $sign  客户端签名字符串
     */
    public function __construct($time,$keyId,$sign)
    {
        //初始化
        $this->time = $time;
        $this->keyId = $keyId;
        $this->sign = $sign;
    }

    /**
     * 签名验证
     * 对sign进行签名验证
     * 前台后规则相同
     * md5(查询字符串正序排列+key),然后与客户端签名对比验证
     * 注意：接口请求有效期为：$ltime，客户端控制，服务端验证
     * @return multitype:string |boolean
     */
    public function vaildSign()
    {
        //         $timeLimit = bcdiv($this->time, $this->ltime)*$this->ltime; //客户端时间除以60向下取整，再乘以60，用来限制一分钟内请求
        $timeLimit = $this->time;//考虑到缓存问题，接口请求时间在客户端按照以上算法算好传入，如不考虑缓存，使用以上语句
        $systemTimeLimit = bcdiv(time(), $this->ltime)*$this->ltime;//系统客户端时间除以60向下取整，再乘以60，用来限制一分钟内请求

        if($systemTimeLimit != $timeLimit)//请求超出时间范围
            return array('status'=>'-1','messate'=>'请求已过期');
        $parseUrl = $this->parseUrl();
        if($parseUrl['flag']==-1)
            return array('status'=>'-2','messate'=>'url解析错误');
        else
        {

            /*系统端签名,规则：md5(查询字符串正序排列+key),然后与客户端签名对比验证*/
            $systemSign = md5($parseUrl['url'].$this->keyId);
            if($_GET['debug']==1)
            {
                echo '客户端带入时间:'.$this->time.'<br>';
                echo '服务端系统时间:'.$systemTimeLimit.'<br>';
                echo '<br>参与加密URL:'.$parseUrl['url'];
                echo '<br>加密字符串:'.$parseUrl['url'].$this->keyId;
                echo '<br>客户端sign:'.$this->sign;
                echo '<br>系统端sign:'.$systemSign;
                echo '<br>验证结果:'.($systemSign == $this->sign);
                echo '<hr>';
            }

            if($systemSign == $this->sign)
                return array('status'=>1,'message'=>'验证通过');
            else
                return array('status'=>0,'message'=>'失败');
        }
    }

    /**
     * 解析查询字符串，并排序，去掉签名字段
     * @return multitype:number string
     */
    public function parseUrl()
    {
        $result = array('flag'=>1);//默认url解析正常
        $querString = $_SERVER["QUERY_STRING"];
        $queryStringArrayTemp = explode('&', $querString);
        $queryStringArray = '';
        foreach ($queryStringArrayTemp as $key=>$param)
        {
            $pArray = explode('=', $param);
            if(count($pArray)!=2)
            {
                $result = array('flag'=>-1);//URL解析错误
                $queryStringArray = '';//遇到错误,置空处理
                break;
            }
            $queryStringArray[$pArray[0]] = $pArray[1];

        }
        if(isset($queryStringArray['sign']))
            unset($queryStringArray['sign']); //删除sign签名参数
        ksort($queryStringArray);
        $result['url'] = http_build_query($queryStringArray);
        return $result;
    }

}