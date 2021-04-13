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
            <div class="">
                <div class="starter-template">
                    <h1>Feed</h1>
                    <p class="lead">Hier stehen alle neuen Posts</p>
                    <?php
                        $sql = "SELECT post.*, user.username FROM post, user WHERE user.id=post.userID";
                        $res = $db->query($sql);
                        while($row = mysqli_fetch_object($res)) {
                            echo('
                            <div class="card post" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">'.$row->username.'</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">'.showPostTime($row->postDate).'</h6>
                                    <p class="card-text">'.$row->content.'</p>
                                    <span onclick="feedback(1, '.$_SESSION['userID'].', '.$row->id.')" id="like-btn'.$row->id.'" class="text-primary material-icons feedback">thumb_up</span>
                                    <span onclick="feedback(0, '.$_SESSION['userID'].', '.$row->id.')" id="dislike-btn'.$row->id.'" class="text-primary material-icons feedback">thumb_down</span>
                                    <a href="#" class="card-link" style="float: right"><span class="material-icons">reply</span></a>
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