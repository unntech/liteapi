<?php
namespace LiteApi;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * LiteApi 基础类
 */
class LiteApi
{
    /**
     * 配置参数
     */
    const VERSION = '1.0.7';
    public $config, $db, $redis;
    public $appName;

    
    public function __construct()
    {
        $this->config = new \LitePhp\Config(DT_ROOT . "/config/");
        $this->config->load(['app', 'db', 'redis']);
        $this->appName = $this->config->get('app.name', 'LiteApi');

    }
    
    public function set_db($i=0){ //$i 为配置文件db列表里的第几个配置
        //$this->config->load(['db']);
        $this->db = \LitePhp\Db::Create($this->config->get('db'), $i);
    }
    
    public function close_db(){
        $this->db->close();
        $this->db = null;
    }
    
    public function set_redis(bool $reconnect = false){
        if(empty($this->redis) || $reconnect) {
            //$this->config->load(['redis']);
            $this->redis = \LitePhp\Redis::Create($this->config->get('redis'));
        }
    }
    
    public function close_redis(){
        $this->redis->close();
        $this->redis = null;
    }
    
    public function alog($type, $log1='', $log2 = '', $log3 = '' ) {
        if(empty($this->db)){
            $this->set_db();
        }
        $log1 = empty($log1) ? '' : addslashes( $log1 ) ;
        $log2 = empty($log2) ? '' : addslashes( $log2 ) ;
        $log3 = empty($log3) ? '' : addslashes( $log3 ) ;
        $SQLC = "INSERT INTO alog (type, log1,log2,log3) VALUES ('{$type}', '" . $log1 . "','" . $log2 . "','" . $log3 . "')";
        $this->db->query( $SQLC );
        return $this->db->insert_id();
    }
    
    public function do_get($url, $aHeader = null){
        $res = \LitePhp\LiHttp::get($url, $aHeader);
        return $res;
        
    }
    
    public function do_post($url, $data=null, $aHeader = null){
        $res = \LitePhp\LiHttp::post($url, $data, $aHeader);
        return $res;
    }
    
    
}