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
                        echo getUserPosts(-1, $db, $query);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('src/php/footer.php'); ?>