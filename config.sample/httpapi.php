<?php

defined('IN_LitePhp') or exit('Access Denied');

return [
    'taskName' => 'LiHttpApi',
    'port' => 9898,
    'SSL' => false,
    'options' => [
        'log_level' => 5,
        'max_request' => 1000,
        'worker_num'=>4,
        'task_worker_num'=>4,
        'task_max_request'=>10000,
        'ssl_cert_file' => 'fullchain.pem',
        'ssl_key_file' => 'privkey.pem',
        //'open_http2_protocol' => true, // Enable HTTP2 protocol
        'daemonize' => true
        ],
    'auth' =>[
        'auth' => true,  //请求接口需要验证toke
        'token_expires' => 7200  //秒
    ]
];