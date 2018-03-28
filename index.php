<?php
/**
 * Simple PHP Framework
 *
 * Dependency Injection Libary thanks to Pimple http://pimple.sensiolabs.org/
 *
 * @autor manuel.he@gmail.com
 * @version 2.0
 */
$script_start_time = microtime(true);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Bogota');
$show_stats = TRUE;

//Start session
session_cache_limiter(false);
session_start();

// Require vendor autoload libraries
require 'app/vendor/autoload.php';
$di = require 'app/config/di.php';

//Init routing class
$routing = new \Mas\Router($di, $_SERVER, $_GET);
//Run application
$routing->run();

// Print usage basic stats
if($show_stats){
    $total_time = microtime(true) - $script_start_time;
    printf('
    <!-- Generated in %01.3f secs -->'
        ,$total_time
    );
}
