<?php


define('PROD',    true);           
define('BASE_URL', '/');           


define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'software_catalog');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');


define('DB_OPT', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '".DB_CHARSET."'",
    
]);



