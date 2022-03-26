<?php
namespace LiteApi;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * LiWebSocket 基础类
 */
class WebSketRepo extends LiteApi
{
    protected $srv;
    public $seckey = '';
    
    public function __construct($srv=null)
    {
        parent::__construct();
        if(!empty($srv)){
            $this->srv = $srv;
        }
    }
    
    public function exist($fd){
		return $this->srv->exist($fd);
	}
    
    public function close($fd){
        if($this->exist($fd)){
            $this->srv->close($fd);
        }
    }
    
    public function set_seckey($seckey){
        $this->seckey = $seckey;
    }
    
    public function noneType($fd){
        return $this->response($fd, 'NONE', 10, '无效请求类型');
    }
    
    public function noneSec($fd, $type){
        return $this->response($fd, $type, 31, 'sec密钥不正确');
    }
    
    public function response($fd, $type, $err=0, $msg='', $data=array()){
        if(isset($data['signtype']) && $data['signtype'] != 'NONE'){
            ksort($data);
            switch($data['signtype']){
                case 'MD5':
                    $sign = strtoupper(md5(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$this->seckey));
                    $data['sign'] = $sign;
                    break;
                case 'SHA256':
                    $sign = strtoupper(hash("sha256",json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$this->seckey));
                    $data['sign'] = $sign;
                    break;
                default:
                    $data['sign'] = '';

            }
        }{
            $data['signtype'] = 'NONE';
        }
        $ret = array('type'=>$type,'errcode'=>$err,'fd'=>$fd, 'msg'=>$msg);
        $ret['data'] = $data;
        $r = $this->srv->push($fd, json_encode($ret, JSON_UNESCAPED_SLASHES));
        return $r;
    }
    
    public function verifySign($data, $seckey = ''){
        if($seckey == ''){
            $seckey = $this->seckey;
        }
        $dataSign = isset($data['sign']) ? $data['sign'] : 'NONE';
        ksort($data);
        $verify = false;
        if(isset($data['signtype']) && $data['signtype'] != 'NONE'){
            switch($data['signtype']){
                case 'MD5':
                    unset($data['sign']);
                    $sign = strtoupper(md5(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$seckey));
                    if($dataSign == $sign){
                        $verify = true;
                    }
                    break;
                case 'SHA256':
                    unset($data['sign']);
                    $sign = strtoupper(hash("sha256",json_encode($data,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).$seckey));
                    if($dataSign == $sign){
                        $verify = true;
                    }
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