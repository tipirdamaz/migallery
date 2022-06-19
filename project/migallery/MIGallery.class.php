<?php

/**
 * MIGallery - Image gallery creation and editing class.
 * PHP Version 5.5+, 7, 8
 *
 * @author    İbrahim Tıpırdamaz <itipirdamaz@gmail.com>
 * @copyright 2021 İbrahim Tıpırdamaz
 */


class MIGallery {
   
   static $db_conn;
   static $db_gal_table;
   static $db_img_table;
   static $img_dir;
   static $img_thumb_dir;
   static $img_thumb_c_dir;
   static $img_slide_dir;
   static $jpg_quality;
   static $png_compress;
   static $img_thumb_width;
   static $img_thumb_height;
   static $img_slide_width;
   static $img_slide_height;
   static $img_full_width;
   static $img_full_height;
   static $max_upload_limit;
   static $time;
   static $filename;
   static $error;
   static $debug;
   static $error_log;
   static $gal_id;

   const OK = 200;
   const ERROR = 500;


   /**
   * Initialize MIGallery API
   * 
   * If $gal_id is not NULL, it is prepared for editing an existing gallery.
   * Otherwise it is prepared to create a new gallery.
   * 
   * @param {Array} $config 	: from CONFIG_FILE
   * @param {Resource} $db_conn : mysqli connection
   * @param {Integer} $gal_id 	: gallery id
   */
   public static function init($config, $db_conn, $gal_id=NULL)
   {
      self::$db_conn = $db_conn;

      // gallery selected
      self::$gal_id = $gal_id;

      if ($gal_id != NULL) {
         self::defineDirNames();
	  }

      self::$db_gal_table = $config['db_gal_table'];
      self::$db_img_table = $config['db_img_table'];
      self::$jpg_quality = $config['jpg_quality'];
      self::$png_compress = $config['png_compress'];
      self::$img_thumb_width = $config['img_thumb_width'];
      self::$img_thumb_height = $config['img_thumb_height'];
      self::$img_slide_width = $config['img_slide_width'];
      self::$img_slide_height = $config['img_slide_height'];
      self::$img_full_width = $config['img_full_width'];
      self::$img_full_height = $config['img_full_height'];
      self::$max_upload_limit = $config['max_upload_limit'];
      self::$debug = $config['debug'];
      self::$error = false;
      self::$filename = basename(__FILE__);
      self::$time = time();
   }


   /**
   * @param {String}
   * 
   * @return {String}
   */
   public static function escapeString($str)
   {
      $str = strip_tags($str);
      $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
      $str = self::$db_conn->real_escape_string($str);
      return $str;
   }


   /**
   * @param {String}
   * 
   * @return {String}
   */
   public static function escapeKeywords($str)
   {
      $str = strip_tags($str);
      $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
      $str = str_replace(["\r\n","\n\r","\r","\n",'\r\n','\n\r','\r','\n'], ', ', $str);
      $str = preg_replace('/(,\s*)+/', ', ', $str);
      $str = trim($str, ', ');
      $str = self::$db_conn->real_escape_string($str);
      return $str;
   }


   /**
   * @param {String}
   * 
   * @return {String}
   */
   public static function unescapeString($str)
   {
      $str = str_replace('\r', "\r", $str);
      $str = str_replace('\n', "\n", $str);
      $str = stripslashes($str);
      return $str;
   }


   /**
   * This method assigns the image information to the session variable passed as a reference and returns the gallery information.
   * 
   * @param {Array} $imgs_info_sess : Session variable where image information is stored
   * 
   * @return {Array} $galleryInfo 	: Gallery info
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function initUploader(&$imgs_info_sess)
   {
      $galleryInfo = array();
      try {
         $query = "SELECT * FROM ".self::$db_gal_table." WHERE id=".self::$gal_id;
         $db_result = self::$db_conn->query($query);
         if (!$db_result) { //error
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': Get gallery info : '.self::$db_conn->error.PHP_EOL);
            }
         } else {
            $row = $db_result->fetch_assoc();
            foreach($row as $key => $val) {
               $galleryInfo[$key] = self::unescapeString($val);
			}
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }

      try {
         $query = 'SELECT img_name, img_ext, img_title, img_desc, img_keyw, img_ts FROM '.self::$db_img_table.' WHERE gid='.self::$gal_id.' '
                 .'ORDER BY sort LIMIT 0,'.self::$max_upload_limit;
         $db_result = self::$db_conn->query($query);
         if (!$db_result) { //error
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': Get image info : '.self::$db_conn->error.PHP_EOL);
            }
         } else {
            for ($i=0; $row = $db_result->fetch_assoc(); $i++) {
               $imgs_info_sess[$i] = (object) [
                  'name'  => $row['img_name']
                 ,'ext'   => $row['img_ext']
                 ,'title' => self::unescapeString($row['img_title'])
                 ,'desc'  => self::unescapeString($row['img_desc'])
                 ,'keyw'  => self::unescapeString($row['img_keyw'])
                 ,'time'  => $row['img_ts']
                 ,'up'    => 'exist'
               ];
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
      return $galleryInfo;
   }


   /**
   * 
   * This method selects the gallery to be operated on.
   * 
   * @param {Integer} $gal_id : gallery id
   * 
   */
   public static function selectGallery($gal_id)
   {
      self::$gal_id = $gal_id; // gallery selected
      self::defineDirNames();
   }


   /**
   * 
   * This method defines directories according to the directory format defined in the DEFINITIONS_FILE file.
   * 
   */
   public static function defineDirNames()
   {
      self::$img_dir = sprintf(IMG_DIR, self::$gal_id);
      self::$img_thumb_dir = sprintf(IMG_THUMB_DIR, self::$gal_id);
      self::$img_thumb_c_dir = sprintf(IMG_THUMB_C_DIR, self::$gal_id);
      self::$img_slide_dir = sprintf(IMG_SLIDE_DIR, self::$gal_id);
   }


