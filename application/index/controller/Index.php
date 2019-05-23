<?php
namespace app\index\controller;

use QL\QueryList;

/**
 * Class Index
 * @package app\index\controller
 * 获取首页内容
 */
class Index extends Base
{
    /**
     * @route("/index")
     */
    public function index(){
        $lunbo_rules = [
            'url'=>['.b-topslider-item>.p0','href'],
            'image'=>['.b-topslider-item>.p0>img','src'],
            'title'=>['.b-topslider-item>.p0>.title','text']
        ];
        if ($this->redis->has("index_data")) {
            $data = $this->redis->get("index_data");
            return ['status'=>true,'code'=>0,'data'=>$data];
        }
        $html = $this->getHttps($this->url);
        $html = html_entity_decode($html);
        $data = QueryList::html($html)->rules($lunbo_rules)->queryData();
        if (empty($data)) {
            return ['status'=>false,'code'=>40001,"err"=>"获取数据失败"];
        }else{
            $this->redis->set("index_data",$data,24*3600);
            return ['status'=>true,'code'=>0,'lunbo'=>$data];
        }
    }

    /**
     * @route("/TvNew")
     */
    public function getTvNew(){
        if ($this->redis->has("index_tv_new")) {
            $data = $this->redis->get('index_tv_new');
            return ['status'=>true,'err'=>0,'data'=>$data];
        }
        $html = $this->getHttps($this->url);
        $tv_new_rules = [
            'href'=>['#js-dianshi>>ul>li>a','href'],
            'image'=>['#js-dianshi>>ul>li>a>>img','src'],
            'title'=>['#js-dianshi>>ul>li','title']
        ];
        $html = html_entity_decode($html);
        $data = QueryList::html($html)->rules($tv_new_rules)->queryData();
        if (empty($data)) {
            return ['status'=>false,'code'=>40001,'err'=>'获取数据失败'];
        }else{
            $this->redis->set('index_tv_new',$data,24*3600);
            return ['status'=>true,'code'=>0,'data'=>$data];
        }
    }

    /**
     * @route("/DyNew")
     */
    public function getDyNew(){
        if ($this->redis->has("index_dy_new")) {
            $data = $this->redis->get('index_dy_new');
            return ['status'=>true,'err'=>0,'data'=>$data];
        }
        $html = $this->getHttps($this->url);
        $dy_new_rules = [
            'href'=>['.remendy>>.rmcontent>ul>li>a','href'],
            'image'=>['.remendy>>.rmcontent>ul>li>a>>img','src'],
            'title'=>['.remendy>>.rmcontent>ul>li','title']
        ];
        $html = html_entity_decode($html);
        $data = QueryList::html($html)->rules($dy_new_rules)->queryData();
        if (empty($data)) {
            return ['status'=>false,'code'=>40001,'err'=>'获取数据失败'];
        }else{
            $this->redis->set('index_dy_new',$data,24*3600);
            return ['status'=>true,'code'=>0,'data'=>$data];
        }
    }

    /**
     * @route("/ZyNew")
     */
    public function getZyNew(){
        if ($this->redis->has("index_zy_new")) {
            $data = $this->redis->get('index_zy_new');
            return ['status'=>true,'err'=>0,'data'=>$data];
        }
        $html = $this->getHttps($this->url);
        $zy_new_rules = [
            'href'=>['.zycontent>ul>li>a','href'],
            'image'=>['.zycontent>ul>li>a>>img','src'],
            'title'=>['.zycontent>ul>li','title']
        ];
        $html = html_entity_decode($html);
        $data = QueryList::html($html)->rules($zy_new_rules)->queryData();
        if (empty($data)) {
            return ['status'=>false,'code'=>40001,'err'=>'获取数据失败'];
        }else{
            $this->redis->set('index_zy_new',$data,24*3600);
            return ['status'=>true,'code'=>0,'data'=>$data];
        }
    }

    /**
     * @route("DmNew")
     */
    public function getDmNew(){
        if ($this->redis->has("index_dm_new")) {
            $data = $this->redis->get('index_dm_new');
            return ['status'=>true,'err'=>0,'data'=>$data];
        }
        $html = $this->getHttps($this->url);
        $dm_new_rules = [
            'href'=>['.dongman>>.content>ul>li>a','href'],
            'image'=>['.dongman>>.content>ul>li>a>>img','src'],
            'title'=>['.dongman>>.content>ul>li','title']
        ];
        $html = html_entity_decode($html);
        $data = QueryList::html($html)->rules($dm_new_rules)->queryData();

        if (empty($data)) {
            return ['status'=>false,'code'=>40001,'err'=>'获取数据失败'];
        }else{
            if (count($data) > 6) {
                $data = array_slice($data,0,7);
            }
            $this->redis->set('index_dm_new',$data,24*3600);
            return ['status'=>true,'code'=>0,'data'=>$data];
        }
    }
}
