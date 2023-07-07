<?php
defined('IN_LitePhp') or exit('Access Denied');

function config(string $name = null, $default = null){
    global $Lite;
    $config = $Lite->config;
    if (false !== strpos($name, '.')) {
        $v = explode('.',$name);
        $key = $v[0];
    }else{
        $key = $name;
    }
    if(!$config->exists($key)){
        $config->load($key);
    }
    return $config->get($name, $default);
}

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

/**
 * 全局通用异常处理过程
 * @param Throwable $e
 * @return void
 */
function exception_handler(Throwable $e)
{
    if (DT_DEBUG) {
        $data = ['exception'=>$e, 'code'=>$e->getCode(),'message'=>$e->getMessage(), 'trace'=>$e->getTrace()];
    }else{
        $data = ['code'=>$e->getCode(),'message'=>$e->getMessage()];
    }
    echo json_encode($data);
}