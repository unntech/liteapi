<?php
namespace LiteApi;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * LiHttpApi 基础类
 */
class HttpApi
{
    protected $Lite, $DT_TIME;
    
    public function __construct($Lite)
    {
        $this->Lite = $Lite;
        $this->DT_TIME = time();
    }
    
    public function onShutdown($serv){ //停止服务时
        
    }
    
    public function onWorkerStart($serv, $worker_id){ //启动时
        //echo 'Server is Reload...';
        
    }
    
    public function onRequest($request, $response) { //Http请求
        $requestPath = isset($request->server['path_info']) ? explode('/',$request->server['path_info']) : array('');
        $req = new HttpRequest();
        $req->init($request, $this->Lite->config);
        
        if(!$req->verifySign()){
            $ret =$req->response(11, '数据验签错误');
            $this->response($ret, $response);
            return;
        }
        if($requestPath[1] == 'authorize'){ //签发TOKEN
            $res = $this->authorize($request);
            if($res){
                //$res['signtype'] = 'RSA';
                $ret =$req->response(0, '获取TOKEN', $res);
            }else{
                $ret =$req->response(100, '获取TOKEN失败！');
            }
            $this->response($ret, $response);
            return;
        }
        
        $jwt = $this->verifyToken($request);
        if($jwt === false){
            $ret =$req->response(99, '验证TOKEN失败！');
            $this->response($ret, $response);
            return;
        }

        $pathInfoCount = count($requestPath);
        if($pathInfoCount >= 3){
            $_func = array_pop($requestPath);
            $_i = strpos($_func, '.');
            $func = $_i === false ? $_func : substr($_func, 0, $_i);
            unset($requestPath[0]);
            $action = implode("\\", $requestPath);
            $newclass = "\\LiteApi\\controller\\".$action;
            try{
                $filename = __DIR__ . '/controller/';
                $filename .= str_replace("\\", '/', $action) . '.php';
                if(file_exists($filename)){
                    $api = new $newclass();
                    $api->init($request, $this->Lite->config);
                    //$api->set_db();
                    //$api->set_redis();
                    $ret = $api->$func();
                    
                }else{
                    $ret =$req->response(-1, '接口不存在！');
                }
                
            }catch(\Throwable $e){
                $emsg = $e->getMessage();
                $ret =$req->response(-1, $emsg);
            }
        }else{
            $ret = $req->noneType();
        }
 
        $this->response($ret, $response);
    }
    
    public function onTask($serv, $task_id, $from_id, $data) {
        
    }
    
    public function onTaskFinish($serv, $task_id, $data) {
        
    }
    
    public function authorize($request){
        /*---
        按需求验证请求身份，验证通过后给予核发TOKEN
        */
        
        $pass = true; //假设验证通过
        if($pass){
            $liCrypt = new \LitePhp\LiCrypt(DT_KEY);
            $cfg = $this->Lite->config->get('httpapi.auth');
            $_exp = $cfg['token_expires'];
            $exp = time() + $_exp;
            //获取Token
            $jwt = ['sub'=>'abc', 'exp'=>$exp];
            $token = $liCrypt->getToken($jwt);
            $ret = ['token'=>$token, 'expires_in'=>$_exp];
        }else{
            $ret = false;
        }
        return $ret;
    }
    
    /*
     * 验证请求TOKEN
     */
    public function verifyToken($request){
        $token = $request->header['token'] ?? '';
        $auth = $this->Lite->config->get('httpapi.auth.auth', false);
        
        if($auth){ //需要验证
            if($token == ''){
                return false;
            }
            $liCrypt = new \LitePhp\LiCrypt(DT_KEY);
            $verify = $liCrypt->verifyToken($token);
            return $verify;
        }else{
            return true;
        }
    }
    
    public function response($data, $response){
        $response->header('Content-Type', 'application/json; charset=utf-8');
        $response->end(json_encode($data, JSON_UNESCAPED_SLASHES )); // | JSON_UNESCAPED_UNICODE
    }
    
}