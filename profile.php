<?php
$currentpage = "profile";
include('src/php/header.php');

if(isset($_GET['user'])){

    $sql = "SELECT * FROM user WHERE username='" . htmlspecialchars($_GET['user']) . "'";
    $res = $db->query($sql);
    $counter = 0;
    while($row = mysqli_fetch_object($res)) {

        $sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE `following`=".$row->id;
        $row2 = mysqli_fetch_object($db->query($sql));
        $userfollowers = $row2->ergebnis;

        $sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE userID=".$row->id;
        $row2 = mysqli_fetch_object($db->query($sql));
        $userfollowing = $row2->ergebnis;

        $sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE userID=".$_SESSION['userID']." AND `following` = ".$row->id;
        $row2 = mysqli_fetch_object($db->query($sql));
        $isFollowing = $row2->ergebnis > 0 ? true : false;

        echo('
        <div class="container main-content">
            <div>
                <img class="banner" src="assets/images/cat2.png' . $row->banner . '">
            </div>
            <div class="profile">
                <img class="avatar" src="assets/images/cat2.png' . $row->avatar . '">

                <div class="profile-actions">
                    '. ($_GET['user'] == $_SESSION['username'] ? '<button>Profil bearbeiten</button>' : ($isFollowing ? '<button class="following">Folge ich</button>' : '<button>Folgen</button>')) . ' 
                </div>
                <br><br>
                <p class="profile-displayname"><b>' . $row->username . '</b>' . ($row->verified ? '<b class="material-icons verified-follow">verified</b>' : '') . '</p>
                <p class="profile-username">@' . $row->username . '</b></p>
                <p class="profile-description">' . $row->description . '</p>
                <p class="profile-registered">Seit ' . strftime('%B %G', strtotime($row->registerDate)) . ' bei DHBW Social</p>
                <div class="profile-followerinfo">
                    <div class="profile-following">' . $userfollowing . ' Folge ich</div>
                    <div class="profile-userfollowers">' . $userfollowers . ' Follower</div>
                </div>

                <div class="tab">
                    <button class="tablinks active" onclick="openProfileTabs(event, \'posts\')">Posts</button>
                    <button class="tablinks" onclick="openProfileTabs(event, \'posts-answers\')">Posts und Antworten</button>
                    <button class="tablinks" onclick="openProfileTabs(event, \'media\')">Medien</button>
                    <button class="tablinks" onclick="openProfileTabs(event, \'likes\')">Gefällt mir</button>
                </div>
              
                <div id="posts" class="tabcontent" style="display: block">
                    '.getUserPosts($row->id, $db, "", true).'
                </div>
                
                <div id="posts-answers" class="tabcontent">
                    '.getUserPosts($row->id, $db, "AND post.referencedPostID IS NOT NULL", true).'
                </div>
                
                <div id="media" class="tabcontent">
                    '.getUserPosts($row->id, $db, "AND post.media IS NOT NULL", true).'
                </div>
                
                <div id="likes" class="tabcontent">
                    '.getUserPosts($row->id, $db, "AND feedback.like = 1 AND feedback.userID = ".$_SESSION["userID"]."", true, "INNER JOIN feedback ON feedback.postID = post.id").'
                </div>
            </div>
        </div>
        ');
        $counter++;
    }
    if($counter == 0) {
        echo("<div class='main-content'>ff</div>");
    }
} else{
    echo('Undefined User');
}

include('src/php/footer.php');
?>