   /**
   * 
   * This method inserts the gallery information into the database.
   * 
   * @param {String} $gal_name			: Gallery name
   * @param {String} $gal_description	: Gallery description
   * @param {String} $gal_keywords		: Gallery keywords for seo
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function insertGalleryInfoToDB($gal_name, $gal_description, $gal_keywords)
   {
   	  $time = self::$time;
      $db_conn = self::$db_conn;
   	  $db_gal_table = self::$db_gal_table;
   	  
      $gal_name = self::escapeString($gal_name);
      $gal_description = self::escapeString($gal_description);
      $gal_keywords = self::escapeKeywords($gal_keywords);

   	  if (self::$gal_id != NULL) return;

      try {
         $query = "SELECT MAX(sort) FROM $db_gal_table";
         $db_result = $db_conn->query($query);
         $row = $db_result->fetch_assoc();
         if ($row['MAX(sort)'] == '' || $row['MAX(sort)'] == NULL) {
            $max_sort = 1;
         } else {
            $max_sort = $row['MAX(sort)'] + 1;
		 }
         
         $query = "INSERT INTO $db_gal_table VALUES(0, \"$gal_name\", \"$gal_description\", \"$gal_keywords\", ".$time.", $max_sort)";
         $db_result = $db_conn->query($query);
         if (!$db_result) { //error
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': insertGalleryInfoToDB : '.$db_conn->error.PHP_EOL);
            }
         } else {
            self::$gal_id = $db_conn->insert_id;
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * 
   * This method updates the gallery information in the database.
   * 
   * @param {String} $gal_name			: Gallery name
   * @param {String} $gal_description	: Gallery description
   * @param {String} $gal_keywords		: Gallery keywords for seo
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function updateGalleryInfoInDB($gal_name, $gal_description, $gal_keywords)
   {
      $db_conn = self::$db_conn;
   	  $db_gal_table = self::$db_gal_table;
      $gal_id = self::$gal_id;

      $gal_name = self::escapeString($gal_name);
      $gal_description = self::escapeString($gal_description);
      $gal_keywords = self::escapeKeywords($gal_keywords);

      try {
         $query = "update $db_gal_table set name=\"$gal_name\", description=\"$gal_description\", keywords=\"$gal_keywords\" where id=".$gal_id;
         $db_result = $db_conn->query($query);
         if ($db_conn->affected_rows<0) { //error
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': updateGalleryInfoInDB : '.$db_conn->error.PHP_EOL);
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * This method creates folders for the gallery where images will be stored.
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function createImageDirs()
   {
      try {
         if (!file_exists(self::$img_dir)) {
            if (!mkdir(self::$img_dir, 0755, true)) {
               self::$error = true;
               if (self::$debug) {
                  $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImageDirs : '.$arr['message'].PHP_EOL);
               }
            }
            if (!mkdir(self::$img_thumb_dir, 0755, true)) {
               self::$error = true;
               if (self::$debug) {
                  $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImageDirs/thumb : '.$arr['message'].PHP_EOL);
               }
            }
            if (!mkdir(self::$img_thumb_c_dir, 0755, true)) {
               self::$error = true;
               if (self::$debug) {
                  $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImageDirs/thumb_c : '.$arr['message'].PHP_EOL);
               }
            }
            if (!mkdir(self::$img_slide_dir, 0755, true)) {
               self::$error = true;
               if (self::$debug) {
                  $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImageDirs/slide : '.$arr['message'].PHP_EOL);
               }
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * This method inserts the image information to the database.
   * 
   * @param {Object} $imgInfo		: Image info
   * @param {Integer} $sortIndex	: Image number in the gallery
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function insertImageInfoToDB($imgInfo, $sortIndex)
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;

      try {
         $img_name = $imgInfo->name;
         $img_ext = $imgInfo->ext;
         $img_title = $imgInfo->title;
         $img_desc = $imgInfo->desc;
         $img_keyw = $imgInfo->keyw;
         $img_time = time(); //$imgInfo->time;

         $img_title = self::escapeString($img_title);
         $img_desc = self::escapeString($img_desc);
         $img_keyw = self::escapeKeywords($img_keyw);

         $file_info = self::getImageFileInfo($gal_id.'-'.$img_name.'.'.$img_ext);

         $query = "SELECT id FROM $db_img_table WHERE img_name=\"".$gal_id.'-'.$img_name."\" and gid=".$gal_id;
         $db_result = $db_conn->query($query);
         $row = $db_result->fetch_assoc();
         if ($row['id'] == '') {
            $query = "INSERT INTO $db_img_table VALUES("
                    ."0, $gal_id, \"$img_title\", \"$img_desc\", \"$img_keyw\", "
                    ."\"".$gal_id.'-'.$img_name."\", \"".$img_ext."\", "
                    ."\"{$file_info['full_width']}\", \"{$file_info['full_height']}\", \"{$file_info['full_size']}\", "
                    ."\"{$file_info['slide_width']}\", \"{$file_info['slide_height']}\", \"{$file_info['slide_size']}\", "
                    ."\"{$file_info['thumb_width']}\", \"{$file_info['thumb_height']}\", \"{$file_info['thumb_size']}\", "
                    ."\"{$file_info['thumb_c_width']}\", \"{$file_info['thumb_c_height']}\", \"{$file_info['thumb_c_size']}\", "
                    ."\"$img_time\", ".$sortIndex
                    .")";
            $db_result = $db_conn->query($query);
            ///if ($db_conn->affected_rows<0) {
            if (!$db_result) {
               self::$error = true;
               if (self::$debug) {
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': insertImageInfoToDB : '.$db_conn->error.PHP_EOL);
               }
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * This method updates the image information in the database.
   * 
   * @param {Object} $imgInfo		: Image information
   * @param {Integer} $sortIndex	: Image number in the gallery
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function updateImageInfoInDB($imgInfo, $sortIndex)
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;

      try {
         //$img_name = $gal_id.'-'.preg_replace('/^'.$gal_id.'\-/', '', $imgInfo->name);
         $img_name = $imgInfo->name;
         $img_ext = $imgInfo->ext;
         $img_title = $imgInfo->title;
         $img_desc = $imgInfo->desc;
         $img_keyw = $imgInfo->keyw;

         $img_title = self::escapeString($img_title);
         $img_desc = self::escapeString($img_desc);
         $img_keyw = self::escapeKeywords($img_keyw);

         $query = "update $db_img_table set img_title=\"$img_title\", img_desc=\"$img_desc\", img_keyw=\"$img_keyw\", sort=$sortIndex where img_name=\"$img_name\" and gid=".$gal_id;
         $db_result = $db_conn->query($query);
         if (!$db_result) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': updateImageInfoInDB : '.$db_conn->error.PHP_EOL);
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * 
   * @param {Array} $imgs_info_sess : Session variable where image information is stored
   * 
   */
   public static function insertUpdateImageInfoInDB(&$imgs_info_sess)
   {
      for ($i=0; $i<count($imgs_info_sess) && $i<self::$max_upload_limit; $i++)
      {
         if ($imgs_info_sess[$i]->up == 'new') {
            self::insertImageInfoToDB($imgs_info_sess[$i], $i+1);
         } else if ($imgs_info_sess[$i]->up == 'exist') {
            self::updateImageInfoInDB($imgs_info_sess[$i], $i+1);
         }
      }
   }


   /**
   * This method moves images from the temporary folders to the folders created for the gallery.
   * 
   * @param {Array} $imgs_info_sess : Session variable where image information is stored
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function moveImagesFromTempToDir(&$imgs_info_sess)
   {
      $gal_id = self::$gal_id;

      try {
         for ($i=0; $i<count($imgs_info_sess); $i++)
         {
            if ($imgs_info_sess[$i]->up == 'new') {
               $img_file_name = $imgs_info_sess[$i]->name.'.'.$imgs_info_sess[$i]->ext;
               if (file_exists(TMP_DIR.'/'.$img_file_name)) {
                  if (!rename(TMP_DIR.'/'.$img_file_name, self::$img_dir.'/'.$gal_id.'-'.$img_file_name)) {
                     self::$error = true;
                     if (self::$debug) {
                        $arr = error_get_last();
                        throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': moveImagesFromTempToDir : '.$arr['message'].PHP_EOL);
                     }
                  }
			   }
               if (file_exists(TMP_THUMB_DIR.'/'.$img_file_name)) {
                  if (!rename(TMP_THUMB_DIR.'/'.$img_file_name, self::$img_thumb_dir.'/'.$gal_id.'-'.$img_file_name)) {
                     self::$error = true;
                     if (self::$debug) {
                        $arr = error_get_last();
                        throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': moveImagesFromTempToDir/thumb : '.$arr['message'].PHP_EOL);
                     }
                  }
			   }
               if (file_exists(TMP_THUMB_C_DIR.'/'.$img_file_name)) {
                  if (!rename(TMP_THUMB_C_DIR.'/'.$img_file_name, self::$img_thumb_c_dir.'/'.$gal_id.'-'.$img_file_name)) {
                     self::$error = true;
                     if (self::$debug) {
                        $arr = error_get_last();
                        throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': moveImagesFromTempToDir/thumb_c : '.$arr['message'].PHP_EOL);
                     }
                  }
			   }
               if (file_exists(TMP_SLIDE_DIR.'/'.$img_file_name)) {
                  if (!rename(TMP_SLIDE_DIR.'/'.$img_file_name, self::$img_slide_dir.'/'.$gal_id.'-'.$img_file_name)) {
                     self::$error = true;
                     if (self::$debug) {
                        $arr = error_get_last();
                        throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': moveImagesFromTempToDir/slide : '.$arr['message'].PHP_EOL);
                     }
                  }
			   }
		    }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * This method reads the image file from the disk and returns some image information.
   * 
   * @param {String} $img_file_name	: Image file name
   * 
   * @return {Array}				: Image information
   * 
   */
   public static function getImageFileInfo($img_file_name)
   {
      $file = self::$img_dir.'/'.$img_file_name;
      $file_slide = self::$img_slide_dir.'/'.$img_file_name;
      $file_tn = self::$img_thumb_dir.'/'.$img_file_name;
      $file_tn_c = self::$img_thumb_c_dir.'/'.$img_file_name;
      if (file_exists($file)) {
         $img_size = getimagesize($file);
         $img_w = $img_size[0];
         $img_h = $img_size[1];
         $file_size = filesize($file);
      } else {
         $img_w = 0;
         $img_h = 0;
         $file_size = 0;
      }	
      if (file_exists($file_slide)) {
         $img_size = getimagesize($file_slide);
         $img_slide_w = $img_size[0];
         $img_slide_h = $img_size[1];
         $file_slide_size = filesize($file_slide);
      } else {
         $img_slide_w = 0;
         $img_slide_h = 0;
         $file_slide_size = 0;
      }	
      if (file_exists($file_tn)) {
         $img_size=getimagesize($file_tn);
         $img_tn_w = $img_size[0];
         $img_tn_h = $img_size[1];
         $file_tn_size = filesize($file_tn);
      } else {
         $img_tn_w = 0;
         $img_tn_h = 0;
         $file_tn_size = 0;
      }	
      if (file_exists($file_tn_c)) {
         $img_size=getimagesize($file_tn_c);
         $img_tn_c_w = $img_size[0];
         $img_tn_c_h = $img_size[1];
         $file_tn_c_size = filesize($file_tn_c);
      } else {
         $img_tn_c_w = 0;
         $img_tn_c_h = 0;
         $file_tn_c_size = 0;
      }
      
      return [
         'full_width' => $img_w
        ,'full_height' => $img_h
        ,'full_size' => $file_size
        ,'slide_width' => $img_slide_w
        ,'slide_height' => $img_slide_h
        ,'slide_size' => $file_slide_size
        ,'thumb_width' => $img_tn_w
        ,'thumb_height' => $img_tn_h
        ,'thumb_size' => $file_tn_size
        ,'thumb_c_width' => $img_tn_c_w
        ,'thumb_c_height' => $img_tn_c_h
        ,'thumb_c_size' => $file_tn_c_size
      ];
   }


