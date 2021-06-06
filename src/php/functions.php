<?php
/**
 * File: functions.php
 * Important main functions and db operations
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
include 'post.php';
include 'user.php';
include 'notification.php';

/**
 * Gets user notifications from the database
 * @return Notification[]
 */
function getNotifications()
{
  global $db;
  $notifications = [];
  $sql = 'SELECT * FROM notificationView WHERE userID = ' . strval($_SESSION['user']->id);
  $res = $db->query($sql);
  while ($row = mysqli_fetch_object($res)) {
    $notification = new Notification($row);
    array_push($notifications, $notification->getHtml());
  }
  return $notifications;
}

/**
 * Gets user posts from the database
 * @param string $cond conditions for select statement
 * @param boolean $showReplies get post with or without replies
 * @param boolean $second show other interesting posts
 * @param boolean $getparent get parent post if exist
 * @return Posts[]
 */
function getPosts($cond, $showReplies = false, $second = false, $getParent = false)
{
  global $db;
  $sql =
    "SELECT ergebnis.*, COUNT(comments.referencedPostID) AS replycount FROM (
        SELECT post.*, user.username, user.avatar, user.verified,
            SUM(IF(feedback.like IS NULL, 0, IF(feedback.like = 1, 1, -1))) AS likedcount,
            pFeedback.like AS liked
        FROM post
        INNER JOIN user ON user.id = post.userID 
        LEFT JOIN feedback ON feedback.postID = post.id
        LEFT JOIN feedback pFeedback ON pFeedback.postID = post.id AND pFeedback.userID = " .
    $_SESSION['user']->id .
    "
        WHERE $cond
        GROUP BY post.id) ergebnis
    LEFT JOIN post comments ON comments.referencedPostID = ergebnis.id
    GROUP BY ergebnis.id
    ORDER BY ergebnis.postDate DESC";
  $posts = '';
  $res = $db->query($sql);
  $postIDs = [];
  while ($row = mysqli_fetch_object($res)) {
    $post = new Post($row);
    array_push($postIDs, $post->id);
    // If getParent is set and post has parent, shows parent
    if ($getParent && !is_null($post->referencedPostID)) {
      $posts .= getPostById($post->referencedPostID);
      $posts .= '<div class="comment comment-level-1">' . $post->getHtml() . '</div>';
    } else {
      $posts .= $post->getHtml();
    }

    if ($showReplies) {
      $posts .= loadReplies($post->id, 1);
    }
  }
  if (!$second) {
    $posts = $posts != '' ? $posts : "<h4 class='gray'>Keine interessanten Posts...</h4>";
  }
  // Also show other interesting posts (from users the user did not follow)
  if ($showReplies && !$second) {
    $others = getPosts(
      'post.referencedPostID IS NULL ' .
        (count($postIDs) != 0 ? 'AND post.id NOT IN (' . implode(',', $postIDs) . ')' : ''),
      true,
      true
    );
    $posts .=
      $others == '' ? '' : '<h3>Diese Posts könnten Sie auch interessieren:</h3><hr>' . $others;
  }
  return $posts;
}

/**
 * Gets posts replies from the database
 * @param string $postID post id where the replies should be loaded from
 * @param int $replyLevel define how many levels should be loaded (maximum 3)
 * @return string
 */
function loadReplies($postID, $replyLevel)
{
  global $db;
  $replyString = '';
  $sql = 'SELECT * FROM post WHERE referencedPostID = ' . $postID;
  $replies = $db->query($sql);

  // Sets max reply level if reached to 3
  if ($replyLevel > 3) $replyLevel = 3;

  while ($reply = mysqli_fetch_object($replies)) {
    $replyString .=
      '<div class="comment comment-level-' .
      $replyLevel .
      '">' .
      getPosts("post.id = $reply->id") .
      '</div>';
    $replyString .= loadReplies($reply->id, $replyLevel + 1); // Recursion to load replies
  }
  return $replyString;
}

/**
 * Gets posts by id
 * @param string $postID post id which should be loaded
 * @param boolean $actions show actions
 * @return string
 */
