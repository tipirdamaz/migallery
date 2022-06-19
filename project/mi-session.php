<?php 

$session_time = 30; //minute

error_reporting (E_ALL & ~E_WARNING & ~E_NOTICE);
if (isset($_GET['sessid']) && !preg_match('/^([a-z0-9]+)$/', $_GET['sessid'], $match)) exit();
if (isset($_POST['sessid']) && !preg_match('/^([a-z0-9]+)$/', $_POST['sessid'], $match)) exit();
if (isset($_COOKIE['PHPSESSID']) && !preg_match('/^([a-z0-9]+)$/', $_COOKIE['PHPSESSID'], $match)) exit();

if(!(isset($_GET['sessid'])||isset($_POST['sessid'])||isset($_COOKIE['PHPSESSID']))||($_GET['sessid']=='undefined'||$_POST['sessid']=='undefined'||$_COOKIE['PHPSESSID']=='undefined'))
{
   //session_cache_limiter('public');
   session_cache_limiter('nocache');
   session_cache_expire ($session_time);
   session_start();
   $cparam = session_get_cookie_params();  
   $sessid = session_id();

    if (PHP_VERSION_ID < 70300) {
        setcookie('PHPSESSID', $sessid, time()+60*$session_time, $cparam['path']."; samesite=strict", $cparam['domain'], true, true);
    }
    else {
        setcookie('PHPSESSID', $sessid, [
            'expires' => time()+60*$session_time,
            'path' => $cparam['path'],
            'domain' => $cparam['domain'],
            'samesite' => 'strict',
            'secure' => true,
            'httponly' => true,
        ]);
    }
}
else
{
   if(isset($_GET['sessid'])) $sessid = $_GET['sessid'];
   else if(isset($_POST['sessid'])) $sessid = $_POST['sessid'];
   else $sessid = $_COOKIE['PHPSESSID'];
   {
      //session_cache_limiter('public');
      session_cache_limiter('nocache');
      session_cache_expire ($session_time);
      session_id($sessid);
      session_start();
   }
}
?>
