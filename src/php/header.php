<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
}
?>
<html>

<head>
<!-- Bootstrap Css -->
<link rel="stylesheet" href="../../src/css/bootstrap.min.css">

<!-- Bootstrap Theme -->
<link rel="stylesheet" href="../../src/css/bootstrap.css">

<link rel="stylesheet" href="../../src/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="#">DHBW Social</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor01">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php if($currentpage == "home") { echo('active'); } ?>">
        <a class="nav-link" href="index.php">Feed</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if($currentpage == "chats") { echo('active'); } ?>" href="chats.php">Chats</a>
      </li>
    </ul>
        <img src="assets/images/cat.jpg" class="form-inline my-2 mr-3 my-lg-0" id="profile-pic"/>
    <form action="src/php/logout.php" class="form-inline my-2 my-lg-0">
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Ausloggen</button>
    </form>
  </div>
</nav>
