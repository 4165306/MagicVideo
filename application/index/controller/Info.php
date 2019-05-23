<?php
namespace app\index\controller;

use QL\Dom\Query;
use QL\QueryList;

/**
 * Class Info
 * @package app\index\controller
 * 获取影片详情内容
 */
class Info extends Base
{

    /**
     * @route("/Tv/Info")
     * 传入返回的电视URL，返回电视剧详情
     */
    public function getTvInfo(){
        $input = input();
        if (!isset($input)) {
            return ['status'=>false,'code'=>40002,'参数不完整'];
        }
        //获取返回的电视URL
        $url = $input['url'];
        if (strpos($url,'360kan') === false ) {
            $url = $this->url.$url;
        }
        $html = $this->getHttps($url);
        $html = html_entity_decode($html);
        $info_rules = [
            'image'=>['#js-s-cover>a>img','src'],
            'title'=>['.s-top-info>>h1','text'],
            'update'=>['.s-top-info>>.tag','text'],
            'temp'=>['.item-desc-wrap:eq(1)>.item-desc','text'],
        ];
        $tv_data = QueryList::html($html)->rules($info_rules)->queryData();
        $tv_list = QueryList::html($html)->rules([
            'num'=>['.num-tab-main:eq(1)>a','data-num'],
            'url'=>['.num-tab-main:eq(1)>a','href']
        ])->queryData();
        $before_str = <<<EOT
playsite:
EOT;
        $after_str = <<<EOT
,
        playtype
EOT;


        $data['tvinfo'] = $tv_data[0];
        $data['tvlist'] = $tv_list;
        $data['source'] = json_decode($this->getSubstr($html,$before_str,$after_str),true);
        if (empty($data)) {
            return ['status'=>false,'code'=>40001,'error'=>'获取数据失败'];
        }else{
            return ['status'=>true,'code'=>0,'data'=>$data];
        }
    }

    public function getSiteList(){
        $input = input();
        if (!isset($input['site']) || !isset($input['url'])) {
            return ['status'=>false,'code'=>40002,'error'=>'参数不完整'];
        }
        if(!ctype_alnum($input['site'])){
            return ['status'=>false,'code'=>40003,'error'=>'参数设置错误'];
        }
        $source_id = $this->getSubstr($input['url'],'/tv/','.html');
        $site = $input['site'];
        $url = "https://www.360kan.com/cover/switchsitev2?site={$site}&id={$source_id}&category=2";
        $result = json_decode($this->getHttps($url),true);
        $html = htmlspecialchars_decode($result['data']);
        $list = QueryList::html($html)->rules([
            'num'=>['.num-tab-main:eq(1)>a','data-num'],
            'url'=>['.num-tab-main:eq(1)>a','href']
        ])->queryData();
        if (empty($list)) {
            return ['status'=>false,'code'=>40001,'error'=>'获取列表失败'];
        }else{
            return ['status'=>true,'code'=>0,'data'=>$list];
        }
    }
}