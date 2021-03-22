<?php 
$currentpage = "home";
include('src/php/header.php');
?>

<h1> Willkommen: <?php echo($_SESSION['username']);?></h1><br>
<div class="container">

    <div class="starter-template">
    <h1>Bootstrap starter template</h1>
    <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
    </div>

</div>

<?php include('src/php/footer.php'); ?>