<?php 

   /**
   * All data of the selected gallery on the server is deleted.
   * Request is sent from gallery-man.php
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn			: from init.php
   * @var {Array} $config				: from CONFIG_FILE
   * 
   * $_POST vars
   * @var {Integer} $_POST['gal_id']	: Gallery id to be deleted
   */

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
include_once("definitions0.php");
include_once(DEFINITIONS_FILE);
include_once(CONFIG_FILE);
require_once('init.php');
require_once('session.php');
require_once('MIGallery.class.php');

if (!is_numeric($_POST['gal_id'])) exit();

$gal_id = $_POST['gal_id'];


MIGallery::init($config, $db_conn, $gal_id);
MIGallery::galleryDelete();
$response = MIGallery::getResult();
echo json_encode($response);
MIGallery::end();


require_once('final.php');

?>
