<?php
/**
 * File: post.php
 * Post page to create a new post with image or video and text or text-only 
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
$currentpage = "post";
include('src/php/header.php');

$error = "";
/* Parameters set -> Post post */
if(isset($_POST['postContent']) || isset($_FILES['uploadedFile'])){
    $postContent = "NULL";
    if(isset($_POST['postContent'])){
        $postContent = "'" . mysqli_real_escape_string($db, $_POST['postContent']) . "'";
    }

    $referencedPost = "NULL";
    if(isset($_POST['refPost'])){
        $referencedPost = "'" . mysqli_real_escape_string($db, $_POST['refPost']) . "'";
    }

    $media = "NULL";
    // If user uploaded file -> Safe file
    if($_FILES['uploadedFile']['size'] != 0){
        try {
            $media = "'" . uploadFile($_FILES["uploadedFile"], 'post') . "'";
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }
    // If no error occured -> Post post
    if($error == "") {
        $sql = "INSERT INTO `post` (`userID`, `referencedPostID`, `content`, `media`) 
        VALUES ('" . $_SESSION['user']->id . "', $referencedPost , $postContent, $media)";
        $db->query($sql);
        $_SESSION['snackbar']['error'] = false;
        $_SESSION['snackbar']['message'] = "Post erfolgreich erstellt";
        header("Location: index.php");
    }
/* Parameters not set -> show post form */
} else {
    echo '<div class="create-post-form">';
    if(isset($_GET['refPost'])){
        $refPost = mysqli_real_escape_string($db, $_GET['refPost']);
        echo '<h3>Antwort auf:</h3>';
        echo(getPostById($refPost, false)); // If post is comment -> show parent post
    } else {
        echo '<h3>Post erstellen:</h3>';
    }
    
    echo '
        <form enctype="multipart/form-data" action="post.php" method="post">
            <h2>Inhalt</h2>
            <textarea maxlength="280" id="postContent" rows="6" name="postContent"></textarea>
            <h2>Bild oder Video</h2>
            <input type="file" id="file-upload" name="uploadedFile"><br>
            '.( isset($_GET['refPost']) ? '<input type="hidden" name="refPost" value="'.$_GET['refPost'].'"/>' : '').'
            <p class="text-danger">'.$error.'</p>
            <input class="btn btn-primary btn-lg" type="submit" value="Pfostieren!">
        </form>
    </div>
    ';
}
include('src/php/footer.php');

?>