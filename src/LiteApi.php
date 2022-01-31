<?php
namespace LiteApi;

defined('IN_LiteApi') or exit('Access Denied');
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
        $this->config = new \LiteApi\Config();
        $this->config->load(['app']);
    }
    
    
}