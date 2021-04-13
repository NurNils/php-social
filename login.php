<?php
$isLogin = true;
include('src/php/header.php');
if(isset($_POST['username'])){

    $sql = "SELECT * FROM `user` WHERE `username`=\"".htmlspecialchars($_POST['username'])."\" AND `password`=\"".md5( $_POST['password'])."\"";
    $res = $db->query($sql);
    while($row = mysqli_fetch_object($res)) {
        $_SESSION['username'] = $row->username;
        $_SESSION['password'] = $row->password;
        $_SESSION['userID'] = $row->id;
        header("Location: index.php");
    }
    echo "Falsches Passwort oder Benutzername";

} elseif(isset($_SESSION['username'])) {
    header("Location: index.php");
} else {
    echo '
    <div class="center-center">
        <form action="login.php" method="post">
            <label>Benutzername: </label><br>
            <input type="text" name="username">
            <br>
            <label>Passwort: </label><br>
            <input type="password" name="password">
            <br><br>
            <input class="btn btn-primary btn-lg" type="submit" value="Login">
        </form>
        <p>Kein Profil? Jetzt <a href="register.php">registrieren</a></p>
    </div>
    ';
    include('src/php/footer.php');
}
?>