<?php

/**
 * Translator - Image gallery language translator class.
 * PHP Version 5.5+, 7, 8
 *
 * @author    İbrahim Tıpırdamaz <itipirdamaz@gmail.com>
 * @copyright 2021 İbrahim Tıpırdamaz
 */

class Translator {
   
   static $lang;
   static $langTable = [
             'af' => ['Afrikaans', 'ltr', 'Y/m/d', ''],
             'ar' => ['عربى', 'rtl', 'd/m/Y', '٠١٢٣٤٥٦٧٨٩'],
             'az' => ['Azəri', 'ltr', 'd.m.Y', ''],
             'be' => ['Беларуская', 'ltr', 'd.m.Y', ''],
             'bg' => ['Български', 'ltr', 'd.n.Y', ''],
             'bn' => ['বাংলা ভাষা', 'ltr', 'd-m-y', ''],
             'bs' => ['Bosanski', 'ltr', 'j.n.Y', ''],
             'ca' => ['Català', 'ltr', 'd/m/Y', ''],
             'co' => ['Lingua Corsa', 'ltr', 'd/m/Y', ''],
             'cs' => ['Čeština', 'ltr', 'j.n.Y', ''],
             'cy' => ['Cymraeg', 'ltr', 'd/m/Y', ''],
             'da' => ['Dansk', 'ltr', 'd-m-Y', ''],
             'de' => ['Deutsche', 'ltr', 'd.m.Y', ''],
             'el' => ['Ελληνικά', 'ltr', 'j/n/Y', ''],
             'en' => ['English', 'ltr', 'n/j/Y', ''],
             'eo' => ['Esperanto', 'ltr', 'd/m/Y', ''],
             'es' => ['Español', 'ltr', 'd/m/Y', ''],
             'et' => ['Eestlane', 'ltr', 'j.m.Y', ''],
             'eu' => ['Euskara', 'ltr', 'Y/m/d', ''],
             'fa' => ['فارسی', 'rtl', 'm/d/Y', '۰۱۲۳۴۵۶۷۸۹'],
             'fi' => ['Suomalainen', 'ltr', 'j.n.Y', ''],
             'fr' => ['Français', 'ltr', 'd/m/Y', ''],
             'fy' => ['Fryske taal', 'ltr', 'j-n-Y', ''],
             'ga' => ['Gaeilge', 'ltr', 'd/m/Y', ''],
             'gl' => ['Galego', 'ltr', 'd/m/y', ''],
             'gu' => ['ગુજરાતી', 'ltr', 'd-m-y', ''],
             'he' => ['עִברִית', 'rtl', 'd/m/Y', ''],
             'hi' => ['हिंदी', 'ltr', 'd-m-Y', ''],
             'hr' => ['Hrvatski', 'ltr', 'j.n.Y', ''],
             'ht' => ['Lang ayisyen', 'ltr', 'd/m/Y', ''],
             'hu' => ['Magyar', 'ltr', 'Y. m. d.', ''],
             'hy' => ['հայերեն', 'ltr', 'd.m.Y', ''],
             'id' => ['Indonesia', 'ltr', 'd/m/Y', ''],
             'is' => ['Íslenska', 'ltr', 'j.n.Y', ''],
             'it' => ['Italiano', 'ltr', 'd/m/Y', ''],
             'ja' => ['日本人', 'ltr', 'Y/m/d', ''],
             'jv' => ['Wong jawa', 'ltr', 'Y/m/d', ''],
             'ka' => ['ქართველი', 'ltr', 'd.m.Y', ''],
             'kk' => ['Қазақ', 'ltr', 'd.m.Y', ''],
             'km' => ['ភាសារខ្មែរ', 'ltr', 'Y-m-d', ''],
             'kn' => ['ಕನ್ನಡ', 'ltr', 'd-m-y', ''],
             'ko' => ['한국어', 'ltr', 'Y-m-d', ''],
             'ku' => ['Kurdî', 'ltr', 'd.m.Y', ''],
             'ky' => ['Кыргызча', 'ltr', 'd.m.y', ''],
             'lb' => ['Lëtzebuergesch', 'ltr', 'd/m/Y', ''],
             'lt' => ['Lietuvis', 'ltr', 'Y.m.d', ''],
             'lv' => ['Latvietis', 'ltr', 'Y.m.d', ''],
             'mg' => ['Fiteny Madagasikara', 'ltr', 'd/m/Y', ''],
             'mi' => ['Maori', 'ltr', 'd/m/Y', ''],
             'mk' => ['Македонски', 'ltr', 'd.m.Y', ''],
             'ml' => ['മലയാള ഭാഷ', 'ltr', 'd-m-y', ''],
             'mn' => ['Монгол', 'ltr', 'y.m.d', ''],
             'mr' => ['मराठी', 'ltr', 'd-m-Y', ''],
             'ms' => ['Malaysia', 'ltr', 'd/m/Y', ''],
             'mt' => ['Malti', 'ltr', 'd/m/Y', ''],
             'my' => ['ဗမာဘာသာစကား', 'ltr', 'd-m-Y', '၀၁၂၃၄၅၆၇၈၉'],
             'ne' => ['नेपाली', 'ltr', 'n/j/Y', '०१२३४५६७८९'],
             'nl' => ['Nederlands', 'ltr', 'j-n-Y', ''],
             'no' => ['Norsk', 'ltr', 'd.m.Y', ''],
             'or' => ['ଓଡିଆ ଭାଷା |', 'ltr', 'd-m-y', '୦୧୨୩୪୫୬୭୮୯'],
             'pa' => ['ਪੰਜਾਬੀ', 'ltr', 'd-m-y', ''],
             'pl' => ['Polskie', 'ltr', 'Y-m-d', ''],
             'ps' => ['پښتو', 'rtl', 'd/m/y', ''],
             'pt' => ['Português', 'ltr', 'd-m-Y', ''],
             'ro' => ['Română', 'ltr', 'd.m.Y', ''],
             'ru' => ['Русский', 'ltr', 'd.m.Y', ''],
             'sd' => ['سنت ٻولي', 'rtl', 'd.m.Y', ''],
             'sk' => ['Slovák', 'ltr', 'j. n. Y', ''],
             'sl' => ['Slovenščina', 'ltr', 'j.n.Y', ''],
             'so' => ['Soomaali', 'ltr', 'd.m.Y', ''],
             'sq' => ['Shqiptare', 'ltr', 'Y-m-d', ''],
             'sr' => ['Српски', 'ltr', 'j.n.Y', ''],
             'sv' => ['Svenska', 'ltr', 'Y-m-d', ''],
             'sw' => ['Kiswahili', 'ltr', 'n/j/Y', ''],
             'ta' => ['தமிழ்', 'ltr', 'd-m-Y', ''],
             'te' => ['తెలుగు', 'ltr', 'd-m-y', ''],
             'th' => ['ไทย', 'ltr', 'j/n/Y', ''],
             'tk' => ['Türkmence', 'ltr', 'd.m.y', ''],
             'tl' => ['Filipino', 'ltr', 'd.m.Y', ''],
             'tr' => ['Türkçe', 'ltr', 'd.m.Y', ''],
             'tt' => ['Татар', 'ltr', 'd.m.Y', ''],
             'ug' => ['ئۇيغۇر تىلى', 'rtl', 'Y-n-j', ''],
             'uk' => ['Український', 'ltr', 'd.m.Y', ''],
             'ur' => ['اردو', 'rtl', 'd/m/Y', ''],
             'uz' => ['O\'zbek', 'ltr', 'd.m.Y', ''],
             'vi' => ['Tiếng Việt', 'ltr', 'd/m/Y', ''],
             'zh' => ['中文', 'ltr', 'Y/n/j', ''],
             'zu' => ['Zulu', 'ltr', 'Y/m/d', '']
   ];

