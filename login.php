<?php
/**
 * File: login.php
 * Login page to login a user 
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
$isLogin = true;
include('src/php/header.php');
if(isset($_POST['username'])){

    $sql = "SELECT *, id AS userID FROM `user` WHERE `username`=\"".htmlspecialchars($_POST['username'])."\" AND `password`=\"".md5( $_POST['password'])."\"";
    $res = $db->query($sql);
    while($row = mysqli_fetch_object($res)) {
        $_SESSION['user'] = new User($row);
        header("Location: index.php");
    }
    makeLoginForm(true);

} elseif(isset($_SESSION['user']->id)) {
    header("Location: index.php");
} else {
    makeLoginForm();
    include('src/php/footer.php');
}

function makeLoginForm($isWrong = false) {
    echo '
    <div class="center-center">
        <form action="login.php" method="post">
            <label>Benutzername: </label><br>
            <input type="text" name="username">
            <br>
            <label>Passwort: </label><br>
            <input type="password" name="password"><br>
            '.($isWrong ? '<span class="text-danger">Falscher Benutzername <br> oder Passwort</span>' : '').'
            <br><br>
            <input class="btn btn-primary btn-lg" type="submit" value="Login">
        </form>
        <p>Kein Profil? Jetzt <a href="register.php">registrieren</a></p>
    </div>
    ';
}
?>