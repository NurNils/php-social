<?php
include('db.php');
include('functions.php');

session_start();
if (!isset($isLogin) && !isset($_SESSION['user']->id)) {
  header('Location: login.php');
}

?>
<html>

<head>
  <!-- Bootstrap Css -->
  <link rel="stylesheet" href="src/css/bootstrap.min.css">
  <link rel="stylesheet" href="src/css/style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
  <script src="src/js/topscript.js"></script>
</head>

<body>
  <div id="snackbar"></div>
  <?php

  if (isset($_SESSION['snackbar'])) {
    echo ('<script>openSnackbar(\'' . $_SESSION['snackbar']['message'] . '\', ' . $_SESSION['snackbar']['error'] . ')</script>');
    unset($_SESSION['snackbar']);
  }

  if (!isset($isLogin)) {

    $notificationsArray = getNotifications($db);

    $text = implode('<div class="dropdown-divider"></div>', $notificationsArray);
    $notifications = $text != "" ? $text : '<a class="dropdown-item"><span class="notifications-message notifications-gray">Du hast keine neuen Nachrichten</span></a>'; 

    echo ('
  <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php"><img src="assets/images/logo.png" width="35"> DHBW Social</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item ' . ($currentpage == "home" ? 'active' : '') . ' ">
          <a class="nav-link" href="index.php">Feed</a>
        </li>
        <li class="nav-item">
          <a class="nav-link ' . ($currentpage == "chats" ? 'active' : '') . '" href="chats.php">Chats</a>
        </li>
        <li class="nav-item">
          <a class="nav-link ' . ($currentpage == "post" ? 'active' : '') . '" href="post.php">Post erstellen</a>
        </li>
        <li class="nav-item">
          <a class="nav-link ' . ($currentpage == "info" ? 'active' : '') . '" href="info.php">Info</a>
        </li>
      </ul>
      <form method="get" action="index.php" class="search-form">
        <div class="searchbar">
          <input class="search_input" type="text" pattern="#[a-zA-Z0-9]+" name="query" placeholder="Hashtags suchen...">
          <span class="material-icons fas fa-search">search</span>
        </div>
      </form>
      <div class="btn-group">
        <button type="button" class="btn btn-info notifications" data-toggle="dropdown" onclick="openNotifications()" aria-haspopup="true" aria-expanded="false">
          <span class="material-icons">notifications</span>
          <div class="notifications-wrapper">
            '. (count($notificationsArray) > 0 ? 
              '<div class="new-notifications">
                <span class="new-notifications-nr">' . count($notificationsArray) . '</span>
              </div>'
            : '').'
          </div>
        </button>
        <div class="dropdown-menu notifications-dropdown">
          ' . $notifications . '
        </div>
      </div>
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="light-dark-switch" data-onstyle="warning" onclick="modeChange()">
        <label class="custom-control-label" id="light-dark-label" for="light-dark-switch"><span class="material-icons" id="light-dark-icon">wb_sunny</span></label>
      </div>
      <a href="profile.php?user=' . $_SESSION['user']->name . '">
        <img src="' . $_SESSION['user']->getAvatar() . '" class="form-inline my-2 mr-3 my-lg-0" id="profile-pic"/>
      </a>
      <form action="src/php/logout.php" class="form-inline my-2 my-lg-0">
          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Ausloggen</button>
      </form>
    </div>
  </nav>
  <div class="main-content">
  ');
  }
  ?>