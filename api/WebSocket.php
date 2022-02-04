<?php
namespace LiteApi;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * LiWebSocket 基础类
 */
class WebSocket
{
    protected $Lite, $DT_TIME;
    protected $srv;
    protected $seckey = '';
    
    public function __construct($Lite, $srv)
    {
        $this->Lite = $Lite;
        $this->DT_TIME = time();
        $this->srv = $srv;
    }
    
    public function onShutdown($serv){ //停止服务时
        //echo 'Server is Shutdown.';
        //do something...
    }
    
    public function onWorkerStart($serv, $worker_id){ //启动时
        //echo 'Server is Reload...';
        //do something...
        
    }
    
    public function onOpen($request){ //建立连接
        //var_dump($request);
        //do something...
        $this->response($request->fd, 'OPEN');
        $requestPath = isset($request->server['path_info']) ? explode('/',$request->server['path_info']) : [];
        
    }
    
    public function onMessage($frame){ //收到消息
        //var_dump($frame);
        if($frame->finish && $frame->opcode == 1){
            $rd = json_decode($frame->data, true);
            $signType = isset($rd['signtype']) ? $rd['signtype'] : 'NONE';
            if($signType != 'NONE'){
                $sec = 'abc';
                if(!$this->verifySign($rd, $sec)){
                    $this->response($frame->fd,'ERROR',11, '数据验签错误！');
                    return;
                }
            }
                $type = isset($rd['type']) ? $rd['type'] : 'NONE';
                switch($type){
                    case 'NONE':
                        $this->noneType($frame->fd);
                        break;
                    case 'BEAT':
                        $this->heartbeat($frame->fd);
                        break;
                    default:
                        $this->noneType($frame->fd);
                }
        }else{
            //接收数据不完整，或非字符
            //do something...
        }
    }
    
    public function offline($fd){ //下线
        //echo "{$fd} offline.";
    }
    
    public function onTask($serv, $task_id, $from_id, $data) { //任务
        
    }
    
    public function onTaskFinish($serv, $task_id, $data) { //任务完成
        
    }
    
    public function exist($fd){
		return $this->srv->exist($fd);
	}
    
    public function close($fd){
        if($this->exist($fd)){
            $this->srv->close($fd);
        }
    }
    
    public function heartbeat($fd){
        $this->response($fd,'BEAT');
        /*----
		$sqlc = "UPDATE `app_wsclients` SET beat = beat + 1 WHERE fd = {$fd}";
        $this->Lite->redis->expire($this->CFG['redis_pre'].'AppSocket_'.$fd, 1200);
		$res = $this->Lite->db->query( $sqlc );
        ----*/
	}
    
    public function noneType($fd){
        return $this->response($fd, 'NONE', 10);
    }
    
    public function noneSec($fd, $type){
        return $this->response($fd, $type, 31);
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