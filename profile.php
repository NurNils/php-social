<?php
$currentpage = "profile";
include('src/php/header.php');

if(isset($_POST['user']) && isset($_POST['edit'])){
    $error = "";

    $description = NULL;
    if(isset($_POST['description'])) {
        $description = "'" . mysqli_real_escape_string($db, $_POST['description']) . "'";
    }

    $avatar = NULL;
    if($_FILES['avatar']['size'] != 0) {
        try {
            $avatar = uploadFile($_FILES["avatar"], 'avatar');
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }

    $banner = NULL;
    if($_FILES['banner']['size'] != 0){
        try {
            $banner = "'" . uploadFile($_FILES["banner"], 'banner') . "'";
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }    

    if($error == "") {
        $set = array();
        if($description) array_push($set, "description=".$description);
        if($avatar) {
            array_push($set, "avatar='".$avatar."'");
            $_SESSION['avatar'] = $avatar;
        }
        if($banner) array_push($set, "banner=".$banner);
        $sql = "UPDATE `user` SET " . implode(",", $set) . "WHERE id=" . $_SESSION['userID'];
        $db->query($sql);
        $_SESSION['snackbar']['error'] = false;
        $_SESSION['snackbar']['message'] = "Profil erfolgreich bearbeitet";
        
    } else {
        $_SESSION['snackbar']['error'] = true;
        $_SESSION['snackbar']['message'] = $error;
    }
    header("Location: profile.php?user=" . $_SESSION['username']);
} else if(isset($_GET['user']) && isset($_GET['follow']) && isset($_GET['userID'])){
    $username = mysqli_real_escape_string($db, $_GET['user']);
    $userID = mysqli_real_escape_string($db, $_GET['userID']);
    $follow = mysqli_real_escape_string($db, $_GET['follow']);

    if($follow == "true") {
        $sql = "DELETE FROM follows WHERE `userID` = ".$_SESSION['userID']." AND `following` = $userID";
    } else {
        $sql = "INSERT INTO follows (`userID`, `following`, `followDate`) VALUES (".$_SESSION['userID'].", $userID, NULL)";
    }
    $db->query($sql);
    header("Location: profile.php?user=".$username);
} else if(isset($_GET['user']) && isset($_GET['edit'])){
    echo('
    <div id="profile-edit-form">
    <h1>Profil bearbeiten</h1>
    <form enctype="multipart/form-data" action="profile.php" method="post">
        <h2>Beschreibung</h2>
        <textarea name="description" rows="4" cols="50"></textarea>
        <br>
        <h2>Avatar</h2>
        <input type="file" id="file-upload" name="avatar"/><br>
        <h2>Banner</h2>
        <input type="file" id="file-upload" name="banner"/><br>
        <input type="hidden" name="user" value="'.$_GET['user'].'"/>
        <input type="hidden" name="edit" value="1"/>
        <br>
        <input class="btn btn-primary btn-lg" type="submit" value="Änderungen speichern">
    </form>
    </div>');
} else if(isset($_GET['user'])) {    
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
        <div class="container">
            <div class="profile">
                <img class="banner" src="' . getProfileBanner($row->banner) . '">
                <img class="avatar" src="' . getProfileAvatar($row->avatar) . '">
                <div class="profile-actions">
                '. ($_GET['user'] == $_SESSION['username'] 
                    ? '<a href="profile.php?user='.$_GET['user'].'&edit=1"><button id="change-profile">Profil bearbeiten</button></a>' 
                    : '<form> 
                        <input type="hidden" name="user" value="'.$row->username.'">
                        <input type="hidden" name="userID" value="'.$row->id.'">' . 
                        ($isFollowing 
                            ? '<button type="submit" class="following">Folge ich</button>
                                <input type="hidden" name="follow" value="true">' 
                            : '<button type="submit">Folgen</button>
                                <input type="hidden" name="follow" value="false">'
                        ) . '</form>'
                    ) . '
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
        echo("ff");
    }
} else{
    echo('Undefined User');
}

include('src/php/footer.php');
?>