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
    } else if(isset($_GET['delete']) && isset($_GET['postID'])) {
        $postID = mysqli_real_escape_string($db, $_GET['postID']);
        checkDelete($db, $postID, false);
    } else if(isset($_GET['openNotification'])) {
        $sql = "UPDATE user SET `notificationUpdateTime` = now() WHERE `id` = $userID";
        $db->query($sql);
    }
} else {
    echo('No permission');
}

function checkDelete($db, $postID, $second) {
    $sql = "SELECT user.verified, user.id, COUNT(comments.referencedPostID) AS replycount, post.referencedPostID, post.deleted, post.media
    FROM user 
    INNER JOIN post ON user.id = post.userID
    LEFT JOIN post comments ON comments.referencedPostID = post.id 
    WHERE post.id = $postID
    GROUP BY post.id";
    if($row = mysqli_fetch_object($db->query($sql))) {
        if(($_SESSION['user']->verified || $row->id == $_SESSION['user']->id) OR $second) {
            if($row->replycount == 0) {
                
                if($row->deleted == 1 OR !$second) {
                    $sql = "DELETE FROM post WHERE `id` = $postID";
                    $db->query($sql);

                    if(!is_null($row->media)) {
                        deleteFile($row->media, "post");
                    }

                    if(!is_null($row->referencedPostID)) {
                        checkDelete($db, $row->referencedPostID, true);
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