<div class="main-content">
<?php 
$currentpage = "chats";
include('src/php/header.php');

echo('
<form method="POST" action="chats.php" enctype="multipart/form-data">
    <input type="file" id="file-upload" name="uploadedFile"><br>
    <input type="submit" name="uploadBtn" value="Upload" />
</form>
');


?>

<div class="container">
    <h1>Hier werden alle chats gezeigt</h1>
</div>
</div>
<?php include('src/php/footer.php'); ?>