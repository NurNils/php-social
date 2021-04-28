<div class="main-content">
<?php 
$currentpage = "chats";
include('src/php/header.php');

$message = '';

    echo('
    <form method="POST" action="upload.php" enctype="multipart/form-data">
        <input type="file" id="file-upload" name="uploadedFile"><br>
        <input type="submit" name="uploadBtn" value="Upload" />
        <input type="hidden" value="chat" name="destinationFolder">
    </form>
  ');

?>

<div class="container">
    <h1>Hier werden alle chats gezeigt</h1>
</div>
</div>
<?php include('src/php/footer.php'); ?>