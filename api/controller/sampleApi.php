<?php
namespace LiteApi\controller;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * 示例接口例。要求类名和文件名一致
 */
use \LiteApi\HttpRequest;

class sampleApi extends HttpRequest
{
    
    public function __construct(){
        
    }
    
    
    //请求处理函数，按需添加编写
    public function test(){
        $data = ['pathinfo'=> $this->pathInfo,
                 'header'=> $this->request['header'],
                 'content'=> $this->request['content'],
                 'postData'=> $this->postData,
                 'get'=>$this->request['get'],
                 'cookie'=>$this->request['cookie'],
                 'remoteAddr'=>$this->remoteAddr];
        
        $response = $this->response(0, "调用方法：test 成功", $data);
        return $response;
    }
}