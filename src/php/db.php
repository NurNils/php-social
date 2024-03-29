<?php
/**
 * File: db.php
 * MySQL database authorization and creation with all needed tables
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_database = 'dhbwSocial';
$t = 1;

$db = @new mysqli($db_host, $db_user, $db_pass, 'mysql');
$res = $db->query('CREATE DATABASE IF NOT EXISTS ' . $db_database);
mysqli_select_db($db, $db_database);
$db->set_charset('utf8');

// Creates "user" table if not exists
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

// Creates "post" table if not exists
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

// Creates "follows" table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `follows` (
    `userID` int(11) NOT NULL,
    `following` int(11) NOT NULL,
    `followDate` DATETIME DEFAULT NOW(),
    PRIMARY KEY (`userID`, `following`), 
    FOREIGN KEY (`userID`) REFERENCES user(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`following`) REFERENCES user(`id`) ON DELETE CASCADE
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

// Creates "feedback" table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `feedback` (
    `userID` int(11) NOT NULL,
    `postID` int(11) NOT NULL,
    `like` boolean NOT NULL,
    `likedDate` DATETIME DEFAULT NOW(),
    PRIMARY KEY (`userID`, `postID`),
    FOREIGN KEY (`userID`) REFERENCES user(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`postID`) REFERENCES post(`id`) ON DELETE CASCADE
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

// Creates "chat" table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `chat` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user1` int(11) NOT NULL,
    `user2` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user1`) REFERENCES user(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user2`) REFERENCES user(`id`) ON DELETE CASCADE
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

// Creates "message" table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `message` (
    `chatID` int(11) NOT NULL,
    `userID` int(11) NOT NULL,
    `content` VARCHAR(1000) NOT NULL,
    `date` DATETIME DEFAULT NOW(),
    FOREIGN KEY (`chatID`) REFERENCES chat(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`userID`) REFERENCES user(`id`) ON DELETE CASCADE
   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4");

// Drops "notificationView" view if exists
$db->query('DROP VIEW IF EXISTS `notificationView`');
// Creates "notificationView" view to show user notifications (user liked/disliked a post, new follower, tagged by somebody, got a reply or following user posted new stuff)
$db->query("CREATE VIEW `notificationView` AS
            SELECT user.id AS `userID`, follows.followDate AS `time`, sUser.username, 'folgt dir jetzt' AS `message` FROM user
            INNER JOIN follows ON follows.following = user.id AND follows.followDate > user.notificationUpdateTime
            INNER JOIN user sUser ON sUser.id = follows.userID
            GROUP BY user.id
            UNION ALL
            SELECT user.id AS `userID`, post.postDate AS `time`, sUser.username, 'hat etwas gepostet' AS `message` FROM user
            INNER JOIN follows ON follows.userID = user.id
            INNER JOIN post ON post.userID = follows.following AND post.postDate > user.notificationUpdateTime AND post.postDate > follows.followDate
            INNER JOIN user sUser ON sUser.id = follows.following
            GROUP BY user.id
            UNION ALL
            SELECT user.id AS `userID`, comment.postDate AS `time`, sUser.username, 'hat auf einen Post von dir geantwortet' AS `message` FROM user
            INNER JOIN post ON post.userID = user.id
            INNER JOIN post comment ON comment.referencedPostID = post.id AND comment.postDate > user.notificationUpdateTime AND comment.userID != user.id
            INNER JOIN user sUser ON sUser.id = comment.userID
            GROUP BY user.id
            UNION ALL
            SELECT user.id AS `userID`, post.postDate AS `time`, sUser.username, 'hat dich in einem Post erwähnt' AS `message` FROM user
            INNER JOIN post ON post.content REGEXP CONCAT(CONCAT('(?<= |^)(@', user.username), ')(?= |$)') AND post.postDate > user.notificationUpdateTime AND post.userID != user.id
            INNER JOIN user sUser ON sUser.id = post.userID
            GROUP BY user.id
            UNION ALL
            SELECT user.id AS `userID`, feedback.likedDate AS `time`, sUser.username, 
                IF(feedback.like = 1, 'hat deinen Post geliked', 'hat deinen Post gedisliked')
                AS `message` FROM user
            INNER JOIN post ON post.userID = user.id
            INNER JOIN feedback ON feedback.postID = post.id AND feedback.likedDate > user.notificationUpdateTime AND feedback.userID != user.id
            INNER JOIN user sUser ON sUser.id = feedback.userID
            GROUP BY user.id, post.id");
?>
