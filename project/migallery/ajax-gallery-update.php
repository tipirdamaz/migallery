<?php 

   /**
   * It updates the selected gallery's information in the database.
   * It updates the gallery and image information in the XML file of the gallery.
   * 
   * Request is sent from 	: galleryUpdate() 			at uploader.js
   * Called from 			: Uploader.galleryUpdate() 	at uploader.php
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn			: from init.php
   * @var {Array} $config				: from CONFIG_FILE
   * 
   * $_POST vars
   * @var {Integer} $_POST['gal_id']			: Gallery id
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


if (!is_numeric($_POST['gal_id'])) exit();
$gal_id = $_POST['gal_id'];
$gal_name = $_POST['gal_name'];
$gal_description = $_POST['gal_description'];
$gal_keywords = $_POST['gal_keywords'];

MIGallery::init($config, $db_conn, $gal_id);
MIGallery::galleryUpdate($gal_name, $gal_description, $gal_keywords);
$response = MIGallery::getResult();
echo json_encode($response);
MIGallery::end();


require_once('final.php');
?>
