<?php 

   /**
   * Full, slide and thumbnail copies of the uploaded file are created in the temporary folder.
   * 
   * Request is sent from : uploader.js
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn			: from init.php
   * @var {Array} $config				: from CONFIG_FILE
   * @var {String} $sessid				: PHPSESSID, session id for the current session from session.php 
   * @var {Array} $_SESSION['imgs_info']: Session variable where image information is stored
   * @var {Array} $_FILES['filedata']	: Uploaded file info
   * 
   */

include_once('FileAPI.class.php');

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


///header('Access-Control-Allow-Origin: *');
include_once("definitions0.php");
include_once(DEFINITIONS_FILE);
include_once(CONFIG_FILE);
date_default_timezone_set($config['timezone']);
require_once('init.php');
require_once('session.php');
require_once('MIGallery.class.php');


MIGallery::init($config, $db_conn);
MIGallery::imageUpload($_FILES['filedata'], $_SESSION['imgs_info'], $sessid);
$result = MIGallery::getResult();
MIGallery::end();


if($result['code'] == MIGallery::OK) {
   $status = FileAPI::OK;  //"HTTP/1.1 200 OK"
   $statusText = 'OK';
} else {
   $status = FileAPI::ERROR; //"HTTP/1.1 500 ERROR"
   $statusText = 'ERROR';
}

if( strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' )
{
   $files = FileAPI::getFiles(); // Retrieve File List
   $images = array();

   // Fetch all image-info from files list
   MIGallery::fetchImages($files, $images);

   // JSONP callback name
   $jsonp = isset($_REQUEST['callback']) ? trim($_REQUEST['callback']) : null;

   // JSON-data for server response
   $json = array(
      'images' => $images
    , 'data' => array('_REQUEST' => $_REQUEST, '_FILES' => $files)
   );

   // Server response
   FileAPI::makeResponse(array(
      'status' => $status
    , 'statusText' => $statusText
    , 'body' => $json
   ), $jsonp);
   ///exit;
}

require_once('final.php');

?>
