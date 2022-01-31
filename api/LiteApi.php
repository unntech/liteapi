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
    public $config;
    
    public function __construct()
    {
        $this->config = new \LitePhp\Config(DT_ROOT . "/config/");
        $this->config->load(['app']);
    }
    
    
}