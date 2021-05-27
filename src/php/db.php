<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_database = 'dhbwSocial';

$db = @ new mysqli($db_host, $db_user, $db_pass, "mysql");

$res = $db->query("CREATE DATABASE IF NOT EXISTS ".$db_database);
mysqli_select_db($db, $db_database);
$db->set_charset("utf8");
// Create tables
$db->query("CREATE TABLE IF NOT EXISTS `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(100) NOT NULL UNIQUE,
    `password` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `avatar` varchar(100) DEFAULT NULL,
    `banner` varchar(100) DEFAULT NULL,
    `description` varchar(160) DEFAULT NULL,
    `verified` boolean DEFAULT false,
    `registerDate` DATETIME DEFAULT NOW(),
    `notificationUpdateTime` DATETIME DEFAULT NOW(),
    PRIMARY KEY (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

$db->query("CREATE TABLE IF NOT EXISTS `post` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `userID` int(11) NOT NULL,
    `referencedPostID` int(11) DEFAULT NULL,
    `content` varchar(280) DEFAULT NULL,
    `media` varchar(100) DEFAULT NULL,
    `postDate` DATETIME DEFAULT NOW(),
    `deleted` BOOLEAN DEFAULT 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`userID`) REFERENCES user(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`referencedPostID`) REFERENCES post(`id`) ON DELETE NO ACTION 
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

$db->query("CREATE TABLE IF NOT EXISTS `follows` (
    `userID` int(11) NOT NULL,
    `following` int(11) NOT NULL,
    `followDate` DATETIME DEFAULT NOW(),
    PRIMARY KEY (`userID`, `following`), 
    FOREIGN KEY (`userID`) REFERENCES user(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`following`) REFERENCES user(`id`) ON DELETE CASCADE
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

$db->query("CREATE TABLE IF NOT EXISTS `feedback` (
    `userID` int(11) NOT NULL,
    `postID` int(11) NOT NULL,
    `like` boolean NOT NULL,
    `likedDate` DATETIME DEFAULT NOW(),
    PRIMARY KEY (`userID`, `postID`),
    FOREIGN KEY (`userID`) REFERENCES user(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`postID`) REFERENCES post(`id`) ON DELETE CASCADE
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

// TODO add notification selection krebs
$db->query("DROP VIEW IF EXISTS `notificationView`");

/* Types of Notifications:
        - User liked/disliked a post
        - User got new follower
        - User got tagged
        - User got reply
        - User the user followed posted something
*/
$db->query("CREATE VIEW `notificationView` AS
            SELECT 'sss' AS `message`, now() AS `time`, username, id AS userID
            FROM user");

?>