function getPostById($postID, $actions = true)
{
  global $db;
  $sql =
    "SELECT post.*, user.username, user.avatar, user.verified, COUNT(feedback.like),
        SUM(IF(feedback.like IS NULL, 0, IF(feedback.like = 1, 1, -1))) AS likedcount,
        IF(feedback.like = 1 AND feedback.userID = " .
    $_SESSION['user']->id .
    ", 1 ,0) AS liked,
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
  return $post->getHtml($actions);
}

/**
 * Gets array of all allowed file extensions
 * @param string $destinationFoler destination where the file should be saved
 * @return string[]
 */
function getAllowedFileExtensions($destinationFolder)
{
  switch ($destinationFolder) {
    case 'chat':
    case 'avatar':
    case 'banner':
      return ['jpg', 'gif', 'png', 'jpeg', 'svg'];
    case 'post':
      return ['jpg', 'gif', 'png', 'jpeg', 'mp4', 'mpeg', 'mov', 'svg'];
    default:
      echo $destinationFolder . ' ist kein erlaubter destinationFolder.';
  }
}

/**
 * Uploads file to defined folder
 * @param file $uploadedFile uploaded file from the user
 * @param string $destinationFoler destination where the file should be saved
 * @return string[]
 */
function uploadFile($uploadedFile, $destinationFolder)
{
  if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
    // Gets details of the uploaded file
    $fileTmpPath = $uploadedFile['tmp_name'];
    $fileName = $uploadedFile['name'];
    $fileNameCmps = explode('.', $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Sanitizes file-name
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // Checks if file has one of the following extensions
    $allowedfileExtensions = getAllowedFileExtensions($destinationFolder);

    if (in_array($fileExtension, $allowedfileExtensions)) {
      // Gets directory in which the uploaded file will be moved
      $uploadFileDir = 'files/' . $destinationFolder . '/';
      $dest_path = $uploadFileDir . $newFileName;
      if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $message = 'File is successfully uploaded.';
        return $newFileName;
      } else {
        $message =
          'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
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

/**
 * Deletes file by name
 * @param string $fileName name of the file
 * @param string $destinationFoler destination where the file is saved
 */
function deleteFile($fileName, $destinationFolder)
{
  try {
    unlink('files/' . $destinationFolder . '/' . $fileName);
    echo 'files/' . $destinationFolder . '/' . $fileName;
  } catch (Exception $e) {
    echo 'Fehler: ', $e->getMessage();
  }
}

/**
 * Gets a list of chats from user
 * @return string
 */
function getChats()
{
  global $db;
  $userID = $_SESSION['user']->id;
  $sql = "SELECT chat.id, user.id AS userID, user.username, user.verified, user.avatar, IF(msg.content IS NULL, '', msg.content) AS lastMsg, MAX(msg.date) AS lastMsgTime  FROM chat
    INNER JOIN user ON user.id = IF(chat.user1 = $userID, chat.user2, chat.user1)
    LEFT JOIN `message` msg ON msg.chatID = chat.id
    WHERE chat.user1 = $userID OR chat.user2 = $userID
    GROUP BY chat.id";
  $chats = '';
  $res = $db->query($sql);
  while ($row = mysqli_fetch_object($res)) {
    $user = new User($row);
    $chats .=
      '<a class="message-box-link" href="chats.php?chat=' . $row->id . '">
        <div class="card text-white bg-secondary mb-3 message-box">
          <div class="card-header message-box-content">
            <img src="' . $user->getAvatar() . '" class="profile-pic-follow"/>
            <div class="chat-wrapper">
              <span class="chat-name"><b>' . $user->name . '</b></span>
              <br>
              <span class="last-message">' . $row->lastMsg . '</span> 
              ' . (!is_null($row->lastMsgTime) ? '<i class="gray">' . prettyTime($row->lastMsgTime) . '</i>' : '') . '
            </div>
          </div>
        </div>
      </a>';
  }
  return $chats;
}

/**
 * Gets messages from a specific chat
 * @param string $chatID chatID of the chat
 * @return string
 */
function getChat($chatID)
{
  global $db;
  $userID = $_SESSION['user']->id;
  $sql = "SELECT msg.*, user.id AS userID, user.username, user.verified, user.avatar FROM `message` msg
    INNER JOIN user ON user.id = msg.userID
    WHERE chatID = $chatID
    ORDER BY msg.date ASC";
  $messages = '';
  $res = $db->query($sql);
  $lastMsg = 0;
  while ($row = mysqli_fetch_object($res)) {
    $user = new User($row);
    $userID = $_SESSION['user']->id;
    if ($user->id == $userID) {
      $messages .=
        '<div class="right-msg msg">
          <div class="msg-content-right">' . $row->content . '</div>
        </div>
        <i class="msg-time-right">' . prettyTime($row->date) . '</i>';
    } else {
      $messages .=
        '<div class="left-msg msg">
          <img src="' . $user->getAvatar() . '" class="profile-pic-msg"/>
            <div class="msg-content-left">' . $row->content . '</div>
        </div>
        <i class="msg-time-left">' . prettyTime($row->date) . '</i>';
    }
    $lastMsg = max(strtotime($row->date), $lastMsg);
  }
  return ['html' => $messages, 'lastMsg' => $lastMsg];
}

/**
 * Returns the time as a time string
 * @param string $time time to be converted
 * @return string
 */
function prettyTime($time)
{
  setlocale(LC_TIME, 'de_DE');
  $time = strtotime($time);
  $now = strtotime(date('Y-m-d H:i:s'));
  $diff = $now - $time;
  if ($diff == 0) {
    return 'Gerade eben';
  } elseif ($diff - 60 < 0) {
    // Shows seconds
    return $diff . ' sek';
  } elseif ($diff - 60 * 60 < 0) {
    // Shows minutes
    return round($diff / 60) . ' min';
  } elseif ($diff - 60 * 60 * 24 < 0) {
    // Shows hours
    return round($diff / 60 / 60) . ' std';
  } elseif (strftime('%Y', $time) == strftime('%Y', $now)) {
    // Shows date
    return strftime('%d %h', $time);
  } else {
    // Shows date and year
    return strftime('%d %h %y', $time);
  }
}
