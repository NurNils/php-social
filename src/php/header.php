<?php
include('db.php');

session_start();
if (!isset($isLogin) && !isset($_SESSION['username'])) {
    header('Location: login.php');
}
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
      $changedContent = preg_replace('/(?<= |^)(#[a-zA-Z0-9]+)(?= |$)/', '<span class="hashtag" onclick="search(\'$1\')">$1</span>', $row->content);
      $changedContent = preg_replace('/(?<= |^)(@[a-z0-9_-]{3,16}+)(?= |$)/', '<span class="username" onclick="openUser(\'$1\')">$1</span>', $changedContent);
      $changedContent = preg_replace('/((http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/im', '<a class="content-link" href="$1">$1</a>', $changedContent);
      $posts .= '
      <div class="card '.($inProfile ? 'post-in-profile' : '').' post">
          <a href="profile.php?user='.$row->username.'"><img src="assets/images/cat2.png" class="posted-profile-pic"/></a>
          <div class="card-body post-content">
              <h5 class="card-title post-headline">
                  <a class="post-username"  href="profile.php?user='.$row->username.'">'.$row->username.'</a> 
                  '.($row->verified ? '<b class="material-icons verified-follow">verified</b>' : '').'
                  <span class="card-subtitle mb-2 text-muted post-date">· &nbsp;' .showPostTime($row->postDate).'</span>
              </h5>
              <p class="card-text">'.$changedContent.'</p>
              <img src="assets/images/cat.jpg" class="post-media"/><br><br>
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

?>
<html>

<head>
<!-- Bootstrap Css -->
<link rel="stylesheet" href="src/css/bootstrap.min.css">

<link rel="stylesheet" href="src/css/style.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<?php
if(!isset($isLogin)) {
  echo('
  <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php">DHBW Social</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item '. ($currentpage == "home" ? 'active' : '') .' ">
          <a class="nav-link" href="index.php">Feed</a>
        </li>
        <li class="nav-item">
          <a class="nav-link '. ($currentpage == "chats" ? 'active' : '') .'" href="chats.php">Chats</a>
        </li>
        <li class="nav-item">
          <a class="nav-link '. ($currentpage == "post" ? 'active' : '') .'" href="post.php">Post erstellen</a>
        </li>
        <li class="nav-item">
          <a class="nav-link '. ($currentpage == "info" ? 'active' : '') .'" href="info.php">Info</a>
        </li>
      </ul>
      <form method="get" action="index.php" class="search-form">
        <div class="searchbar">
          <input class="search_input" type="text" pattern="#[a-zA-Z0-9]+" name="query" placeholder="Search...">
          <span class="material-icons fas fa-search">search</span>
        </div>
      </form>
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="light-dark-switch" data-onstyle="warning" onclick="modeChange()">
        <label class="custom-control-label" id="light-dark-label" for="light-dark-switch"><span class="material-icons" id="light-dark-icon">wb_sunny</span></label>
      </div>
      <a href="profile.php?user='.$_SESSION['username'].'">
        <img src="assets/images/cat.jpg" class="form-inline my-2 mr-3 my-lg-0" id="profile-pic"/>
      </a>
      <form action="src/php/logout.php" class="form-inline my-2 my-lg-0">
          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Ausloggen</button>
      </form>
    </div>
  </nav>
  ');
}
?>