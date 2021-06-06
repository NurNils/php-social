<?php
/**
 * File: register.php
 * Login page to register a user 
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
$isLogin = true;
include('src/php/header.php');
// Registers a new user with specified username, email and password
if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $sql = "INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES (NULL, '$username', '$email', '".md5($_POST['password'])."')";
    $db->query($sql);
    $sql = "SELECT *, id AS userID FROM user WHERE `username` = '$username'";
    $res = $db->query($sql);
    if($row = mysqli_fetch_object($res)) {
        $_SESSION['user'] = new User($row);
        header("Location: index.php");
    }
    header("Location: index.php");
// Redirects user if session exists
} elseif(isset($_SESSION['user']->name)) {
    header('Location: index.php');
// Shows register form
} else {
    echo '
    <div class="center-center">
        <h3>Registrierung</h3>
        <form action="register.php" method="post">
            <label>Benutzername: </label><br>
            <input type="text" required="1" name="username" pattern="[a-z0-9_-]{3,16}$">
            <br>
            <label>E-Mail-Adresse: </label><br>
            <input id="email" type="text" name="email" required="1" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
            <br>
            <label>Passwort: </label><br>
            <input type="password" required="1" name="password">
            <br><br>
            <input class="btn btn-primary btn-lg" type="submit" value="Registrieren">
        </form>
        <p>Oder <a href="login.php">einloggen</a></p>
    </div>
    ';
    include('src/php/footer.php');
}
?>