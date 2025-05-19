<?php

const IN_LitePhp = true;
define('DT_ROOT', str_replace("\\", '/', __DIR__ ));

require_once DT_ROOT . '/vendor/autoload.php';

$Lite = new LiteApi\LiteApi();
LitePhp\Lite::setRootPath(DT_ROOT);

define('ENVIRONMENT', $Lite->config->get('app.ENVIRONMENT', 'DEV'));
define('DT_DEBUG', $Lite->config->get('app.APP_DEBUG', true));
if(DT_DEBUG) {
	error_reporting(E_ALL);
	$debug_starttime = microtime(true);
} else {
	error_reporting(E_ERROR);
}

$DT_TIME = time();

define('DT_KEY', $Lite->config->get('app.authkey', 'LitePhp'));

require_once DT_ROOT . '/include/common.php';
set_exception_handler('exception_handler');