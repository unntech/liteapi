<?php
namespace LiteApi;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * LiWebSocket 基础类
 */
class WebSocket
{
    /**
     * @var WebSketRepo
     */
    protected $Lite, $DT_TIME;
    protected $srv;
    
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
        $this->Lite->response($request->fd, 'OPEN');
        $requestPath = isset($request->server['path_info']) ? explode('/',$request->server['path_info']) : [];
        
    }
    
    public function onMessage($frame){ //收到消息
        //var_dump($frame);
        if($frame->finish && $frame->opcode == 1){
            $rd = json_decode($frame->data, true);
            $signType = isset($rd['signType']) ? $rd['signType'] : 'NONE';
            if($signType != 'NONE'){
                $sec = 'abc';
                if(!$this->Lite->verifySign($rd, $sec)){
                    $this->Lite->response($frame->fd,'ERROR',11, '数据验签错误！');
                    return;
                }
            }
                $type = isset($rd['type']) ? $rd['type'] : 'NONE';
                switch($type){
                    case 'NONE':
                        $this->Lite->noneType($frame->fd);
                        break;
                    case 'BEAT':
                        $this->heartbeat($frame->fd);
                        break;
                    default:
                        $this->Lite->noneType($frame->fd);
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
        $fine = array();
        
        
        return $fine;
        
    }
    
    public function onTaskFinish($serv, $task_id, $data) { //任务完成
        
    }
    
    public function heartbeat($fd){
        $this->Lite->response($fd,'BEAT');
        /*----
		$sqlc = "UPDATE `app_wsclients` SET beat = beat + 1 WHERE fd = {$fd}";
        $this->Lite->redis->expire($this->CFG['redis_pre'].'AppSocket_'.$fd, 1200);
		$res = $this->Lite->db->query( $sqlc );
        ----*/
	}
    
    
}