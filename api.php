<?php
/**
 * File: api.php
 * Asynchon api calls from frontend
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
include('src/php/functions.php');
include('src/php/db.php');
session_start();
if(isset($_SESSION['user'])) {
    $userID = $_SESSION['user']->id;
    /* Endpoint for liking/disliking posts */
    if(isset($_GET['like']) && isset($_GET['postID'])) {
        $like = mysqli_real_escape_string($db, $_GET['like']);
        $postID = mysqli_real_escape_string($db, $_GET['postID']);
        if($like != "1" && $like != "0") die("Wrong parameters");
        $sql = "SELECT * FROM feedback WHERE `userID`=\"".$userID."\" AND `postID`=\"".$postID."\"";
        if($row = mysqli_fetch_object($db->query($sql))) {
            // User liked/disliked already
            if($like == $row->like) {
                // User liked again -> Delete feedback
                $sql = "DELETE FROM feedback WHERE `userID` = $userID AND `postID` = $postID";
            } else {
                // User changed like/dislike to dislike/like -> Update feedback
                $sql = "UPDATE feedback SET `userID` = $userID, `postID` = $postID, `like` = $like , `likedDate` = now() WHERE `userID` = $userID AND `postID` = $postID";
            }
        } else {
            // User did not like/dislike already -> Add feedback
            $sql = "INSERT INTO feedback (`userID`, `postID`, `like`) VALUES ($userID, $postID, $like)";
        }
        $db->query($sql);
    /* Endpoint for post deletion */
    } else if(isset($_GET['delete']) && isset($_GET['postID'])) {
        $postID = mysqli_real_escape_string($db, $_GET['postID']);
        checkDelete($postID, false);
    /* Endpoint for updating last notification time */
    } else if(isset($_GET['openNotification'])) {
        $sql = "UPDATE user SET `notificationUpdateTime` = now() WHERE `id` = $userID";
        $db->query($sql);
    /* Endpoint for new chat message */
    } else if(isset($_POST['chat']) && isset($_POST['message'])) {
        $chatID = mysqli_real_escape_string($db, $_POST['chat']);
        $message = mysqli_real_escape_string($db, $_POST['message']);

        $userID = $_SESSION['user']->id;

        $sql = "SELECT * FROM chat WHERE id = $chatID AND (user1 = $userID OR user2 = $userID)";
        if($row = mysqli_fetch_object($db->query($sql))) {
            $sql = "INSERT INTO `message` (`chatID`, `userID`, `content`) VALUES ($chatID, $userID, '$message')";
            $db->query($sql);
            echo("true");
        }
    /* Endpoint to get all new chat messages from point "lastMsg" */
    } else if(isset($_POST['chat']) && isset($_POST['lastMsg'])) {
        $chatID = mysqli_real_escape_string($db, $_POST['chat']);
        $lastMsg = mysqli_real_escape_string($db, $_POST['lastMsg']);
        $userID = $_SESSION['user']->id;

        $messages = "";
        $sql = "SELECT msg.*, user.id AS userID, user.username, user.verified, user.avatar FROM `message` msg
        INNER JOIN user ON user.id = msg.userID
        WHERE chatID = $chatID AND `date` > '". date('Y-m-d H:i:s' , $lastMsg) ."'
        ORDER BY msg.date ASC";
        $res = $db->query($sql);
        // Get all messages and send html
        while($row = mysqli_fetch_object($res)) {
            $user = new User($row);
            $userID = $_SESSION['user']->id;
            if($user->id == $userID) {
                $messages .= '<div class="right-msg msg">
                    <div class="msg-content-right">'.$row->content.'</div>
                </div>
                <i class="msg-time-right">'.prettyTime($row->date).'</i>';
            } else {
                $messages .= '<div class="left-msg msg">
                <img src="' . $user->getAvatar() . '" class="profile-pic-msg"/>
                    <div class="msg-content-left">'.$row->content.'</div>
                </div>
                <i class="msg-time-left">'.prettyTime($row->date).'</i>';
            }
            $lastMsg = max(strtotime($row->date), $lastMsg);
        }
        // Send html and the time of the last message
        echo(json_encode(array("html" => $messages, "lastMsg" => $lastMsg)));
    }
} else {
    echo('No permission');
}

/**
 * Check if post can be deleted
 * @param int $postID id for post
 * @param boolean $second for recursion
 */
function checkDelete($postID, $second) {
    global $db;
    $sql = "SELECT user.verified, user.id, COUNT(comments.referencedPostID) AS replycount, post.referencedPostID, post.deleted, post.media
    FROM user 
    INNER JOIN post ON user.id = post.userID
    LEFT JOIN post comments ON comments.referencedPostID = post.id 
    WHERE post.id = $postID
    GROUP BY post.id";
    if($row = mysqli_fetch_object($db->query($sql))) {
        // Check if user is allowed to delete post
        if(($_SESSION['user']->verified || $row->id == $_SESSION['user']->id) OR $second) {
            // If post has no replies -> delete post, else set "deleted" to true
            if($row->replycount == 0) {
                if($row->deleted == 1 OR !$second) { // If post is set to be deleted -> delete post
                    $sql = "DELETE FROM post WHERE `id` = $postID";
                    $db->query($sql);
                    // If post has media -> remove media from folder
                    if(!is_null($row->media)) {
                        deleteFile($row->media, "post");
                    }
                    // If post has parent -> Check if parent can be deleted
                    if(!is_null($row->referencedPostID)) {
                        checkDelete($row->referencedPostID, true);
                    }
                }
            } else {
                $sql = "UPDATE post SET deleted = 1 WHERE `id` = $postID";
                $db->query($sql);
            }
        }
    }
}
?>