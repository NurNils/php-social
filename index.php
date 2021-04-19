<?php
$currentpage = "home";
include('src/php/header.php');

$sql = "SELECT COUNT(*) AS ergebnis FROM post WHERE userID=".$_SESSION['userID'];
$row = mysqli_fetch_object($db->query($sql));
$userposts = $row->ergebnis;

$sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE `following`=".$_SESSION['userID'];
$row = mysqli_fetch_object($db->query($sql));
$userfollowers = $row->ergebnis;

$sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE userID=".$_SESSION['userID'];
$row = mysqli_fetch_object($db->query($sql));
$userfollowing = $row->ergebnis;


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
?>

<br>
<div class="container-fluid content">
    <div class="row">
        <div class="col-2 side">
            <div class="sidebar">
                <div class="personal">
                    <img src="assets/images/cat.jpg" class="banner"/>
                    <img src="assets/images/cat.jpg" class="profile-pic-side"/>
                    <b><?php echo ($_SESSION['username']); ?></b>
                    <b class="material-icons verified-follow"><?php echo ($_SESSION['verified'] ? 'verified' : ''); ?></b><br><br>
                    <div class="row center">
                        <div class="col">
                            Posts
                        </div>
                        <div class="col">
                            Folger
                        </div>
                        <div class="col">
                            Folge ich
                        </div>
                    </div>
                    <div class="row center">
                        <div class="col">
                            <?php echo($userposts); ?>
                        </div>
                        <div class="col">
                            <?php echo($userfollowers); ?>
                        </div>
                        <div class="col">
                            <?php echo($userfollowing); ?>
                        </div>
                    </div>
                </div>
                <br><br>
                <a href="post.php" class="btn btn-primary">Neuer post</a><br>
                <br><br>
                <div class="following">
                    <h3>Ich folge</h3><br>
                    <?php
                    $sql = "SELECT * FROM follows INNER JOIN user ON follows.following = user.id WHERE follows.userID=".$_SESSION['userID'];
                    $res = $db->query($sql);
                    while($row = mysqli_fetch_object($res)) {
                        echo('
                            <img src="assets/images/cat.jpg" class="profile-pic-follow"/>
                            <b>'.$row->username.'</b>
                            <b class="material-icons verified-follow">'.($row->verified ? 'verified' : '').'</b>
                            <hr>
                        ');
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col main-content">
            <div class="center-div">
                <div class="starter-template">
                    <?php
                        $query = "";
                        if(isset($_GET['query'])) {
                            echo('
                            <a class="material-icons arrow-back text-primary" onclick="window.history.back();">arrow_back</a>
                            <form method="get" action="index.php" class="search-form">
                                <div class="searchbar-main">
                                    <input class="search_input-main" type="text" pattern="#[a-zA-Z0-9]+" name="query" value="'.$_GET['query'].'" placeholder="Search...">
                                    <span class="material-icons fas fa-search">search</span>
                                </div>
                            </form>
                            ');
                            $query = htmlspecialchars("AND post.content LIKE '% ".$_GET['query']." %'");
                        }
                        $sql = "SELECT post.*, user.username, user.verified FROM post, user WHERE user.id=post.userID ".$query." ORDER BY post.postDate DESC";
                        $res = $db->query($sql);
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
                            echo('
                            <div class="card post" style="width: 18rem;">
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
                            ');
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('src/php/footer.php'); ?>