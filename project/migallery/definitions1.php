<?php

/**
* When the image is uploaded, first full size, thumbnail and slide images are created in the TMP_DIR. After the gallery is saved, the gallery id is taken and a new folder with this name is created in IMG_DIR and the images are moved here.
* 
* For example, when the file named abcdef123456789.jpg is uploaded, it is moved to the TMP_DIR.
Let's say gallery info is inserted to database with 128 id. After that, the file is moved to the IMG_DIR/128/128-abcdef123456789.jpg folder.
*/

/* DB_DIR is defined in the definitions0.php file.*/

define("TMP_DIR", DB_DIR.'/tmp'); // created full size image here
define("TMP_THUMB_DIR", TMP_DIR.'/thumb'); // created thumbnail image with aspect ratio here
define("TMP_THUMB_C_DIR", TMP_DIR.'/thumb_c'); // created cropped thumbnail image here
define("TMP_SLIDE_DIR", TMP_DIR.'/slide'); // created slide image here

define("IMG_BASE_DIR", DB_DIR.'/img');
define("IMG_DIR", IMG_BASE_DIR.'/%d'); // %d is gallery id
define("IMG_THUMB_DIR", IMG_DIR.'/thumb');
define("IMG_THUMB_C_DIR", IMG_DIR.'/thumb_c');
define("IMG_SLIDE_DIR", IMG_DIR.'/slide');

define("XML_DIR", DB_DIR.'/xml');

define("IMG_BASE_URL", DB_URL.'/img');
define("IMG_URL", IMG_BASE_URL.'/%d'); // %d is gallery id
define("IMG_THUMB_URL", IMG_URL.'/thumb');
define("IMG_THUMB_C_URL", IMG_URL.'/thumb_c');
define("IMG_SLIDE_URL", IMG_URL.'/slide');

//watermark positions
define("WM_TOP_LEFT", 0);
define("WM_TOP_CENTER", 1);
define("WM_TOP_RIGHT", 2);
define("WM_LEFT_MIDDLE", 3);
define("WM_CENTER", 4);
define("WM_RIGHT_MIDDLE", 5);
define("WM_BOTTOM_LEFT", 6);
define("WM_BOTTOM_CENTER", 7);
define("WM_BOTTOM_RIGHT", 8);

?>