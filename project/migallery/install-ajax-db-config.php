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
require_once("definitions0.php");
require_once("definitions1.php");

$lang = $_POST['lang'];
$timezone = trim($_POST['timezone']);
$img_direction = $_POST['img_direction'];
$db_host = $_POST['db_host'];
$db_user = $_POST['db_user'];
$db_pass = $_POST['db_pass'];
$db_name = $_POST['db_name'];
$db_img_table = $_POST['db_img_table'];
$db_gal_table = $_POST['db_gal_table'];
$max_upload_limit = $_POST['max_upload_limit'];
$max_file_size = $_POST['max_file_size'];
$up_img_min_width = $_POST['up_img_min_width'];
$up_img_min_height = $_POST['up_img_min_height'];
$up_img_max_width = $_POST['up_img_max_width'];
$up_img_max_height = $_POST['up_img_max_height'];
$img_full_width = $_POST['img_full_width'];
$img_full_height = $_POST['img_full_height'];
$img_thumb_width = $_POST['img_thumb_width'];
$img_thumb_height = $_POST['img_thumb_height'];
$img_slide_width = $_POST['img_slide_width'];
$img_slide_height = $_POST['img_slide_height'];
$jpg_quality = $_POST['jpg_quality'];
$png_compress = $_POST['png_compress'];

$xml_dir = $_POST['xml_dir'];
$tmp_dir = $_POST['tmp_dir'];
$tmp_thumb_dir = $_POST['tmp_thumb_dir'];
$tmp_thumb_c_dir = $_POST['tmp_thumb_c_dir'];
$tmp_slide_dir = $_POST['tmp_slide_dir'];
$img_base_dir = $_POST['img_base_dir'];
$img_dir = $_POST['img_dir'];
$img_thumb_dir = $_POST['img_thumb_dir'];
$img_thumb_c_dir = $_POST['img_thumb_c_dir'];
$img_slide_dir = $_POST['img_slide_dir'];


$wm_use = $_POST['wm_use'];

if ($wm_use=='1' && !file_exists(IMG_BASE_DIR.'/watermark.png')) {
   copy(GALLERY_DIR.'/watermark-default.png', IMG_BASE_DIR.'/watermark.png');
}

$wm_position = $_POST['wm_position'];

$error_log = '';
$error = false;


