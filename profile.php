<?php
$currentpage = "profile";
include('src/php/header.php');

if(isset($_GET['user'])){

    $sql = "SELECT * FROM user WHERE username='" . htmlspecialchars($_GET['user']) . "'";
    $res = $db->query($sql);
    while($row = mysqli_fetch_object($res)) {

        $sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE `following`=".$row->id;
        $row2 = mysqli_fetch_object($db->query($sql));
        $userfollowers = $row2->ergebnis;

        $sql = "SELECT COUNT(*) AS ergebnis FROM follows WHERE userID=".$row->id;
        $row3 = mysqli_fetch_object($db->query($sql));
        $userfollowing = $row3->ergebnis;

        $selfPage = false;
        $isFollowing = true;

        echo('
        <div class="container main-content">
            <div>
                <img class="banner" src="' . $row->banner . '">
            </div>
            <div class="profile">
                <img class="avatar" src="' . $row->avatar . '">

                <div class="profile-actions">
                    '. ($selfPage  ? '<button>Profil bearbeiten</button>' : ($isFollowing ? '<button class="following">Folge ich</button>' : '<button>Folgen</button>')) . ' 
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
                    <button class="tablinks active" onclick="openProfileTabs(event, \'tweets\')">Tweets</button>
                    <button class="tablinks" onclick="openProfileTabs(event, \'tweets-answers\')">Tweets und Antworten</button>
                    <button class="tablinks" onclick="openProfileTabs(event, \'media\')">Medien</button>
                    <button class="tablinks" onclick="openProfileTabs(event, \'likes\')">Gefällt mir</button>
                </div>
              
                <div id="tweets" class="tabcontent" style="display: block">
                    <h3>Tweets</h3>
                </div>
                
                <div id="tweets-answers" class="tabcontent">
                    <h3>Tweets und Antworten</h3>
                </div>
                
                <div id="media" class="tabcontent">
                    <h3>Medien</h3>
                </div>
                
                <div id="likes" class="tabcontent">
                    <h3>Gefällt mir</h3>
                </div>
            </div>
        </div>
        ');
    }
} else{
    echo('Undefined User');
}
include('src/php/footer.php');
?>