<?php
/**
 * File: index.php
 * Main index file 
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
$currentpage = "home";
include('src/php/header.php');

$sql = "SELECT COUNT(*) AS ergebnis FROM post WHERE userID=".$_SESSION['user']->id;
$row = mysqli_fetch_object($db->query($sql));
$userposts = $row->ergebnis;

$sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE `following`=".$_SESSION['user']->id;
$row = mysqli_fetch_object($db->query($sql));
$userfollowers = $row->ergebnis;

$sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE userID=".$_SESSION['user']->id;
$row = mysqli_fetch_object($db->query($sql));
$userfollowing = $row->ergebnis;
?>

<br>
<div class="container-fluid content">
    <div class="row">
        <div class="col-3 side">
            <div class="sidebar">
                <div class="personal">
                    <div class="profile-side-info">
                        <div class="center">
                            <a href="profile.php?user=<?php echo($_SESSION['user']->name) ?>">
                                <img src="<?php echo(($_SESSION['user']->getAvatar())) ?>" class="profile-pic-side"/>
                            </a>
                            <b><a class="post-username" href="profile.php?user=<?php echo ($_SESSION['user']->name . "\">". $_SESSION['user']->name . "</a>"); ?></b>
                            <b class="material-icons verified-follow"><?php echo ($_SESSION['user']->verified ? 'verified' : ''); ?></b>
                        </div><br>
                        <div class="row">
                            <div class="col profile-info-row-content">
                                <?php echo($userposts); ?>
                            </div>
                            <div class="col profile-info-row-content">
                                <?php echo($userfollowers); ?>
                            </div>
                            <div class="col profile-info-row-content">
                                <?php echo($userfollowing); ?>
                            </div>
                        </div>           
                        <div class="row">
                            <div class="col profile-info-row">Posts </div>
                            <div class="col profile-info-row">Follower</div>
                            <div class="col profile-info-row">Folge ich</div>
                        </div>
                    </div>
                    <a href="post.php" class="profile-info-button">Neuen Post erstellen</a>
                </div>
                <div class="following">
                    <h3>Folge ich:</h3><br>
                    <?php
                    $sql = "SELECT *, id AS userID FROM follows INNER JOIN user ON follows.following = user.id WHERE follows.userID=".$_SESSION['user']->id;
                    $res = $db->query($sql);
                    // Show all following accounts
                    while($row = mysqli_fetch_object($res)) {
                        $user = new User($row);
                        echo('
                        <div class="follower">
                            <a href="profile.php?user='.$user->name.'">
                                <img src="' . $user->getAvatar() . '" class="profile-pic-follow"/>
                            </a>
                            <b><a class="post-username" href="profile.php?user='.$user->name.'">'.$user->name.'</a></b>
                            <b class="material-icons verified-follow">'.($user->verified ? 'verified' : '').'</b>
                        </div>
                        <br>');
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="center-div">
                <?php
                    $hashtag = "";
                    // Show search bar if query is set
                    if(isset($_GET['query'])) {
                        echo('
                        <a class="material-icons arrow-back text-primary" onclick="window.history.back();">arrow_back</a>
                        <form method="get" action="index.php" class="search-form">
                            <div class="searchbar-main">
                                <input class="search_input-main" type="text" pattern="#[a-zA-Z0-9]+" name="query" value="'.$_GET['query'].'" placeholder="Hashtags suchen...">
                                <span class="material-icons fas fa-search">search</span>
                            </div>
                        </form>
                        ');
                        $hashtag = htmlspecialchars($_GET['query']);
                    }
                    echo getPosts("post.referencedPostID IS NULL AND (post.userID = ".$_SESSION['user']->id." OR (post.userID IN 
                    (SELECT id FROM follows INNER JOIN user ON follows.following = user.id WHERE follows.userID=".$_SESSION['user']->id.")))
                    " . ($hashtag != "" ? "AND post.content LIKE '%$hashtag%'" : ""), $hashtag == "" ? true : false );
                ?>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
</div>

<?php include('src/php/footer.php'); ?>