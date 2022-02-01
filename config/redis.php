<?php

defined('IN_LitePhp') or exit('Access Denied');

return[
    // 连接配置信息
    'connections'     => [
            // 服务器地址
            'host'          => '10.100.113.20',
            // 密码
            'password'      => '123456',
            // 端口
            'port'          => 6379,
            // KEY 前缀
            'prefix'        => '',
            //库ID
            'db'            => 1,
        ]
];