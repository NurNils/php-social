<?php
$currentpage = "home";
include('src/php/header.php');

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
                    <p>Banner</p>
                    <img src="assets/images/cat.jpg" class="profile-pic-side"/>
                    <img />
                    <b><?php echo ($_SESSION['username']); ?></b><br><br>
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
                            12
                        </div>
                        <div class="col">
                            2
                        </div>
                        <div class="col">
                            4
                        </div>
                    </div>
                </div>
                <br>
                <a href="post.php" class="btn btn-primary">Neuer post</a>
                <br><br>
                <div class="following">
                    <h3>Ich folge:</h3>
                    <ul>
                        <li>Peter</li>
                        <li>Günter</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col main-content">
            <div class="center-div">
                <div class="starter-template">
                    <h1>Feed</h1>
                    <p class="lead">Hier stehen alle neuen Posts</p>
                    <?php
                        $sql = "SELECT post.*, user.username FROM post, user WHERE user.id=post.userID";
                        $res = $db->query($sql);
                        while($row = mysqli_fetch_object($res)) {
                            $sql = "SELECT ((SELECT COUNT(*) FROM feedback WHERE `like` = 1 AND postID = ".$row->id.") - (SELECT COUNT(*) FROM feedback WHERE `like` = 0 AND postID = ".$row->id.")) AS ergebnis";
                            if($row2 = mysqli_fetch_object($db->query($sql))) {
                                $likecount = $row2->ergebnis;
                            }
                            $sql = "SELECT * FROM feedback WHERE postID = ".$row->id." AND userID = ".$_SESSION['userID'];
                            $liked = NULL;
                            if($row2 = mysqli_fetch_object($db->query($sql))) {
                                $liked = $row2->like;
                            }
                            echo('
                            <div class="card post" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title post-username">'.$row->username.' <span class="card-subtitle mb-2 text-muted post-date">· &nbsp;' .showPostTime($row->postDate).'</span></h5>
                                    <p class="card-text">'.$row->content.'</p>
                                    <img src="assets/images/cat.jpg" class="post-media"/><br><br>
                                    <span onclick="feedback(1, '.$_SESSION['userID'].', '.$row->id.')" id="like-btn'.$row->id.'" class="material-icons feedback text-primary '.($liked == "1" ? 'text-success' : '').'">thumb_up</span>
                                    <span class="like-count text-primary" id="like-count'.$row->id.'">'.$likecount.'</span>
                                    <span onclick="feedback(0, '.$_SESSION['userID'].', '.$row->id.')" id="dislike-btn'.$row->id.'" class="text-primary material-icons feedback '.($liked == "0" ? 'text-danger' : '').'">thumb_down</span>
                                    <div class="reply">
                                        <span class="material-icons text-success">reply</span>
                                        <span class="reply-count text-success">1337</span>
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