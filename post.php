<?php

$currentpage = "post";
include('src/php/header.php');

echo '<br><br><br><br><br><br><br>';

if(isset($_POST['textContent']) ||  isset($_POST['uploadedFile'])){

    $textContent = "NULL";
    if(isset($_POST['textContent'])){
        $textContent = mysqli_real_escape_string($db, $_POST['textContent']);
    }
    $media = "NULL";

    //echo($_POST['uploadedFile']);
    //var_dump($_FILES);
    //echo($_FILES["uploadedFile"]);
    //echo($_POST['uploadedFile']);

    if(isset($_FILES['uploadedFile'])){
        try{
            echo("test");
            $media = "'" . uploadFile($_FILES["uploadedFile"], 'post') . "'";
        }catch(Exception $e){
            echo("test2");
            echo 'Fehler beim Fileupload: ' .  $e->getMessage() . "\n";
        }
    }
    echo($media);

    $sql = "INSERT INTO `post` (`id`, `userID`, `referencedPostID`, `content`, `media`, `postDate`) 
            VALUES (NULL, '" . $_SESSION['userID'] . "', NULL , '$textContent', $media, NULL)";

    $db->query($sql);

    header("Location: index.php");
}

echo '
    <div class="center-center">
        <h3>Post erstellen:</h3>
        <form enctype="multipart/form-data" action="post.php" method="post">
            <br>
            <label for="textContent">Content: </label>
            <textarea id="textContent" name="textContent"></textarea>
            <br><br>
            <input type="file" id="file-upload" name="uploadedFile"><br>
            <br>
            <input class="btn btn-primary btn-lg" type="submit" value="Pfostieren!">
        </form>
    </div>
    ';
include('src/php/footer.php');
//UserID muss gesetzt sein und (Content || Media)
//Wenn nichts davon gesetzt ist echo form mit Eingaben für einen neuen POST
//Rest abschreiben von register
?>
