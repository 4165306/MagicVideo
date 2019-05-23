<?php
namespace app\index\controller;

use think\App;
use think\cache\driver\Redis;
use think\Controller;

class Base extends Controller
{
    protected $url = "https://www.360kan.com";

    protected $site = [
        '\u7231\u5947\u827a'=>'qiyi',
        '\u0043\u004e\u0054\u0056'=>'cntv',
        '\u8292\u679c\u0054\u0056'=>'imgo',
        '\u817e\u8baf'=>'qq',
        '\u534e\u6570\u0054\u0056'=>'huashu',
        '\u641c\u72d0'=>'sohu',
        '\u0050\u0050\u89c6\u9891'=>'pptv',
        '\u98ce\u884c'=>'fengxing',
        '\u4f18\u9177'=>'youku'
    ];

    /**
     * @var Redis
     */
    protected $redis;

    public function initialize()
    {
        $this->redis = new Redis();
    }

    protected function getHttps($url){
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch); //执行并获取HTML文档内容
        $str = htmlspecialchars($output);//转换为源代码形式
        //释放curl句柄
        curl_close($ch);
        return $str;
    }

    protected function getSubstr($str, $leftStr, $rightStr)
    {
        $left = strpos($str, $leftStr);
        //echo '左边:'.$left;
        $right = strpos($str, $rightStr,$left);
        //echo '<br>右边:'.$right;
        if($left < 0 or $right < $left) return '';
        return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
    }
}