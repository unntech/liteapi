<?php

require __DIR__.'/../autoload.php';


$appName = $Lite->config->get('app.name');
var_dump($appName);

$a = LitePhp\LiComm::createNonceStr();

var_dump($a);

