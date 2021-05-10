<?php
$currentpage = "post";
include('src/php/header.php');

$error = "";
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
    if($_FILES['uploadedFile']['size'] != 0){
        try {
            $media = "'" . uploadFile($_FILES["uploadedFile"], 'post') . "'";
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }

    if($error == "") {
        $sql = "INSERT INTO `post` (`userID`, `referencedPostID`, `content`, `media`) 
        VALUES ('" . $_SESSION['userID'] . "', $referencedPost , $postContent, $media)";
        $db->query($sql);
        $_SESSION['snackbar']['error'] = false;
        $_SESSION['snackbar']['message'] = "Post erfolgreich erstellt";
        header("Location: index.php");
    }
} else {
    if(isset($_GET['refPost'])){
        $refPost = mysqli_real_escape_string($db, $_GET['refPost']);
        echo(getPostById($refPost, $db));
    }
}

echo '
    <div class="">
        <h3>Post erstellen:</h3>
        <form enctype="multipart/form-data" action="post.php" method="post">
            <br>
            <label for="postContent">Content: </label>
            <textarea maxlength="280" id="postContent" name="postContent"></textarea>
            <br><br>
            <input type="file" id="file-upload" name="uploadedFile"><br>
            '.( isset($_GET['refPost']) ? '<input type="hidden" name="refPost" value="'.$_GET['refPost'].'"/>' : '').'
            <p class="text-danger">'.$error.'</p>
            <input class="btn btn-primary btn-lg" type="submit" value="Pfostieren!">
        </form>
    </div>
    ';
include('src/php/footer.php');
//UserID muss gesetzt sein und (Content || Media)
//Wenn nichts davon gesetzt ist echo form mit Eingaben für einen neuen POST
//Rest abschreiben von register
?>
