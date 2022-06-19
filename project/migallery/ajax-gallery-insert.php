<?php 

   /**
   * It inserts gallery information to database.
   * It inserts image information of this gallery, which is uploaded and stored in the $_SESSION['imgs_info'] variable, to the database.
   * It creates image folders according to the gallery id and moves images from temporary folders to these folders.
   * Creates the xml file containing the information of the gallery and images.
   * 
   * Request is sent from 	: galleryInsert() 			at uploader.js
   * Called from 			: Uploader.galleryInsert() 	at uploader.php
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn			: from init.php
   * @var {Array} $config				: from CONFIG_FILE
   * @var {Array} $_SESSION['imgs_info']: Session variable where image information is stored
   * 
   * $_POST vars
   * @var {String} $_POST['gal_name']			: Gallery name
   * @var {String} $_POST['gal_description']	: Gallery description
   * @var {String} $_POST['gal_keywords']		: Gallery keywords
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


$gal_name = $_POST['gal_name'];
$gal_description = $_POST['gal_description'];
$gal_keywords = $_POST['gal_keywords'];

MIGallery::init($config, $db_conn);
MIGallery::galleryInsert($gal_name, $gal_description, $gal_keywords, $_SESSION['imgs_info']);
$response = MIGallery::getResult();
echo json_encode($response);
MIGallery::end();


require_once('final.php');
?>