   static $default_lang = 'en';
   static $lang_dir;
   static $lang_file; 

   /**
   * 
   * @param {String} $http_accept_lang
   * 
   */
   public static function init($http_accept_lang)
   {
      self::$lang_dir = dirname(__FILE__).'/lang/xml';
      $lang_2 = substr($http_accept_lang, 0, 2);
      $lang_3 = substr($http_accept_lang, 0, 3);
      self::$lang = in_array($lang_2, array_keys(self::$langTable)) ? $lang_2 : (in_array($lang_3, array_keys(self::$langTable)) ? $lang_3 : self::$default_lang);
      self::$lang_file = file_exists(self::$lang_dir.'/'.self::$lang.'.xml') ? self::$lang_dir.'/'.self::$lang.'.xml' : self::$lang_dir.'/'.self::$default_lang.'.xml';
   }

   /**
   * 
   * @param {String} $lang
   * 
   */
   public static function select($lang)
   {
      self::$lang_dir = dirname(__FILE__).'/lang/xml';
      self::$lang = $lang;
      self::$lang_file = file_exists(self::$lang_dir.'/'.self::$lang.'.xml') ? self::$lang_dir.'/'.self::$lang.'.xml' : self::$lang_dir.'/'.self::$default_lang.'.xml';
   }

   /**
   * 
   * 
   * @return {Array}
   */
   public static function getLangTable()
   {
      return self::$langTable;
   }

   /**
   * Get language code. e.g. 'en', 'tr', ...
   * 
   * @return {String}
   */
   public static function getLangCode()
   {
      return self::$lang;
   }

   /**
   * Get ltr or rtl
   * 
   * @return {String}
   */
   public static function getDirection()
   {
      return self::$langTable[self::$lang][1];
   }

   /**
   * Get language specific numbers. e.g. Arabic ٠١٢٣٤٥٦٧٨٩
   * 
   * @return {String}
   */
   public static function getLangNumbers()
   {
      return self::$langTable[self::$lang][3];
   }

   /**
   * Read language xml file and return
   * 
   * @return {Object}
   */
   public static function translate()
   {
      $xml = simplexml_load_file(self::$lang_file, 'SimpleXMLElement', LIBXML_NOCDATA);
      return $xml;
   }

   /**
   * Convert english numbers to language specific numbers.
   * 
   * @param {String} $num
   * 
   * @return {String}
   */
   public static function convertNumToLang($num)
   {
      $langNums = self::getLangNumbers();
      if ($langNums !== '') {
         $engNums = array('0','1','2','3','4','5','6','7','8','9');
         return str_replace($engNums, preg_split('//u', $langNums, null, PREG_SPLIT_NO_EMPTY), $num);
      }
	  return $num;
   }

   /**
   * Get locale date format
   * 
   * @return {String}
   */
   public static function getLocaleDateFormat()
   {
      return self::$langTable[self::$lang][2];
   }
}

?>