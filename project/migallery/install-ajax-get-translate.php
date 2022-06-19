<?php 

if( !empty($_SERVER['HTTP_ORIGIN']) ){
	// Enable CORS
	header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type');
	header('Access-Control-Allow-Credentials: true');
}


if( $_SERVER['REQUEST_METHOD'] == 'OPTIONS' ){
	exit();
}
//header('Access-Control-Allow-Origin: *');

require_once("session.php");
require_once('Translator.class.php');

$lang = $_POST['lang'];

Translator::select($lang);
$dir = Translator::getDirection();

echo json_encode(Array($dir, Translator::translate()));

?>