   /**
   * This method adds and / or updates the gallery and image information to the XML file created for the gallery.
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function insertUpdateGalleryAndImageInfoInXMLFile()
   {
      $db_conn = self::$db_conn;
      $db_gal_table = self::$db_gal_table;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;

      try {

         $galleryInfo = self::getGalleryInfoFromDB();

         if (count($galleryInfo) > 0) 
         {
            $gal_name = $galleryInfo['name'];
            $gal_desc = $galleryInfo['desc'];
            $gal_keyw = $galleryInfo['keyw'];;
            $gal_time = $galleryInfo['time'];

            $xmldata = "<?xml version=\"1.0\" encoding=\"utf-8\"?>".PHP_EOL.PHP_EOL."<gallery>".PHP_EOL;

            $xmldata .= "<info>".PHP_EOL
                     ."<name><![CDATA[".$gal_name."]]></name>".PHP_EOL
                     ."<desc><![CDATA[".$gal_desc."]]></desc>".PHP_EOL
                     ."<keyw><![CDATA[".$gal_keyw."]]></keyw>".PHP_EOL
                     ."<time>".$gal_time."</time>".PHP_EOL
                     ."</info>".PHP_EOL;

            $images = self::getImageInfoFromDB();

            for ($i=0; $i<count($images); $i++)
            {
               $xmldata .= "<image>".PHP_EOL
                        ."<file>".$images[$i]['file']."</file>".PHP_EOL
                        ."<full width=\"{$images[$i]['full_width']}\" height=\"{$images[$i]['full_height']}\" filesize=\"{$images[$i]['full_size']}\"></full>".PHP_EOL
                        ."<slide width=\"{$images[$i]['slide_width']}\" height=\"{$images[$i]['slide_height']}\" filesize=\"{$images[$i]['slide_size']}\"></slide>".PHP_EOL
                        ."<thumb width=\"{$images[$i]['thumb_width']}\" height=\"{$images[$i]['thumb_height']}\" filesize=\"{$images[$i]['thumb_size']}\"></thumb>".PHP_EOL
                        ."<thumb_c width=\"{$images[$i]['thumb_c_width']}\" height=\"{$images[$i]['thumb_c_height']}\" filesize=\"{$images[$i]['thumb_c_size']}\"></thumb_c>".PHP_EOL
                        ."<title><![CDATA[".$images[$i]['title']."]]></title>".PHP_EOL
                        ."<desc><![CDATA[".$images[$i]['desc']."]]></desc>".PHP_EOL
                        ."<keyw><![CDATA[".$images[$i]['keyw']."]]></keyw>".PHP_EOL
                        ."<time>".$images[$i]['time']."</time>".PHP_EOL
                        ."</image>".PHP_EOL;
            }
            
            $xmldata .= "</gallery>";

            $fp = fopen(XML_DIR.'/'.$gal_id.'.xml', "w");
            if (!$fp) {
               self::$error = true;
               if (self::$debug) {
                  $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': insertUpdateGalleryAndImageInfoInXMLFile : '.$arr['message'].PHP_EOL);
               }
            }
            fwrite($fp, $xmldata);
            fclose($fp);
         }

      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }
   

   /**
   * It inserts gallery information to database.
   * It inserts image information of this gallery, which is uploaded and stored in the $_SESSION['imgs_info'] variable, to the database.
   * It creates image folders according to the gallery id and moves images from temporary folders to these folders.
   * Creates the xml file containing the information of the gallery and images.
   * 
   * Called from ajax-gallery-insert.php
   * 
   * @param {String} $gal_name			: Gallery name
   * @param {String} $gal_description	: Gallery description
   * @param {String} $gal_keywords		: Gallery keywords
   * @param {Array} $imgs_info_sess		: Session variable where image information is stored ($_SESSION['imgs_info'])
   * 
   */
   public static function galleryInsert($gal_name, $gal_description, $gal_keywords, &$imgs_info_sess)
   {
      self::insertGalleryInfoToDB($gal_name, $gal_description, $gal_keywords);
      self::defineDirNames();
      self::createImageDirs();
      self::moveImagesFromTempToDir($imgs_info_sess);
      self::insertUpdateImageInfoInDB($imgs_info_sess);
      self::insertUpdateGalleryAndImageInfoInXMLFile();
   }


   /**
   * It updates the selected gallery's information in the database.
   * It updates the gallery and image information in the XML file of the gallery.
   * 
   * Called from : ajax-gallery-update.php
   * 
   * @param {String} $gal_name			: Gallery name
   * @param {String} $gal_description	: Gallery description
   * @param {String} $gal_keywords		: Gallery keywords
   * 
   */
   public static function galleryUpdate($gal_name, $gal_description, $gal_keywords)
   {
      self::updateGalleryInfoInDB($gal_name, $gal_description, $gal_keywords);
      self::insertUpdateGalleryAndImageInfoInXMLFile();
   }


   /**
   * Sorts the galleries in the database
   * 
   * Called from : ajax-gallery-sort.php
   * 
   * @param {Array} $gals_sort
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function gallerySort($gals_sort)
   {
      $db_conn = self::$db_conn;
      $db_gal_table = self::$db_gal_table;

      try {
         for ($i=0; $i<count($gals_sort->ids); $i++) {
            $query = "UPDATE $db_gal_table set sort=".$gals_sort->sort[$i]." WHERE id=".$gals_sort->ids[$i];
            $db_result = $db_conn->query($query);
            if (!$db_result) { //error
            //if ($db_conn->affected_rows<0) { //error
               self::$error = true;
               if (self::$debug) {
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': gallerySort : '.$db_conn->error.PHP_EOL);
               }
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }
   

   /**
   * Returns the number of images in the gallery.
   * 
   * @return {Integer} $img_count
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function getImageCountFromGallery()
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;

      $img_count = 0;

      try {
         /* Get image count from XML file is exist */
         if (file_exists(XML_DIR.'/'.$gal_id.'.xml')) {
            $gallery = simplexml_load_file(XML_DIR.'/'.$gal_id.'.xml', 'SimpleXMLElement', LIBXML_NOCDATA); 
            $img_count = count($gallery->image);
         } else { // Else get image count from DB
            $query = "SELECT count(id) "
                    ."FROM $db_img_table "
                    ."WHERE gid=".$gal_id." "
                    ."LIMIT 0,".self::$max_upload_limit;
            $db_result = $db_conn->query($query);

            if (!$db_result) {
               self::$error = true;
               if (self::$debug) {
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': getImageCountFromGallery : '.$db_conn->error.PHP_EOL);
               }
            } else {
               $img_count = $row['count(id)'];
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
      
      return $img_count;
   }


