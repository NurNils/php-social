<?php
include("post.php");
include("user.php");

function getProfileAvatar($avatar) {
    return 'files/avatar/' . ($avatar ? $avatar : 'defaultProfile.png');
}

function getProfileBanner($banner) {
    return 'files/banner/' . ($banner ? $banner : 'notFound.png');
}

function getPosts($cond, $db, $showReplies = false) {
    $sql = "SELECT post.*, user.username, user.avatar, user.verified, COUNT(feedback.like),
        SUM(IF(feedback.like IS NULL, 0, IF(feedback.like = 1, 1, -1))) AS likedcount,
        IF(feedback.like = 1 AND feedback.userID = " . $_SESSION['user']->id . ", 1 ,0) AS liked,
        COUNT(comments.referencedPostID) AS replycount
    FROM post
    INNER JOIN user ON user.id = post.userID 
    LEFT JOIN feedback ON feedback.postID = post.id
    LEFT JOIN post comments ON comments.referencedPostID = post.id
    WHERE $cond
    GROUP BY post.id
    ORDER BY post.postDate DESC";
    $posts = "";

    $res = $db->query($sql);
    while($row = mysqli_fetch_object($res)) {
        $post = new Post($row);
        $posts .= $post->getHtml();

        if($showReplies) {
            $posts .= loadReplies($post->id, 1, $db);
        }
    }
    return $posts != "" ? $posts : "<br><h3 class='center'>Keine Posts gefunden :(</h3>";
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

function getPostById($postID, $db, $inProfile = NULL) {
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
