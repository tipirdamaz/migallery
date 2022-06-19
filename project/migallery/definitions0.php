<?php

$mi_host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];

$mi_base_dir = str_replace("\\", '/', dirname(__FILE__));

if (@preg_match('/('.str_replace(["\\", '/'], '\/', dirname($_SERVER['REQUEST_URI'])).'.*$)/', $mi_base_dir, $matches)) {
   $mi_base_url = $mi_host.$matches[0];
} else if (@preg_match('/('.str_replace(["\\", '/'], '\/', $_SERVER['REQUEST_URI']).'.*$)/', $mi_base_dir, $matches)) {
   $mi_base_url = $mi_host.$matches[0];
} else {
   $mi_base_url = $mi_host.(str_replace("\\",'/',dirname($_SERVER['REQUEST_URI']))=='/' ? rtrim($_SERVER['REQUEST_URI'], '/') : dirname($_SERVER['REQUEST_URI']));
}


define("GALLERY_DIR", $mi_base_dir); //gallery base dir.
define("GALLERY_URL", $mi_base_url); //gallery base url.
define("DB_DIR", GALLERY_DIR.'/db'); //writable base directory for dynamic and uploaded content.
define("DB_URL", GALLERY_URL.'/db');


/**
* The following files are created after installation. 
* If $config['debug'] = true and an error occurs, the error.log file is created and errors are written into this file.
*/
define("ERROR_LOG_FILE", DB_DIR.'/error.log'); // error log file
define("CONFIG_FILE", DB_DIR.'/config.php'); // configuration file
define("DEFINITIONS_FILE", DB_DIR.'/definitions.php'); // definitions file

?>