<?php
define('APP_DEBUG', true);
define('BUILD_DIR_SECURE', false);
define('APP_PATH', './Application/');
define('RUNTIME_PATH','./Runtime/');

//If it is final round, uncomment the following line
define('BIND_MODULE','FinalRound');
define('APP_STATUS','local');

require './ThinkPHP/ThinkPHP.php';
