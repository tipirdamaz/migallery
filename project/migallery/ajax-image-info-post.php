<?php 

   /**
   * Updates the information on the server according to the image information sent from the client.
   * The image information uploaded before the gallery is saved is stored in the session variable with the MIGallery::imageInfoSaveToSession method, and after the gallery information is inserted into database, it is associated with the gallery id and inserted into the database.
   * Image information sent during gallery editing is inserted into the database with the MIGallery :: imageInfoUpdate method.
   * 
   * Request is sent from : uploader.js
   * With the fileapi.onComplete method, which is called after the images have finished uploading.
   * By saving the image changes with the Uploader.saveImageInfoChanges method.
   * After calling delImageNewUploaded or Uploader.delImageNewUploadedFromExistingGallery methods.
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn			: from init.php
   * @var {Array} $config				: from CONFIG_FILE
   * @var {Array} $_SESSION['imgs_info']: Session variable where image information is stored
   * 
   * $_POST vars
   * @var {Integer} $_POST['gal_id']	: Gallery id
   * @var {String} $_POST['imgs_info']	: json-encoded array containing the client side latest information of the all images
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

$gal_id = $_POST['gal_id'];

if ($gal_id == '') {
   MIGallery::init($config, $db_conn);
   $imgs_info_post = MIGallery::postInfoJsonDecode($_POST['imgs_info']);
   MIGallery::imageInfoSaveToSession($imgs_info_post, $_SESSION['imgs_info']);
} else {
   if (!is_numeric($gal_id)) exit();
	
   MIGallery::init($config, $db_conn, $gal_id);
   $imgs_info_post = MIGallery::postInfoJsonDecode($_POST['imgs_info']);
   MIGallery::imageInfoUpdate($imgs_info_post, $_SESSION['imgs_info']);
}

$response = MIGallery::getResult();
echo json_encode($response);
MIGallery::end();


require_once('final.php');
?>
