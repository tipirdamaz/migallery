<?php

//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db_conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
$db_conn->query("SET NAMES 'utf8', CHARACTER SET utf8, COLLATION_CONNECTION = 'utf8_unicode_ci'");

?>