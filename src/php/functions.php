<?php
function showPostTime($time){
    $time = strtotime($time);
    $now = strtotime(date("Y-m-d H:i:s"));
    $diff = $now - $time;
    if($diff - 60 < 0) {
        // Show seconds
        return $diff." sek";
    } elseif ($diff - 60*60 < 0) {
        // Show minutes
        return round($diff/60)." min";
    } elseif ($diff - 60*60*24 < 0) {
        // Show hours
        return round($diff/60/60)." std";
    } elseif (strftime("%Y", $time) == strftime("%Y", $now)) {
        // Show date
        return strftime("%d %h", $time);
    } else {
        // Show date and year
        return strftime("%d %h %y", $time);
    }
}

function getUserPosts($userid, $db, $query = "", $inProfile = false, $secondJoin = "") {
    if($userid == -1) {
        $sql = "SELECT post.*, user.username, user.verified FROM post, user WHERE user.id=post.userID $query ORDER BY post.postDate DESC";
    } else {
        $sql = "SELECT post.*, user.username, user.verified FROM post INNER JOIN user ON user.id = post.userID $secondJoin WHERE post.userID=".$userid." $query ORDER BY post.postDate DESC";
    }
    $res = $db->query($sql);
    $posts = "";
    while($row = mysqli_fetch_object($res)) {
        $sql = "SELECT ((SELECT COUNT(*) FROM feedback WHERE `like` = 1 AND postID = ".$row->id.") - (SELECT COUNT(*) FROM feedback WHERE `like` = 0 AND postID = ".$row->id.")) AS ergebnis";
        if($row2 = mysqli_fetch_object($db->query($sql))) {
            $likecount = $row2->ergebnis;
        }
        $sql = "SELECT COUNT(*) AS ergebnis FROM post WHERE referencedPostID = ".$row->id;
        if($row2 = mysqli_fetch_object($db->query($sql))) {
            $replycount = $row2->ergebnis;
        }
        $sql = "SELECT * FROM feedback WHERE postID = ".$row->id." AND userID = ".$_SESSION['userID'];
        $liked = NULL;
        if($row2 = mysqli_fetch_object($db->query($sql))) {
            $liked = $row2->like;
        }
        $changedContent = "";
        if(isset($row->content) && $row->content != "") {
            $changedContent = preg_replace('/(?<= |^)(#[a-zA-Z0-9]+)(?= |$)/', '<span class="hashtag" onclick="search(\'$1\')">$1</span>', $row->content);
            $changedContent = preg_replace('/(?<= |^)(@[a-z0-9_-]{3,16}+)(?= |$)/', '<span class="username" onclick="openUser(\'$1\')">$1</span>', $changedContent);
            $changedContent = preg_replace('/((http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/im', '<a class="content-link" href="$1">$1</a>', $changedContent);
        }
        $posts .= '
      <div class="card '.($inProfile ? 'post-in-profile' : '').' post" id="post'.$row->id.'">
          <a href="profile.php?user='.$row->username.'">
            <img src="assets/images/cat2.png" class="posted-profile-pic"/>
          </a>
          <div class="card-body post-content">
              <h5 class="card-title post-headline">
                  <a class="post-username"  href="profile.php?user='.$row->username.'">'.$row->username.'</a> 
                  '.($row->verified ? '<b class="material-icons verified-follow">verified</b>' : '').'
                  <span class="card-subtitle mb-2 text-muted post-date">· &nbsp;' .showPostTime($row->postDate).'</span>
                  '.(($_SESSION['verified'] || $_SESSION['userID'] == $row->userID) ? '<span class="material-icons delete-post text-danger" onclick="deletePost('.$row->id.')">delete</span>' : '').'
              </h5>
              <p class="card-text">'.$changedContent.'</p>
              '. (isset($row->media) && $row->media != "" ? "<img src=\"files/post/$row->media\" class=\"post-media\"/><br><br>": "") .'
              <span onclick="feedback(1, '.$_SESSION['userID'].', '.$row->id.')" id="like-btn'.$row->id.'" class="material-icons feedback text-primary '.($liked == "1" ? 'text-success' : '').'">thumb_up</span>
              <span class="like-count text-primary" id="like-count'.$row->id.'">'.$likecount.'</span>
              <span onclick="feedback(0, '.$_SESSION['userID'].', '.$row->id.')" id="dislike-btn'.$row->id.'" class="text-primary material-icons feedback '.($liked == "0" ? 'text-danger' : '').'">thumb_down</span>
              <div class="reply">
                  <a href="post.php?refPost='.$row->id.'" class="material-icons text-success reply-icon">reply</a>
                  <span class="reply-count text-success">'.$replycount.'</span>
              </div>
          </div>
      </div>
      ';
    }
    return $posts != "" ? $posts : "<br><h3 class='center'>Keine Posts gefunden :(</h3>";
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
