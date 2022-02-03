<?php

defined('IN_LitePhp') or exit('Access Denied');

return [
    'taskName' => 'LiWebSocket',
    'port' => 9899,
    'SSL' => false,
    'options' => [
        'log_level' => 0,
        'max_request' => 1000,
        'worker_num'=>4,
        'task_worker_num'=>4,
        'task_max_request'=>10000,
        'ssl_cert_file' => '/etc/letsencrypt/live/h82.lth.xyz/fullchain.pem',
        'ssl_key_file' => '/etc/letsencrypt/live/h82.lth.xyz/privkey.pem',
        'heartbeat_check_interval' => 65,
        'daemonize' => true
        ]
];