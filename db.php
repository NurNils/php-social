<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_database = 'login';

$db = @ new mysqli($db_host, $db_user, $db_pass, $db_database);
$db->set_charset("utf8");
?>