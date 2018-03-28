<?php
/**
 * Dependency Injection Container setup file
 * using PIMP Dependency Injection Libary http://pimple.sensiolabs.org/
 */
use Pimple\Container;

//Pimple Instanciation
$di = new Container();

//System Values
$global_path = explode('index.php',$_SERVER["PHP_SELF"]);
$baseDir = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

//Config file parsing
$config = parse_ini_file($baseDir.'app/config/config.ini');
$dbconf = parse_ini_file($baseDir.'app/secretConf/.conf');

$di['config'] = $config + $dbconf + array(
    'baseUrl' => ((
            (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https")
            || (isset($_SERVER['HTTP_REFERER']) && strpos(strtolower($_SERVER['HTTP_REFERER']), 'https') !== FALSE )
            || (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off')
            || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'],'secure') !== FALSE)
        )
            ? 'https' : 'http').
            '://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'',
    'baseDir' => $baseDir,
    'basePath' => isset($global_path[0]) && $global_path[0] ? $global_path[0] : '',
);

//Database instance
$di['db'] = function ($c) {
    return new PDO(
        "mysql:host={$c['config']['db_host']};dbname={$c['config']['db_database']}",
        $c['config']['db_username'],
        $c['config']['db_password'],
        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
    );
};
return $di;
