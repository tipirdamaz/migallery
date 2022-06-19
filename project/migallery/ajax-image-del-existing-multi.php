<?php 

   /**
   * It deletes the existing multiple image from the server while editing the gallery.
   * 
   * Request is sent from 	: delMultiImageFromExistingGallery()	at uploader.js
   * Uploader.delMultiImageFromExistingGallery()	: It deletes the existing preloaded multiple image from the server while the gallery is being edited.
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn			: from init.php
   * @var {Array} $config				: from CONFIG_FILE
   * @var {Array} $_SESSION['imgs_info']: Session variable where image information is stored
   * 
   * $_POST vars
   * @var {Integer} $_POST['gal_id']	: Gallery id
   * @var {String} $_POST['imgs_info']	: json-encoded array containing the client side latest information of the all images
   * @var {String} $_POST['imgs_fname']	: json-encoded array containing the file names of the images to delete
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
$imgs_info_post = MIGallery::postInfoJsonDecode($_POST['imgs_info']);
$imgs_fname_post = MIGallery::postInfoJsonDecode($_POST['imgs_fname'], true);
MIGallery::imageDelExistingFilesMulti($imgs_fname_post, $imgs_info_post, $_SESSION['imgs_info']);
$response = MIGallery::getResult();
echo json_encode($response);
MIGallery::end();


require_once('final.php');

?>
