<?php
include("post.php");
include("user.php");
include("notification.php");

function getNotifications($db) {
    $notifications = array();
    $sql ="SELECT * FROM notificationView WHERE userID = " . strval($_SESSION['user']->id);
    $res = $db->query($sql);
    while($row = mysqli_fetch_object($res)) {
        $notification = new Notification($row);
        array_push($notifications, $notification->getHtml());
    }
    return $notifications;
}

function getPosts($cond, $db, $showReplies = false, $second = false, $getParent = false) {
    $sql ="SELECT ergebnis.*, COUNT(comments.referencedPostID) AS replycount FROM (
        SELECT post.*, user.username, user.avatar, user.verified,
            SUM(IF(feedback.like IS NULL, 0, IF(feedback.like = 1, 1, -1))) AS likedcount,
            pFeedback.like AS liked
        FROM post
        INNER JOIN user ON user.id = post.userID 
        LEFT JOIN feedback ON feedback.postID = post.id
        LEFT JOIN feedback pFeedback ON pFeedback.postID = post.id AND pFeedback.userID = ". $_SESSION['user']->id ."
        WHERE $cond
        GROUP BY post.id) ergebnis
    LEFT JOIN post comments ON comments.referencedPostID = ergebnis.id
    GROUP BY ergebnis.id
    ORDER BY ergebnis.postDate DESC";
    $posts = "";
    $res = $db->query($sql);
    $postIDs = array();
    while($row = mysqli_fetch_object($res)) {
        $post = new Post($row);
        array_push($postIDs, $post->id);
        if($getParent && !is_null($post->referencedPostID)) {
            $posts .= getPostById($post->referencedPostID, $db);
            $posts .= '<div class="comment comment-level-1">' .$post->getHtml() . '</div>';
        } else {
            $posts .= $post->getHtml();
        }

        if($showReplies) {
            $posts .= loadReplies($post->id, 1, $db);
        }
    }
    if($showReplies && !$second) {
        $others = getPosts("post.referencedPostID IS NULL "
        . ( count($postIDs) != 0 ? "AND post.id NOT IN (" . implode(",", $postIDs) . ")" : ""), $db, true, true);
        $posts .= $others == "" ? "" : "<h3>Diese Posts könnten Sie auch interessieren:</h3><hr>" . $others;
    }
    if(!$second) {
        $posts = $posts != "" ? $posts : "<br><h3 class='center'>Keine Posts gefunden :(</h3>";
    }
    return $posts;
}

function loadReplies($postID, $replyLevel, $db){
    $replyString = "";
    $sql = "SELECT * FROM post WHERE referencedPostID = " . $postID;
    $replies = $db->query($sql);

    if($replyLevel > 3){
        $replyLevel = 3;
    }

    while($reply = mysqli_fetch_object($replies)){
        $replyString .= '<div class="comment comment-level-'.$replyLevel.'">' . getPosts("post.id = $reply->id", $db) . '</div>';
        $replyString .= loadReplies($reply->id, $replyLevel+1, $db);
    }

    return $replyString;
}

function getPostById($postID, $db) {
    $sql = "SELECT post.*, user.username, user.avatar, user.verified, COUNT(feedback.like),
        SUM(IF(feedback.like IS NULL, 0, IF(feedback.like = 1, 1, -1))) AS likedcount,
        IF(feedback.like = 1 AND feedback.userID = " . $_SESSION['user']->id . ", 1 ,0) AS liked,
        COUNT(comments.referencedPostID) AS replycount
    FROM post
    INNER JOIN user ON user.id = post.userID 
    LEFT JOIN feedback ON feedback.postID = post.id
    LEFT JOIN post comments ON comments.referencedPostID = post.id
    WHERE post.id = $postID
    GROUP BY post.id
    ORDER BY post.postDate DESC";
    
    $row = mysqli_fetch_object($db->query($sql));
    $post = new Post($row);

    return $post->getHtml();
}

function getAllowedFileExtensions($destinationFolder){
    switch($destinationFolder){
        case "chat":
        case "avatar":
        case "banner": return array('jpg', 'gif', 'png', 'jpeg', 'svg');
        case "post": return array('jpg', 'gif', 'png', 'jpeg', 'mp4', 'mp3', 'avi', 'svg');
        default: echo $destinationFolder . " ist kein erlaubter destinationFolder.";
    }
}

function uploadFile($uploadedFile, $destinationFolder){
    if ( $uploadedFile['error'] === UPLOAD_ERR_OK) {
        // get details of the uploaded file
        $fileTmpPath = $uploadedFile['tmp_name'];
        $fileName = $uploadedFile['name'];
        $fileSize = $uploadedFile['size'];
        $fileType = $uploadedFile['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // sanitize file-name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // check if file has one of the following extensions
        $allowedfileExtensions = getAllowedFileExtensions($destinationFolder);

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = 'files/' . $destinationFolder . "/";
            $dest_path = $uploadFileDir . $newFileName;
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $message ='File is successfully uploaded.';
                return $newFileName;
            } else {
                $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        } else {
            $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        $message = 'There is some error in the file upload. Please check the following error.<br>';
        $message .= 'Error:' . $uploadedFile['error'];
    }
    throw new Exception($message);
}

function deleteFile($fileName, $destinationFolder){
    try {
        unlink("files/" . $destinationFolder . "/" . $fileName);
        echo("files/" . $destinationFolder . "/" . $fileName);
    } catch (Exception $e) {
        echo 'Fehler: ',  $e->getMessage();
    }
}