try {
   $db_connect = mysqli_connect($db_host, $db_user, $db_pass);
   if (!$db_connect) { //error
      $error = true;
      throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.mysqli_connect_errno().': '.mysqli_connect_error().PHP_EOL);
   }

   mysqli_query($db_connect, "SET NAMES 'utf8', CHARACTER SET utf8, COLLATION_CONNECTION = 'utf8_unicode_ci'");

   if (!mysqli_select_db($db_connect, $db_name)) {
      $error = true;
      throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$db_conn->error.PHP_EOL);
   }

   $query = "CREATE TABLE if not exists $db_img_table ( "
           ."id int not null auto_increment, "
           ."gid int not null, " //gallery id
           ."img_title char(55) not null, " //image title
           ."img_desc char(155) not null, " //image description
           ."img_keyw char(155) not null, " //image keywords
           ."img_name char(25) not null, " //image name
           ."img_ext char(5) not null, " //image ext
           ."full_width int not null default 0, " //image full width
           ."full_height int not null default 0, " //image full height
           ."full_size int not null default 0, " //size of the full image in bytes
           ."slide_width int not null default 0, " //image slide width
           ."slide_height int not null default 0, " //image slide height
           ."slide_size int not null default 0, " //size of the slide image in bytes
           ."thumb_width int not null default 0, " //image thumbnail width
           ."thumb_height int not null default 0, " //image thumbnail height
           ."thumb_size int not null default 0, " //size of the thumbnail image in bytes
           ."thumb_c_width int not null default 0, " //image cropped thumbnail width
           ."thumb_c_height int not null default 0, " //image cropped thumbnail height
           ."thumb_c_size int not null default 0, " //size of the cropped thumbnail image in bytes
           ."img_ts int not null default 0, " //unix timestamp
           ."sort int not null default 0, " //image sorting
           ."primary key(id) "
           .") CHARACTER SET utf8 COLLATE utf8_unicode_ci";

   $query_result = mysqli_query($db_connect, $query);
   if (!$query_result) {
      $error = true;
      throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$db_conn->error.PHP_EOL);
   }

   $query = "CREATE TABLE if not exists $db_gal_table ( "
           ."id int not null auto_increment, "
           ."name char(55) not null, " //gallery name
           ."description char(155) not null, " //gallery description
           ."keywords char(155) not null, " //gallery keywords
           ."ts int not null default 0, " //unix timestamp
           ."sort int not null default 0, " //gallery sorting
           ."primary key(id) "
           .") CHARACTER SET utf8 COLLATE utf8_unicode_ci";

   $query_result = mysqli_query($db_connect, $query);
   if (!$query_result) {
      $error = true;
      throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$db_conn->error.PHP_EOL);
   }

   if (!file_exists(DB_DIR)) {
      if (!mkdir(DB_DIR, 0755, true)) {
         $error = true;
         $arr = error_get_last();
         throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
      }
   }

   $config_file_data = "<?php\n";
   $config_file_data .= "\$config = [\n"
       ."	'lang'				=> '{$lang}',\n"
       ."	'timezone'			=> '{$timezone}',\n"
       ."	'img_direction'		=> '{$img_direction}',\n"
       ."	'db_host'			=> '{$db_host}',\n"
       ."	'db_user'			=> '{$db_user}',\n"
       ."	'db_pass'			=> '{$db_pass}',\n"
       ."	'db_name'			=> '{$db_name}',\n"
       ."	'db_gal_table'		=> '{$db_gal_table}',\n"
       ."	'db_img_table'		=> '{$db_img_table}',\n"
       ."	'max_upload_limit'	=> {$max_upload_limit}, 				//Allowable maximum image count per gallery\n"
       ."	'max_file_size'		=> {$max_file_size}, 				//Single File Maximum Size (MB)\n"
       ."	'up_img_min_width'	=> {$up_img_min_width}, 			//allowed minimum width of uploadable image\n"
       ."	'up_img_min_height'	=> {$up_img_min_height}, 			//allowed minimum height of uploadable image\n"
       ."	'up_img_max_width'	=> {$up_img_max_width}, 			//allowed maximum width of uploadable image\n"
       ."	'up_img_max_height'	=> {$up_img_max_height}, 			//allowed maximum height of uploadable image\n"
       ."	'img_full_width'	=> {$img_full_width}, 			//allowed maximum width of converted image\n"
       ."	'img_full_height'	=> {$img_full_height}, 			//allowed maximum height of converted image\n"
       ."	'img_thumb_width'	=> {$img_thumb_width}, 			//max thumbnail width of converted image\n"
       ."	'img_thumb_height'	=> {$img_thumb_height}, 			//max thumbnail height of converted image\n"
       ."	'img_slide_width'	=> {$img_slide_width}, 			//max slide width of converted image\n"
       ."	'img_slide_height'	=> {$img_slide_height}, 			//max slide height of converted image\n"
       ."	'jpg_quality'		=> {$jpg_quality}, 				//JPEG quality\n"
       ."	'png_compress'		=> {$png_compress}, 				//PNG quality. 0: no compress, min:0, max:9\n"
       ."	'wm_use'			=> {$wm_use}, 				//use watermark\n"
       ."	'wm_position'		=> {$wm_position}, //watermark position\n"
       ."	'debug'				=> false 			//true for debug mode\n"
       ."];\n";
   $config_file_data .= "?>\n";

   $fp = fopen(CONFIG_FILE, "w");
   if (!$fp) {
      $error = true;
      $arr = error_get_last();
      throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
   } else {
      fwrite($fp, $config_file_data);
      fclose($fp);
   }

   $definition_file_data = "<?php\n\n";
   $definition_file_data .= ""
       ."\$protocol = isset(\$_SERVER['HTTPS']) && \$_SERVER['HTTPS'] === 'on' ? \"https://\" : \"http://\";\n\n"
       ."define(\"TMP_DIR\", \"$tmp_dir\");\n"
       ."define(\"TMP_THUMB_DIR\", \"$tmp_thumb_dir\");\n"
       ."define(\"TMP_THUMB_C_DIR\", \"$tmp_thumb_c_dir\");\n"
       ."define(\"TMP_SLIDE_DIR\", \"$tmp_slide_dir\");\n\n"
       ."define(\"IMG_BASE_DIR\", \"$img_base_dir\");\n"
       ."define(\"IMG_DIR\", \"$img_dir\");\n"
       ."define(\"IMG_THUMB_DIR\", \"$img_thumb_dir\");\n"
       ."define(\"IMG_THUMB_C_DIR\", \"$img_thumb_c_dir\");\n"
       ."define(\"IMG_SLIDE_DIR\", \"$img_slide_dir\");\n\n"
       ."define(\"XML_DIR\", \"$xml_dir\");\n\n"
       ."define(\"IMG_BASE_URL\", \$protocol.\"{$_SERVER['HTTP_HOST']}\".\"".str_replace($_SERVER['DOCUMENT_ROOT'], '', $img_base_dir)."\");\n"
       ."define(\"IMG_URL\", \$protocol.\"{$_SERVER['HTTP_HOST']}\".\"".str_replace($_SERVER['DOCUMENT_ROOT'], '', $img_dir)."\");\n"
       ."define(\"IMG_THUMB_URL\", \$protocol.\"{$_SERVER['HTTP_HOST']}\".\"".str_replace($_SERVER['DOCUMENT_ROOT'], '', $img_thumb_dir)."\");\n"
       ."define(\"IMG_THUMB_C_URL\", \$protocol.\"{$_SERVER['HTTP_HOST']}\".\"".str_replace($_SERVER['DOCUMENT_ROOT'], '', $img_thumb_c_dir)."\");\n"
       ."define(\"IMG_SLIDE_URL\", \$protocol.\"{$_SERVER['HTTP_HOST']}\".\"".str_replace($_SERVER['DOCUMENT_ROOT'], '', $img_slide_dir)."\");\n\n"
       ."define(\"WM_TOP_LEFT\", 0);\n"
       ."define(\"WM_TOP_CENTER\", 1);\n"
       ."define(\"WM_TOP_RIGHT\", 2);\n"
       ."define(\"WM_LEFT_MIDDLE\", 3);\n"
       ."define(\"WM_CENTER\", 4);\n"
       ."define(\"WM_RIGHT_MIDDLE\", 5);\n"
       ."define(\"WM_BOTTOM_LEFT\", 6);\n"
       ."define(\"WM_BOTTOM_CENTER\", 7);\n"
       ."define(\"WM_BOTTOM_RIGHT\", 8);\n\n"
       ."";
   $definition_file_data .= "?>\n";

   $fp = fopen(DEFINITIONS_FILE, "w");
   if (!$fp) {
      $error = true;
      $arr = error_get_last();
      throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
   } else {
      fwrite($fp, $definition_file_data);
      fclose($fp);
   }


   if (!file_exists($xml_dir)) {
      if (!mkdir($xml_dir, 0755, true)) {
         $error = true;
         $arr = error_get_last();
         throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
      }
   }
   if (!file_exists($tmp_dir)) {
      if (!mkdir($tmp_dir, 0755, true)) {
         $error = true;
         $arr = error_get_last();
         throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
      }
   }
   if (!file_exists($tmp_thumb_dir)) {
      if (!mkdir($tmp_thumb_dir, 0755, true)) {
         $error = true;
         $arr = error_get_last();
         throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
      }
   }
   if (!file_exists($tmp_thumb_c_dir)) {
      if (!mkdir($tmp_thumb_c_dir, 0755, true)) {
         $error = true;
         $arr = error_get_last();
         throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
      }
   }
   if (!file_exists($tmp_slide_dir)) {
      if (!mkdir($tmp_slide_dir, 0755, true)) {
         $error = true;
         $arr = error_get_last();
         throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
      }
   }
   if (!file_exists($img_base_dir)) {
      if (!mkdir($img_base_dir, 0755, true)) {
         $error = true;
         $arr = error_get_last();
         throw new Exception(date(DATE_RFC3339).' '.basename(__FILE__).' line '.__LINE__.': '.$arr['message'].PHP_EOL);
      }
   }

} catch(Exception $e) {
   $error_log = $e->getMessage();
}


if (!$error) {
   echo 'ok';
} else {
   $fp = fopen(ERROR_LOG_FILE, "a");
   if (!$fp) {
      echo 'error';
   } else {
      fwrite($fp, $error_log);
      fclose($fp);
      echo 'Some errors have occurred. Check out the '.basename(ERROR_LOG_FILE).' file for details.';
   }
}

if ($db_connect) {
   mysqli_close($db_connect);
}

?>
