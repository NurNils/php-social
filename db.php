<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_database = 'dhbwSocial';

$db = @ new mysqli($db_host, $db_user, $db_pass, "mysql");

$res = $db->query("CREATE DATABASE IF NOT EXISTS ".$db_database);
mysqli_select_db($db, $db_database);
$db->set_charset("utf8");

//Check if table exists
$db->query("CREATE TABLE IF NOT EXISTS `login` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(100) NOT NULL,
    `passwd` varchar(100) NOT NULL,
    `email` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4");
?>