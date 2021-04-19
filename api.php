<?php
session_start();
if(isset($_SESSION['username'])) {
    if(isset($_GET['like']) && isset($_GET['userID']) && isset($_GET['postID'])) {
        include('db.php');
        $like = mysqli_real_escape_string($db, $_GET['like']);
        $userID = mysqli_real_escape_string($db, $_GET['userID']);
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
                $sql = "UPDATE feedback SET `userID` = $userID, `postID` = $postID, `like` = $like WHERE `userID` = $userID AND `postID` = $postID";
            }
        } else {
            // User did not like/dislike already -> Add feedback
            $sql = "INSERT INTO feedback (`userID`, `postID`, `like`) VALUES ($userID, $postID, $like)";
        }
        $db->query($sql);
    }
} else {
    echo('No permission');
}
?>