<?php 

   /**
   * Uploaded images are stored in temporary folders before the gallery is saved and id retrieved.
   * In the meantime, MIGallery::imageDelNewUploadedFile method is called if the uploaded image is to be deleted.
   * 
   * Request is sent from 	: delImageNewUploaded()	at uploader.js
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn			: from init.php
   * @var {Array} $config				: from CONFIG_FILE
   * @var {Array} $_SESSION['imgs_info']: Session variable where image information is stored
   * 
   * $_POST vars
   * @var {String} $_POST['img_name_del']	: The name of the image file to delete
   * @var {String} $_POST['img_ext_del']	: Extension of the image file to delete
   * @var {String} $_POST['imgs_info']		: json-encoded array containing the client side latest information of the all images
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


if (!preg_match('/^([a-z0-9]+)$/', $_POST['img_name_del'])) exit();
if (!preg_match('/^([a-z0-9]+)$/', $_POST['img_ext_del'])) exit();

$img_name_del = $_POST['img_name_del'];
$img_ext_del = $_POST['img_ext_del'];


MIGallery::init($config, $db_conn);
$imgs_info_post = MIGallery::postInfoJsonDecode($_POST['imgs_info']);
MIGallery::imageDelNewUploadedFile($img_name_del, $img_ext_del, $imgs_info_post, $_SESSION['imgs_info']);
$response = MIGallery::getResult();
echo json_encode($response);
MIGallery::end();


require_once('final.php');

?>