   /**
   * Returns gallery information from the database.
   * 
   * @return {Array} $galleryInfo
   * 
   * In case of failure, the empty array is returned and / or the self::$error flag set to true.
   */
   public static function getGalleryInfoFromDB()
   {
      $db_conn = self::$db_conn;
      $db_gal_table = self::$db_gal_table;
      $gal_id = self::$gal_id;
      
      $galleryInfo = [];

      try {
         $query = "SELECT id, name, description, keywords, ts "
                 ."FROM $db_gal_table "
                 ."WHERE id=$gal_id";
         $db_result = $db_conn->query($query);

         if (!$db_result) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': getGalleryInfoFromDB : '.$db_conn->error.PHP_EOL);
            }
         } else {
            $row = $db_result->fetch_assoc();
            if ($row['id'] != '') {
               $galleryInfo = [
                  'name' => self::unescapeString($row['name'])
                 ,'desc' => self::unescapeString($row['description'])
                 ,'keyw' => self::unescapeString($row['keywords'])
                 ,'time' => $row['ts']
               ];
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }

      return $galleryInfo;
   }


   /**
   * Returns image information from the database for the selected gallery.
   * 
   * @return {Array} $imageInfo
   * 
   * In case of failure, the empty array is returned and / or the self::$error flag set to true.
   */
   public static function getImageInfoFromDB()
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;

      $imageInfo = [];

      try {
         $query = "SELECT * "
                 ."FROM $db_img_table "
                 ."WHERE gid=".$gal_id." "
                 ."ORDER BY sort LIMIT 0,".self::$max_upload_limit;
         $db_result = $db_conn->query($query);

         if (!$db_result) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': getImageInfoFromDB : '.$db_conn->error.PHP_EOL);
            }
         } else {
            for ($i=0; $row = $db_result->fetch_assoc(); $i++)
            {
               $imageInfo[$i]['file'] = $row['img_name'].'.'.$row['img_ext'];
               $imageInfo[$i]['title'] = self::unescapeString($row['img_title']);
               $imageInfo[$i]['desc'] = self::unescapeString($row['img_desc']);
               $imageInfo[$i]['keyw'] = self::unescapeString($row['img_keyw']);
               $imageInfo[$i]['full_width'] = $row['full_width'];
               $imageInfo[$i]['full_height'] = $row['full_height'];
               $imageInfo[$i]['full_size'] = $row['full_size'];
               $imageInfo[$i]['thumb_width'] = $row['thumb_width'];
               $imageInfo[$i]['thumb_height'] = $row['thumb_height'];
               $imageInfo[$i]['thumb_size'] = $row['thumb_size'];
               $imageInfo[$i]['thumb_c_width'] = $row['thumb_c_width'];
               $imageInfo[$i]['thumb_c_height'] = $row['thumb_c_height'];
               $imageInfo[$i]['thumb_c_size'] = $row['thumb_c_size'];
               $imageInfo[$i]['slide_width'] = $row['slide_width'];
               $imageInfo[$i]['slide_height'] = $row['slide_height'];
               $imageInfo[$i]['slide_size'] = $row['slide_size'];
               $imageInfo[$i]['time'] = $row['img_ts'];
               $imageInfo[$i]['sort'] = $row['sort'];
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
      
      return $imageInfo;
   }


   /**
   * Returns gallery and image information from the database for the selected gallery.
   * 
   * @return {Array} $galAndImageInfo : Gallery and image info
   * 
   * In case of failure, the empty array is returned and / or the self::$error flag set to true.
   */
   public static function getGalleryAndImageInfo()
   {
      $db_conn = self::$db_conn;
      $db_gal_table = self::$db_gal_table;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;

      $galAndImageInfo = [];
      $images = [];

      try {
         if (@file_exists(XML_DIR.'/'.$gal_id.'.xml')) { // Get gallery and image info from XML file is exist
            $gallery = @simplexml_load_file(XML_DIR.'/'.$gal_id.'.xml', 'SimpleXMLElement', LIBXML_NOCDATA); 
            $gal_name = $gallery->info->name;
            $gal_desc = $gallery->info->desc;
            $gal_keyw = $gallery->info->keyw;
            $gal_time = $gallery->info->time;
   
            for ($i=0; $i<count($gallery->image); $i++) {
               $images[$i]['file'] = $gallery->image[$i]->file;
               $images[$i]['title'] = $gallery->image[$i]->title;
               $images[$i]['desc'] = $gallery->image[$i]->desc;
               $images[$i]['keyw'] = $gallery->image[$i]->keyw;
               $images[$i]['time'] = $gallery->image[$i]->time;
               $images[$i]['full_width'] = $gallery->image[$i]->full->attributes()['width'];
               $images[$i]['full_height'] = $gallery->image[$i]->full->attributes()['height'];
               $images[$i]['full_size'] = $gallery->image[$i]->full->attributes()['filesize'];
               $images[$i]['thumb_c_width'] = $gallery->image[$i]->thumb_c->attributes()['width'];
               $images[$i]['thumb_c_height'] = $gallery->image[$i]->thumb_c->attributes()['height'];
            }

            $galAndImageInfo = [
                      'name' => $gal_name
                     ,'desc' => $gal_desc
                     ,'keyw' => $gal_keyw
                     ,'time' => $gal_time
                     ,'imgs' => $images
            ];
         } else { // Else get gallery and image info from DB

            $galleryInfo = self::getGalleryInfoFromDB();
            
            if (count($galleryInfo) > 0) 
            {
               $gal_name = $galleryInfo['name'];
               $gal_desc = $galleryInfo['desc'];
               $gal_keyw = $galleryInfo['keyw'];;
               $gal_time = $galleryInfo['time'];
            
               $images = self::getImageInfoFromDB();

               $galAndImageInfo = [
                         'name' => $gal_name
                        ,'desc' => $gal_desc
                        ,'keyw' => $gal_keyw
                        ,'time' => $gal_time
                        ,'imgs' => $images
               ];

			} else {
               self::$error = true;
               if (self::$debug) {
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': getGalleryAndImageInfo error '.PHP_EOL);
               }
			}
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
      
      return $galAndImageInfo;
   }


   /**
   * Returns the number of galleries from the gallery table of database
   * 
   * @return {Integer} $gal_count : gallery count
   * 
   * In case of failure, 0 is returned and / or the self::$error flag set to true.
   */
   public static function getGalleryCount()
   {
      $db_conn = self::$db_conn;
      $db_gal_table = self::$db_gal_table;

      $gal_count = 0;

      try {
         $query = "select count(id) from $db_gal_table";
         $db_result = $db_conn->query($query);

         if (!$db_result) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': getGalleryCount : '.$db_conn->error.PHP_EOL);
            }
         } else {
            $row = $db_result->fetch_assoc();
            $gal_count = $row['count(id)'];
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
      
      return $gal_count;
   }


   /**
   * Returns the list of galleries from the gallery table of database
   * 
   * @param {Integer} $start_record_num	: Starting record number
   * @param {Integer} $result_per_page	: Result per page for gallery listing on web page
   * @param {String} $order				: ascending or descending order
   * 
   * @return {Array} $gals				: Gallery list
   * 
   * In case of failure, the empty array is returned and / or the self::$error flag set to true.
   */
   public static function getGalleryList($start_record_num, $result_per_page, $order)
   {
      $db_conn = self::$db_conn;
      $db_gal_table = self::$db_gal_table;
      $db_img_table = self::$db_img_table;

      $gals = [];

      try {
         $query = "SELECT $db_gal_table.id, $db_gal_table.name, $db_gal_table.description, $db_gal_table.keywords, $db_gal_table.ts, $db_gal_table.sort, $db_img_table.img_name, $db_img_table.img_ext "
                 ."FROM $db_gal_table "
                 ."left outer join $db_img_table on ($db_gal_table.id=$db_img_table.gid AND $db_img_table.sort=1) " // cover photo
                 ."order by $db_gal_table.sort $order "
		         ."limit $start_record_num, $result_per_page";
         $db_result = $db_conn->query($query);

         if (!$db_result) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': getGalleryList : '.$db_conn->error.PHP_EOL);
            }
         } else {
            for($i=0; $row = $db_result->fetch_assoc(); $i++) 
            {
               $gals[$i]['id'] = $row['id'];
               $gals[$i]['name'] = self::unescapeString($row['name']);
               $gals[$i]['desc'] = self::unescapeString($row['description']);
               $gals[$i]['keyw'] = self::unescapeString($row['keywords']);
               $gals[$i]['time'] = $row['ts'];
               $gals[$i]['thumb'] = $row['img_name'].'.'.$row['img_ext'];
               if ($gals[$i]['thumb'] == '.') $gals[$i]['thumb'] = '';
               $gals[$i]['sort'] = $row['sort'];
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
      
      return $gals;
   }


   /**
   * This method deletes the Image information from the database.
   * 
   * @param {String} $img_name_del	: File name to be deleted
   * @param {String} $img_ext_del	: File extension to be deleted
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function imageInfoDeleteFromDB($img_name_del, $img_ext_del, &$imgs_info_sess)
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;
      
      $img_name_del = $gal_id.'-'.preg_replace('/^'.$gal_id.'\-/', '', $img_name_del);

      try {
         $query = "DELETE FROM $db_img_table WHERE gid=$gal_id AND img_name=\"".$img_name_del."\" AND img_ext=\"".$img_ext_del."\"";
         $db_result = $db_conn->query($query);
         ///if ($db_conn->affected_rows<0) {
         if (!$db_result) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageInfoDeleteFromDB : '.$db_conn->error.PHP_EOL);
            }
         } else {
            $ind = self::findImageIndex($imgs_info_sess, $img_name_del);
            if($ind >= 0){
               array_splice($imgs_info_sess, $ind, 1);
            }
		 }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * This method deletes the multiple Image information from the database.
   * 
   * @param {Array} $imgs_del		: File names to be deleted
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function imageInfoDeleteMultiFromDB($imgs_del, &$imgs_info_sess)
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;
      
      $img_names = '';

      foreach ($imgs_del as $img_file_name) {
        list($img_name, $img_ext) = explode('.', $img_file_name);
        $img_names .= "'".$gal_id.'-'.preg_replace('/^'.$gal_id.'\-/', '', $img_name)."',";
      }

      $img_names = rtrim($img_names, ',');

      try {
         $query = "DELETE FROM $db_img_table WHERE gid=$gal_id AND img_name in ($img_names)";
         $db_result = $db_conn->query($query);
         ///if ($db_conn->affected_rows<0) {
         if (!$db_result) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageInfoDeleteMultiFromDB : '.$db_conn->error.PHP_EOL);
            }
         } else {
            foreach ($imgs_del as $img_file_name) {
               list($img_name, $img_ext) = explode('.', $img_file_name);
               $img_name = $gal_id.'-'.preg_replace('/^'.$gal_id.'\-/', '', $img_name);
               $ind = self::findImageIndex($imgs_info_sess, $img_name);
               if($ind >= 0){
                  array_splice($imgs_info_sess, $ind, 1);
               }
            }
		 }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * It decodes the json-encoded image information sent from the client and returns it as an array.
   * 
   * @param {String} $json_post	: Json encoded post data
   * @param {Boolean} $assoc
   * 
   * @return {Array} $arr_post	: Decoded image info
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function postInfoJsonDecode($json_post, $assoc=false)
   {
      try {
         $arr_post = json_decode($json_post, $assoc);
         //$arr_post = json_decode(stripslashes($json_post), $assoc);
         if ($arr_post == FALSE || $arr_post == NULL) {
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': postInfoJsonDecode: '.json_last_error_msg().PHP_EOL);
            }
         }
   
         return $arr_post;
   
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * Reorders the images in the database according to the order in the client.
   * 
   * @param {Array} $imgs_info_sess : Session variable where image information is stored
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function imageInfoReorderInDB(&$imgs_info_sess)
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;
      
      try {
         for ($i=0; $i<count($imgs_info_sess); $i++) 
         {
            $img_title = $imgs_info_sess[$i]->title;
            $img_desc = $imgs_info_sess[$i]->desc;
            $img_keyw = $imgs_info_sess[$i]->keyw;

            $img_title = self::escapeString($img_title);
            $img_desc = self::escapeString($img_desc);
            $img_keyw = self::escapeKeywords($img_keyw);

            $img_name = $gal_id.'-'.preg_replace('/^'.$gal_id.'\-/', '', $imgs_info_sess[$i]->name);
            $img_ext = $imgs_info_sess[$i]->ext;
            
            $sortIndex = $i+1;
            
            $query = "update $db_img_table set sort=$sortIndex, img_title=\"".$img_title."\", img_desc=\"".$img_desc."\", img_keyw=\"".$img_keyw."\" where img_name=\"".$img_name."\" and img_ext=\"".$img_ext."\" and gid=".$gal_id;
            
            $db_result = $db_conn->query($query);
            ///if ($db_conn->affected_rows<0) {
            if (!$db_result) {
               self::$error = true;
               if (self::$debug) {
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageInfoReorderInDB : '.$db_conn->error.PHP_EOL);
               }
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * Images uploaded during gallery insert are first moved to temporary folders. 
   * If the image is deleted before saving the gallery, it will be deleted from the temporary folder.
   * 
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * @param {String} $img_name_del	: File name to be deleted
   * @param {String} $img_ext_del	: File extension to be deleted
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function imageFileDeleteFromTemp(&$imgs_info_sess, $img_name_del, $img_ext_del)
   {

      try {
         if(file_exists(TMP_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(TMP_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromTemp : '.$arr['message'].PHP_EOL);
               }
            }
         }
         if(file_exists(TMP_SLIDE_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(TMP_SLIDE_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromTemp:slide : '.$arr['message'].PHP_EOL);
               }
            }
         }
         if(file_exists(TMP_THUMB_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(TMP_THUMB_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromTemp:thumb : '.$arr['message'].PHP_EOL);
               }
            }
         }
         if(file_exists(TMP_THUMB_C_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(TMP_THUMB_C_DIR.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromTemp:thumb_c : '.$arr['message'].PHP_EOL);
               }
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }

      $ind = self::findImageIndex($imgs_info_sess, $img_name_del);
      if($ind >= 0){
         array_splice($imgs_info_sess, $ind, 1);
      }
   }


   /**
   * Deletes the image file from the directory of the saved gallery.
   * 
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * @param {String} $img_name_del	: File name to be deleted
   * @param {String} $img_ext_del	: File extension to be deleted
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function imageFileDeleteFromDir(&$imgs_info_sess, $img_name_del, $img_ext_del)
   {
      $gal_id = self::$gal_id;

      try {
         $img_name_del = $gal_id.'-'.preg_replace('/^'.$gal_id.'\-/', '', $img_name_del);
         if(file_exists(self::$img_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(self::$img_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromDir : '.$arr['message'].PHP_EOL);
               }
            }
         }
         if(file_exists(self::$img_slide_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(self::$img_slide_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromDir/slide : '.$arr['message'].PHP_EOL);
               }
            }
         }
         if(file_exists(self::$img_thumb_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(self::$img_thumb_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromDir/thumb : '.$arr['message'].PHP_EOL);
               }
            }
         }
         if(file_exists(self::$img_thumb_c_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
            if (!unlink(self::$img_thumb_c_dir.'/'.$img_name_del.'.'.$img_ext_del)) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteFromDir/thumb_c : '.$arr['message'].PHP_EOL);
               }
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
	  
      $ind = self::findImageIndex($imgs_info_sess, $img_name_del);
      if($ind >= 0){
         array_splice($imgs_info_sess, $ind, 1);
      }
   }


   /**
   * Deletes the image files from the directory of the saved gallery.
   * 
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * @param {Array} $imgs_del		: File names to be deleted
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function imageFileDeleteMultiFromDir(&$imgs_info_sess, $imgs_del)
   {
      $gal_id = self::$gal_id;

      try {
         foreach ($imgs_del as $img) 
         {
            $img = $gal_id.'-'.preg_replace('/^'.$gal_id.'\-/', '', $img);
            if(file_exists(self::$img_dir.'/'.$img)) {
               if (!unlink(self::$img_dir.'/'.$img)) {
                  self::$error = true;
                  if (self::$debug) {
	                 $arr = error_get_last();
                     throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteMultiFromDir : '.$arr['message'].PHP_EOL);
                  }
	      	 }
      	  }
            if(file_exists(self::$img_slide_dir.'/'.$img)) {
               if (!unlink(self::$img_slide_dir.'/'.$img)) {
                  self::$error = true;
                  if (self::$debug) {
	                 $arr = error_get_last();
                     throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteMultiFromDir/slide : '.$arr['message'].PHP_EOL);
                  }
	      	 }
	        }
            if(file_exists(self::$img_thumb_dir.'/'.$img)) {
               if (!unlink(self::$img_thumb_dir.'/'.$img)) {
                  self::$error = true;
                  if (self::$debug) {
	                 $arr = error_get_last();
                     throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteMultiFromDir/thumb : '.$arr['message'].PHP_EOL);
                  }
		       }
	        }
            if(file_exists(self::$img_thumb_c_dir.'/'.$img)) {
               if (!unlink(self::$img_thumb_c_dir.'/'.$img)) {
                  self::$error = true;
                  if (self::$debug) {
	                 $arr = error_get_last();
                     throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': imageFileDeleteMultiFromDir/thumb_c : '.$arr['message'].PHP_EOL);
                  }
		       }
	        }

            list($img_name, $img_ext) = explode('.', $img);
	  
            $ind = self::findImageIndex($imgs_info_sess, $img_name);
            if($ind >= 0){
               array_splice($imgs_info_sess, $ind, 1);
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * Assigns the image information posted from the client to the session variable.
   * 
   * @param {Array} $imgs_info_post	: Latest information of the all images posted from client
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * 
   */
   public static function imageInfoSaveToSession($imgs_info_post, &$imgs_info_sess)
   {
      foreach ($imgs_info_post as $k => $v) {
         $imgs_info_sess[$k] = clone $v;
      }
      
      $imgs_sess_length = count($imgs_info_sess);
      $imgs_post_length = count($imgs_info_post);
      if ($imgs_sess_length > $imgs_post_length) {
         array_splice($imgs_info_sess, $imgs_post_length, $imgs_sess_length - $imgs_post_length);
	  }
   }


   /**
   * Updates the information on the server according to the image information posted from the client.
   * 
   * Called from: ajax-image-info-post.php
   * 
   * @param {Array} $imgs_info_post	: Latest information of the all images posted from client
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * 
   */
   public static function imageInfoUpdate($imgs_info_post, &$imgs_info_sess)
   {
      self::imageInfoSaveToSession($imgs_info_post, $imgs_info_sess);
      self::moveImagesFromTempToDir($imgs_info_sess);
      self::insertUpdateImageInfoInDB($imgs_info_sess);
      self::imageInfoReorderInDB($imgs_info_sess);
      self::insertUpdateGalleryAndImageInfoInXMLFile();

      for ($i=0; $i<count($imgs_info_sess); $i++) {
         $imgs_info_sess[$i]->up = 'exist';
      }
   }


   /**
   * Searches for the image name within the session array variable. If it finds it, it returns its index.
   * If not found it returns -1
   * 
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * @param {String} $name			: Image name
   * 
   * @return {Integer} $key			: Index of the found image in the array
   */
   public static function findImageIndex($imgs_info_sess, $name) {
      foreach ($imgs_info_sess as $key => $val) {
         if ($val->name === $name) {
            return $key;
         }
      }
      return -1;
   }


   /**
   * Uploaded images are stored in temporary folders before the gallery is saved and id retrieved. In the meantime, this method is called if the uploaded image is to be deleted.
   * Images uploaded during gallery insert are first moved to temporary folder. 
   * If the image is deleted before saving the gallery, it will be deleted from the temporary folder.
   * Assigns the image information posted from the client to the session variable.
   * 
   * Called from: ajax-image-del-new-uploaded.php
   * 
   * @param {String} $img_name_del	: File name to be deleted, posted from client
   * @param {String} $img_ext_del	: File extension to be deleted, posted from client
   * @param {Array} $imgs_info_post	: Latest information of the all images posted from client
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * 
   */
   public static function imageDelNewUploadedFile($img_name_del, $img_ext_del, $imgs_info_post, &$imgs_info_sess)
   {
      $ind = self::findImageIndex($imgs_info_post, $img_name_del);
      if($ind >= 0){
         array_splice($imgs_info_post, $ind, 1);
      }

      self::imageInfoSaveToSession($imgs_info_post, $imgs_info_sess);
      self::imageFileDeleteFromTemp($imgs_info_sess, $img_name_del, $img_ext_del);
   }


   /**
   * It deletes the existing image from the server while editing the gallery.
   * 
   * Called from: ajax-image-del-existing.php
   * 
   * @param {String} $img_name_del	: File name to be deleted, posted from client
   * @param {String} $img_ext_del	: File extension to be deleted, posted from client
   * @param {Array} $imgs_info_post	: Latest information of the all images posted from client
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * 
   */
   public static function imageDelExistingFile($img_name_del, $img_ext_del, $imgs_info_post, &$imgs_info_sess)
   {
      $ind = self::findImageIndex($imgs_info_post, $img_name_del);
      if($ind >= 0){
         array_splice($imgs_info_post, $ind, 1);
      }

      self::imageInfoSaveToSession($imgs_info_post, $imgs_info_sess);
      self::imageInfoDeleteFromDB($img_name_del, $img_ext_del, $imgs_info_sess);
      self::imageInfoReorderInDB($imgs_info_sess);
      self::insertUpdateGalleryAndImageInfoInXMLFile();
      self::imageFileDeleteFromDir($imgs_info_sess, $img_name_del, $img_ext_del);
   }


   /**
   * It deletes the existing multiple image from the server while editing the gallery.
   * 
   * Called from: ajax-image-del-existing-multi.php
   * 
   * @param {Array} $imgs_fname_post: Array of images to be deleted posted from the client
   * @param {Array} $imgs_info_post	: Latest information of the all images posted from client
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * 
   */
   public static function imageDelExistingFilesMulti($imgs_fname_post, $imgs_info_post, &$imgs_info_sess)
   {
      foreach ($imgs_fname_post as $img) {
        list($img_name, $img_ext) = explode('.', $img);
        $ind = self::findImageIndex($imgs_info_post, $img_name);
        if($ind >= 0){
           array_splice($imgs_info_post, $ind, 1);
        }
      }

      self::imageInfoSaveToSession($imgs_info_post, $imgs_info_sess);
      self::imageInfoDeleteMultiFromDB($imgs_fname_post, $imgs_info_sess);
      self::imageInfoReorderInDB($imgs_info_sess);
      self::insertUpdateGalleryAndImageInfoInXMLFile();
      self::imageFileDeleteMultiFromDir($imgs_info_sess, $imgs_fname_post);
   }


   /**
   * Deletes selected gallery information from the database
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function galleryInfoDelFromDB()
   {
      $db_conn = self::$db_conn;
      $db_gal_table = self::$db_gal_table;
      $gal_id = self::$gal_id;

      try {
         $query = "DELETE FROM $db_gal_table WHERE id=$gal_id";
         $db_result = $db_conn->query($query);
         //if (!$db_result) { //error
         if ($db_conn->affected_rows<0) { //error
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': galleryInfoDelFromDB : '.$db_conn->error.PHP_EOL);
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * Deletes images information of the selected gallery from the database
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function galleryImageInfoDelFromDB()
   {
      $db_conn = self::$db_conn;
      $db_img_table = self::$db_img_table;
      $gal_id = self::$gal_id;

      try {
         $query = "DELETE FROM $db_img_table WHERE gid=$gal_id";
         $db_result = $db_conn->query($query);
         //if (!$db_result) { //error
         if ($db_conn->affected_rows<0) { //error
            self::$error = true;
            if (self::$debug) {
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': galleryImageInfoDelFromDB : '.$db_conn->error.PHP_EOL);
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * Deletes the xml file containing all the information of the selected gallery from the disk.
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function galleryXMLFileDelFromDir()
   {
      $gal_id = self::$gal_id;
      
      try {
         if(file_exists(XML_DIR.'/'.$gal_id.'.xml')) {
            if (!unlink(XML_DIR.'/'.$gal_id.'.xml')) {
               self::$error = true;
               if (self::$debug) {
	              $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': galleryXMLFileDelFromDir : '.$arr['message'].PHP_EOL);
               }
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * Deletes the directory specified in the parameter, along with all subdirectories and files in its content.
   * 
   * @param {String} $dirPath
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function deleteDir($dirPath) {
      try {
         if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
         }

         if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
         }

         $files = glob($dirPath . '*', GLOB_MARK);

         foreach ($files as $file) {
            if (is_dir($file)) {
               self::deleteDir($file);
            } else {
               if (!unlink($file)) {
                  self::$error = true;
                  if (self::$debug) {
                     $arr = error_get_last();
                     throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': '.$arr['message'].PHP_EOL);
                  }
               }
            }
         }

         if (!rmdir($dirPath)) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': '.$arr['message'].PHP_EOL);
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }
   }


   /**
   * Deletes the folder containing the images of the selected gallery together with its contents.
   * 
   */
   public static function galleryImageDelFromDir()
   {
      self::deleteDir(self::$img_dir);
   }


   /**
   * All data of the selected gallery on the server is deleted.
   * Called from ajax-gallery-del.php
   */
   public static function galleryDelete()
   {
      self::galleryImageDelFromDir();
      self::galleryXMLFileDelFromDir();
      self::galleryImageInfoDelFromDB();
      self::galleryInfoDelFromDB();
   }
   

   /**
   * Creates a new image file according to the aspect ratio of the source image.
   * 
   * @param {String} $sfile		: Source image file name
   * @param {String} $dfile		: The name of the target image file to be created
   * @param {String} $type		: Image type
   * @param {Integer} $width	: Width of target file to be created
   * @param {Integer} $height	: Height of target file to be created
   * @param {String} $watermark	: Watermark file name to be set in the middle of the image to be created
   * 
   * @return {Boolean}			: Returns true on success and false on failure
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function createImage($sfile, $dfile, $type, $width, $height, $watermark=NULL)
   {
      try {
         $img_size = @getimagesize ($sfile);
         if (!$img_size) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImage:getimagesize : '.$arr['message'].PHP_EOL);
            }
         }
         
         $sfileWidth = $img_size[0];
         $sfileHeight = $img_size[1];

         // If the size of the source image is smaller than the dimensions to be converted
         // fix dimensions
         
         $fixWidth = $width;
         $fixHeight = $height;
   
         if ($sfileWidth > $width) {
            $fixHeight = round(($width/$sfileWidth) * $sfileHeight);
   
            if ($fixHeight > $height) {
	           $fixWidth = ($height/$fixHeight)*$width;
		       $fixHeight = $height;
	        }
         }
         else if ($sfileHeight > $height) {
            $fixWidth = round(($height/$sfileHeight) * $sfileWidth);

            if ($fixWidth > $width) {
	           $fixHeight = ($width/$fixWidth)*$height;
		       $fixWidth = $width;
	        }
         }
         // fix dimensions

         switch ($type)
         {
         case "jpg" : $im = @imagecreatefromjpeg($sfile); break;
         case "gif" : $im = @imagecreatefromgif($sfile); break;
         case "png" : $im = @imagecreatefrompng($sfile); break;
         default: break;
         }

         if (!$im) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImage : '.$arr['message'].PHP_EOL);
            }
         }

         $re_im = @imagecreatetruecolor ($fixWidth, $fixHeight);

         if (!$re_im) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImage:imagecreatetruecolor : '.$arr['message'].PHP_EOL);
            }
         }

         /* black background fix */
         switch ($type)
         {
         case "png":
            $background = @imagecolorallocate($im, 255, 255, 255);
            @imagecolortransparent($im, $background);
            @imagealphablending($im, false);
            @imagesavealpha($im, true);
         break;
         case "gif":
            $background = @imagecolorallocate($im, 255, 255, 255);
            @imagecolortransparent($im, $background);
         break;
	     default : 
            $background = true;
	     break;
         }
         /* black background fix */

         if (!$background) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImage:imagecolorallocate : '.$arr['message'].PHP_EOL);
            }
         }
         
         $resampleIm = @imagecopyresampled($re_im, $im, 0, 0, 0, 0, $fixWidth, $fixHeight, $sfileWidth, $sfileHeight);

         if (!$resampleIm) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImage:imagecopyresampled : '.$arr['message'].PHP_EOL);
            }
         }

         if ($watermark != NULL)
         {
            $wm_size = getimagesize ($watermark);
            $wm = imagecreatefrompng($watermark);
            $x = round(($width - $wm_size[0])/2);
            $y = round(($height - $wm_size[1])/2);
	        imagecopy($re_im, $wm, $x, $y, 0, 0, $wm_size[0], $wm_size[1]);
            imagedestroy($wm);
         }

         switch ($type)
         {
         case "jpg" : $createIm = @imagejpeg($re_im, $dfile, self::$jpg_quality); break;
         case "gif" : $createIm = @imagegif($re_im, $dfile); break;
         case "png" : $createIm = @imagepng($re_im, $dfile, self::$png_compress); break;
         default: break;
         }

         if (!$createIm) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createImage : '.$arr['message'].PHP_EOL);
            }
         }

         @imagedestroy($im);
         @imagedestroy($re_im);
         
         return true;

      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
         return false;
      }
   }


   /**
   * Creates a scaled down, centered, and cropped thumbnail to match the thumbnail dimensions specified in the CONFIG_FILE file.
   * 
   * @param {String} $img_file			: Source image file name
   * @param {String} $thumb_c_img_file	: The name of the target cropped thumbnail image file to be created
   * @param {Integer} $thumb_w			: Width of target file to be created
   * @param {Integer} $thumb_h			: Height of target file to be created
   * @param {String} $type				: Image type
   * 
   * @return {String}:base64			: If $thumb_c_img_file is NULL, it returns a base64 encoded string of image.
   * @return {Boolean}					: Otherwise the image is written into the $thumb_c_img_file file. Returns true on success and false on failure
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function createCroppedThumb($img_file, $thumb_c_img_file, $thumb_w, $thumb_h, $type)
   {
      try {
         $img_size = @getimagesize ($img_file);
         if (!$img_size) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb:getimagesize : '.$arr['message'].PHP_EOL);
            }
         }

         $full_w = $img_size[0];
         $full_h = $img_size[1];

         if (($thumb_w/$thumb_h) > ($full_w/$full_h)) {
            $_thumb_w = $thumb_w;
            $_thumb_h = round($thumb_w*$full_h/$full_w);
            $c_thumb_x = 0;
            $c_thumb_y = round(($_thumb_h - $thumb_h)/2);
         } else {
            $_thumb_w = round($thumb_h*$full_w/$full_h);
            $_thumb_h = $thumb_h;
            $c_thumb_x = round(($_thumb_w - $thumb_w)/2);
            $c_thumb_y = 0;
         }
         $c_thumb_rect = array('x' => $c_thumb_x, 'y' => $c_thumb_y, 'width' => $thumb_w, 'height' => $thumb_h);

         switch ($type)
         {
         case "jpg" : $im = @imagecreatefromjpeg($img_file); break;
         case "gif" : $im = @imagecreatefromgif($img_file); break;
         case "png" : $im = @imagecreatefrompng($img_file); break;
         default: break;
         }

         if (!$im) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb : '.$arr['message'].PHP_EOL);
            }
         }

         $re_im = @imagecreatetruecolor ($_thumb_w, $_thumb_h);

         if (!$re_im) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb:imagecreatetruecolor : '.$arr['message'].PHP_EOL);
            }
         }

         /* black background fix */
         switch ($type)
         {
         case "png":
            $background = @imagecolorallocate($im, 255, 255, 255);
            @imagecolortransparent($im, $background);
            @imagealphablending($im, false);
            @imagesavealpha($im, true);
         break;
         case "gif":
            $background = @imagecolorallocate($im, 255, 255, 255);
            @imagecolortransparent($im, $background);
         break;
	     default : 
            $background = true;
	     break;
         }
         /* black background fix */

         if (!$background) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb:imagecolorallocate : '.$arr['message'].PHP_EOL);
            }
         }

         $resampleIm = @imagecopyresampled ($re_im, $im, 0, 0, 0, 0, $_thumb_w, $_thumb_h, $full_w, $full_h);

         if (!$resampleIm) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb:imagecopyresampled : '.$arr['message'].PHP_EOL);
            }
         }

         $im2 = @imagecrop($re_im, $c_thumb_rect);
         if (!$im2) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb:imagecrop : '.$arr['message'].PHP_EOL);
            }
         }

         if (!($thumb_c_img_file == '' || $thumb_c_img_file == NULL)) {
            switch ($type)
            {
            case "jpg" : $imgc_result = @imagejpeg($im2, $thumb_c_img_file, self::$jpg_quality); break;
            case "gif" : $imgc_result = @imagegif($im2, $thumb_c_img_file); break;
            case "png" : $imgc_result = @imagepng($im2, $thumb_c_img_file, self::$png_compress); break;
            default: break;
            }

            if (!$imgc_result) {
               self::$error = true;
               if (self::$debug) {
                  $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb : '.$arr['message'].PHP_EOL);
               }
            }
            
            @imagedestroy($im);
            @imagedestroy($re_im);
            @imagedestroy($im2);

            return true;

		 } else {

            ob_start();

            switch ($type)
            {
            case "jpg" : 
              $img_result = @imagejpeg($im2, NULL, self::$jpg_quality);
              $mime = 'image/jpeg';
            break;
            case "gif" : 
              $img_result = @imagegif($im2, NULL);
              $mime = 'image/gif';
            break;
            case "png" : 
              $img_result = @imagepng($im2, NULL, self::$png_compress);
              $mime = 'image/png';
              break;
            default: break;
            }

            if (!$img_result) {
               self::$error = true;
               if (self::$debug) {
                  $arr = error_get_last();
                  throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': createCroppedThumb : '.$arr['message'].PHP_EOL);
               }
            }

            $contents = ob_get_clean();

            @imagedestroy($im);
            @imagedestroy($re_im);
            @imagedestroy($im2);
            
            return "data:$mime;base64," . base64_encode($contents);
		 }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
         return false;
      }
   }


   /**
   * Creates a scaled down, centered, and cropped thumbnail to match the thumbnail dimensions specified in the CONFIG_FILE file
   * and returns a base64 encoded string of image.
   * 
   * @param {String} $img_file	: Source image file name
   * @param {Integer} $thumb_w	: Width of target file to be created
   * @param {Integer} $thumb_h	: Height of target file to be created
   * 
   * @return {String}			: Returns a base64 encoded string of image
   */
   public static function getCanvas($img_file, $thumb_w, $thumb_h)
   {
      $arr = explode('.', $img_file);
      $type = array_pop($arr);
      return self::createCroppedThumb($img_file, NULL, $thumb_w, $thumb_h, $type);
   }


   /**
   * Fetch all image-info from files list
   * 
   * @param {Array} $files
   * @param {Array} $images
   * @param {String} $name
   * 
   * @return
   */
   public static function fetchImages($files, &$images, $name = 'file')
   {
      if( isset($files['tmp_name']) ){
         $filename = $files['tmp_name'];
         list($mime) = explode(';', @mime_content_type($filename));

         if( strpos($mime, 'image') !== false ){
            $size = getimagesize($filename);
            $base64 = base64_encode(file_get_contents($filename));

            $images[$name] = array(
                  'width' => $size[0]
                , 'height' => $size[1]
                , 'mime' => $mime
                , 'size' => filesize($filename)
                , 'dataURL' => 'data:'. $mime .';base64,'. $base64
            );
         }
      }
      else {
         foreach( $files as $name => $file ){
            self::fetchImages($file, $images, $name);
         }
      }
   }


   /**
   * Full, slide and thumbnail copies of the uploaded file are created in the temporary folder.
   * 
   * Called from : ajax-image-upload.php
   * 
   * @param {Array} $filedata		: Uploaded file info
   * @param {Array} $imgs_info_sess	: Session variable where image information is stored
   * @param {String} $sessid		: PHPSESSID, session id for the current session
   * 
   * @return {Boolean}				: Returns true on success and false on failure
   * 
   * In case of failure, the self::$error flag set to true.
   */
   public static function imageUpload(&$filedata, &$imgs_info_sess, $sessid)
   {
      $img_name = substr(md5(unpack('H*',base64_encode(rawurlencode(substr($filedata['name'],0,32).$sessid)))[1]),0,14);

      switch ($filedata['type'])
      {
      case 'image/gif' : 
      case 'image/png' : $img_ext = 'png'; break;
      case 'image/jpeg' : 
      default: $img_ext = 'jpg'; break;
      }
		
      $img_file = $img_name.".".$img_ext;

      try {
         if (!move_uploaded_file($filedata['tmp_name'], TMP_DIR."/".$img_file)) {
            self::$error = true;
            if (self::$debug) {
               $arr = error_get_last();
               throw new Exception(date(DATE_RFC3339).' '.self::$filename.' line '.__LINE__.': move uploaded file : '.$arr['message'].PHP_EOL);
            }
         }
      } catch(Exception $e) {
         self::$error_log = $e->getMessage();
      }

      if(!self::createImage(TMP_DIR."/".$img_file, TMP_THUMB_DIR."/".$img_file, $img_ext, self::$img_thumb_width, self::$img_thumb_height)) {
	     return false;
	  }

      if(!self::createImage(TMP_DIR."/".$img_file, TMP_SLIDE_DIR."/".$img_file, $img_ext, self::$img_slide_width, self::$img_slide_height)) {
	     return false;
	  }

      $full_size = getimagesize (TMP_DIR."/".$img_file);
      if ($full_size[0]>self::$img_full_width && $full_size[1]>self::$img_full_height) {
         if (!self::createImage(TMP_DIR."/".$img_file, TMP_DIR."/_".$img_file, $img_ext, self::$img_full_width, self::$img_full_height)) {
		    return false;
		 }
         unlink(TMP_DIR."/".$img_file);
         rename(TMP_DIR."/_".$img_file, TMP_DIR."/".$img_file);
      }

      if(!self::createCroppedThumb(TMP_DIR."/".$img_file, TMP_THUMB_C_DIR."/".$img_file, self::$img_thumb_width, self::$img_thumb_height, $img_ext)) {
	     return false;
	  }


      // The uploaded file information is assigned to the session variable.

      $i = count($imgs_info_sess);

      $imgs_info_sess[$i] = (object) [
         'name'  => $img_name
        ,'ext'   => $img_ext
        ,'title' => ''
        ,'desc'  => ''
        ,'keyw'  => ''
        ,'time'  => time()
        ,'up'    => 'new'
      ];
   }

   
   /**
   * Returns the file size given in bytes as B, KB, MB, GB or TB.
   * 
   * @param {Integer} $size			: File size in bytes
   * @param {Integer} $precision	: precision
   * 
   * @return {Float}				: File size as B, KB, MB, GB or TB.
   */
   public static function formatBytes($size, $precision = 0)
   {
      $base = log($size, 1024);
      $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   
      return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
   }


   /**
   * Result of the actions
   * 
   * @return {Array}
   */
   public static function getResult()
   {
      if(!self::$error) {
         $code = self::OK;
         $result = self::$gal_id;
      } else {
         $code = self::ERROR;
         if (self::$debug) {
   	        $result = 'Some errors have occurred. Check out the '.basename(ERROR_LOG_FILE).' file for details.';
         }
         else $result = 'Something went wrong';
      }
      
      return [
         'code'   => $code
        ,'result' => $result
      ];
   }


   /**
   * If debug mode is on, errors are written to the ERROR_LOG_FILE file by calling the end() method.
   * 
   */
   public static function end()
   {
      if (self::$debug) {
         if (self::$error) {
            $fp = fopen(ERROR_LOG_FILE, "a");
            fwrite($fp, self::$error_log);
            fclose($fp);
         }
      }
   }
}

?>