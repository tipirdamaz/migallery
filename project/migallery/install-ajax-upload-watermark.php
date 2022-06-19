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
include_once("definitions0.php");
include_once("definitions1.php");
require_once('session.php');

$croped_image = $_POST['wm_file'];
list($type, $croped_image) = explode(';', $croped_image);
list(, $croped_image)      = explode(',', $croped_image);
$croped_image = base64_decode($croped_image);

$output = [];

if (!file_exists(IMG_BASE_DIR)) {
   if (!mkdir(IMG_BASE_DIR, 0755, true)) {
      $output['status'] = "Can't create ".IMG_BASE_DIR." directory.";
   }
}

$fp = fopen(IMG_BASE_DIR.'/watermark.png', "wb");
if (!$fp) {
   $output['status'] = "The watermark file could not be written to disk";
} else {
   fwrite($fp, $croped_image);
   fclose($fp);
   $output['status'] = 'OK';
}

print json_encode($output);

?>
