<?php

require_once 'autoload.php';

use Swoole\Http\Server;

Co::set(['hook_flags'=> SWOOLE_HOOK_TCP | SWOOLE_HOOK_SLEEP | SWOOLE_HOOK_CURL]);

$Lite->config->load(['httpapi']);
$httpApi = new LiHttpApi($Lite->config->get('httpapi'));

$httpApi->start();

class LiHttpApi {
    public $server, $Lite;
    public $commTable;
    protected $host = '0.0.0.0';
    protected $port = 9898;
    protected $taskName = 'LiHttpApi';
    protected $options = [
            'log_file' => __DIR__.'/log/http.log',
            'pid_file' => __DIR__.'/http.pid',
    ];
    
    public function __construct($param = []) {
        $this->port = isset($param['port']) ? $param['port'] : 9898 ;
        if(isset($param['SSL']) && $param['SSL']==true){
            $this->server = new Swoole\Http\Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP | SWOOLE_SSL );
        }else{
            $this->server = new Swoole\Http\Server($this->host, $this->port, SWOOLE_BASE, SWOOLE_SOCK_TCP );
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
        
        $this->server->on("request", [$this, 'onRequest']);
        $this->server->on("Task", [$this, 'onTask']);
        $this->server->on("Finish", [$this, 'onTaskFinish']);
        
        
        $this->server->on('WorkerStart', function ($serv, $worker_id){

            $this->Lite = new LiteApi\LiteApi();
            $this->Lite->config->load(['httpapi']);
            $hApi = new LiteApi\HttpApi($this->Lite);
            $hApi->onWorkerStart($serv, $worker_id);
            unset($hApi);
            
        });
        
        $this->server->on('Start', function($serv){
            swoole_set_process_name($this->taskName);
        });

        $this->server->on('shutdown',function ($serv) {
            $hApi = new LiteApi\HttpApi($this->Lite);
            $hApi->onShutdown($serv);
            unset($hApi);
        });

    }
    
    public function __destruct() {

    }
    

    
    public function onRequest($request, $response) {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            $response->end();
            return;
        }
        
        $hApi = new LiteApi\HttpApi($this->Lite);
        $hApi->onRequest($request, $response);
        unset($hApi);
    }
    
    public function onTask($serv, $task_id, $from_id, $data) {
        $hApi = new LiteApi\HttpApi($this->Lite);
        $fine = $hApi->onTask($serv, $task_id, $from_id, $data);
        
        if(!empty($fine) && count($fine)){
            $serv->finish($fine);
        }
        unset($hApi);
    }
    
    public function onTaskFinish($serv, $task_id, $data) {
        $hApi = new LiteApi\HttpApi($this->Lite);
        $hApi->onTaskFinish($serv, $task_id, $data);
        unset($hApi);
    }
    
    public function start(){
        $this->server->start();
    }
    
}
