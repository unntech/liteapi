<?php
namespace LiteApi;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * 接口请求的基础类
 */
class HttpRequest
{
    protected $request, $fd;
    protected $pathInfo, $remoteAddr, $postData;
    protected $config, $db, $redis;
    protected $seckey='', $DT_TIME;
    
    public function __construct(){
        
    }
    
    public function __call($name, $arguments) {
        //方法名$name区分大小写
        
        return $this->response(-1, "调用方法：{$name} 不存在");
    }  
    
    public function init($request, $config){
        $this->fd = $request->fd;
        $this->config = $config;
        $req['header'] = $request->header;
        $req['get'] = $request->get;
        $req['cookie'] = $request->cookie;
        $req['content'] = $request->rawContent();
        $this->request = $req;
        $this->postData = json_decode($req['content'], true);
        $this->remoteAddr = $request->server['remote_addr'];
        $requestPath = isset($request->server['path_info']) ? explode('/',$request->server['path_info']) : array('');
        $qpath = $requestPath;
        unset($qpath[0]);
        unset($qpath[1]);
        unset($qpath[2]);
        $this->pathInfo = array_values($qpath);
        $this->seckey = isset($req['header']['token']) ? $req['header']['token'] : '';
        $this->DT_TIME = time();
    }
    
    public function set_db($i=0){ //$i 为配置文件db列表里的第几个配置
        if(!empty($this->db)){ //已连接
            return;
        }
        $cfg = $this->config->get('db');
        if(empty($cfg)){
            $this->config->load(['db']);
            $cfg = $this->config->get('db');
        }
        $this->db = \LitePhp\Db::Create($cfg, $i);
    }
    
    public function close_db(){
        $this->db->close();
        $this->db = null;
    }
    
    public function set_redis(){
        if(!empty($this->redis)){ //已连接
            return;
        }
        $cfg = $this->config->get('redis');
        if(empty($cfg)){
            $this->config->load(['redis']);
            $cfg = $this->config->get('redis');
        }
        $this->redis = \LitePhp\Redis::Create($cfg);
    }
    
    public function close_redis(){
        $this->redis->close();
        $this->redis = null;
    }
    
    public function noneType(){
        return $this->response(10, 'NONE DATA');
    }
    
    public function response($err=0, $msg='', $data=array()){
        if(isset($data['signType']) && $data['signType'] != 'NONE'){
            ksort($data);
            switch($data['signType']){
                case 'MD5':
                    $sign = strtoupper(md5(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$this->seckey));
                    $data['sign'] = $sign;
                    break;
                case 'SHA256':
                    $sign = strtoupper(hash("sha256",json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$this->seckey));
                    $data['sign'] = $sign;
                    break;
                case 'RSA':
                    $_rsa = new \LitePhp\LiRsa($this->config->get('app.rsaKey.pub'), $this->config->get('app.rsaKey.priv'));
                    $data = $_rsa->signArray($data);
                    break;

            }
        }else{
            $data['signType'] = 'NONE';
        }
        $ret = array('errcode'=>$err,'fd'=>$this->fd, 'msg'=>$msg);
        $ret['data'] = $data;
        return $ret;
    }
    
    public function verifySign(){
        $data = $this->postData;
        if(!is_array($data)){
            return true;
        }
        $dataSign = $data['sign'] ?? 'NONE';
        ksort($data);
        $verify = false;
        if(isset($data['signType']) && $data['signType'] != 'NONE'){
            switch($data['signType']){
                case 'MD5':
                    unset($data['sign']);
                    $sign = strtoupper(md5(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$this->seckey));
                    if($dataSign == $sign){
                        $verify = true;
                    }
                    break;
                case 'SHA256':
                    unset($data['sign']);
                    $sign = strtoupper(hash("sha256",json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$this->seckey));
                    if($dataSign == $sign){
                        $verify = true;
                    }
                    break;
                case 'RSA':
                    //$data['sign'] = $dataSign;
                    $_rsa = new \LitePhp\LiRsa($this->config->get('app.rsaKey.pub'), $this->config->get('app.rsaKey.priv'));
                    $_rsa->SetThirdPubKey($this->config->get('app.rsaKey.thirdPub'));
                    $verify = $_rsa->verifySignArray($data);
                    break;
                default:
                    $verify = false;
            }
        }else{
            $verify = true;
        }
        
        return $verify;
    }
}