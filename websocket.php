<?php

require_once 'autoload.php';

use Swoole\WebSocket\Server;

Co::set(['hook_flags'=> SWOOLE_HOOK_TCP | SWOOLE_HOOK_SLEEP | SWOOLE_HOOK_CURL]);

$Lite->config->load(['websocket']);
$LiSket = new LiWebSocket($Lite->config->get('websocket'));

$LiSket->start();

class LiWebSocket {
    public $server, $Lite;
    public $commTable;
    protected $host = '0.0.0.0';
    protected $port = 9899;
    protected $taskName = 'LiWebSocket';
    protected $options = [
            'log_file' => __DIR__.'/log/wsket.log',
            'pid_file' => __DIR__.'/websocket.pid',
    ];
    
    public function __construct($param = []) {
        $this->port = isset($param['port']) ? $param['port'] : 9898 ;
        if(isset($param['SSL']) && $param['SSL']==true){
            $this->server = new Swoole\WebSocket\Server($this->host, $this->port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL );
        }else{
            $this->server = new Swoole\WebSocket\Server($this->host, $this->port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP );
        }
        
        if (!empty($param)) {
            $this->taskName = $param['taskName'];
            $options = $param['options'];
            $this->options = array_merge($this->options, $options);
        }
        $this->server -> set($this->options);
        
        LiteApi\LiApiCommVar::$commTable = new Swoole\Table(1024);
        LiteApi\LiApiCommVar::$commTable->column('num', Swoole\Table::TYPE_INT);
        LiteApi\LiApiCommVar::$commTable->create();
        
        $this->server->on("open", [$this, 'onOpen']);
        $this->server->on("message", [$this, 'onMessage']);
        $this->server->on("close", [$this, 'onClose']);
        $this->server->on("Task", [$this, 'onTask']);
        $this->server->on("Finish", [$this, 'onTaskFinish']);
        
        
        $this->server->on('WorkerStart', function ($serv, $worker_id){

            $this->Lite = new LiteApi\LiteApi();
            $this->Lite->config->load(['websocket']);
            $wSket = new LiteApi\WebSocket($this->Lite, $this->server);
            $wSket->onWorkerStart($serv, $worker_id);
            unset($wSket);
            
        });
        
        $this->server->on('Start', function($serv){
            swoole_set_process_name($this->taskName);
        });

        $this->server->on('shutdown',function ($serv) {
            $wSket = new LiteApi\WebSocket($this->Lite, $this->server);
            $wSket->onShutdown($serv);
            unset($wSket);
        });

    }
    
    public function __destruct() {

    }
    
    public function onOpen(Swoole\WebSocket\Server $serv, $request) {
        $wSket = new LiteApi\WebSocket($this->Lite, $this->server);
        $wSket->onOpen($request);
        unset($wSket);
    }
    
    public function onMessage(Swoole\WebSocket\Server $serv, $frame) {
        $wSket = new LiteApi\WebSocket($this->Lite, $this->server);
        $wSket->onMessage($frame);
        unset($wSket);
    }
    
    public function onTask($serv, $task_id, $from_id, $data) {
        $wSket = new LiteApi\WebSocket($this->Lite, $this->server);
        $fine = $wSket->onTask($serv, $task_id, $from_id, $data);
        
        if(!empty($fine) && count($fine)){
            $serv->finish($fine);
        }
        unset($wSket);
    }
    
    public function onTaskFinish($serv, $task_id, $data) {
        $wSket = new LiteApi\WebSocket($this->Lite, $this->server);
        $wSket->onTaskFinish($serv, $task_id, $data);
        unset($wSket);
    }
    
    public function onClose($ser, $fd) {
        $wSket = new LiteApi\WebSocket($this->Lite, $ser);
        $wSket->offline($fd);
        unset($wSket);
    }
    
    public function start(){
        $this->server->start();
    }
    
}
