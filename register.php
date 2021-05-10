<?php

$isLogin = true;
include('src/php/header.php');

if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
    include('db.php');
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);

    $sql = "INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES (NULL, '$username', '$email', '".md5($_POST['password'])."')";
    $db->query($sql);
    $sql = "SELECT * FROM user WHERE `username` = '$username'";
    $res = $db->query($sql);
    while($row = mysqli_fetch_object($res)) {
        $_SESSION['username'] = $row->username;
        $_SESSION['userID'] = $row->id;
        $_SESSION['verified'] = $row->verified;
        $_SESSION['avatar'] = $row->avatar;
        header("Location: index.php");
    }

    header("Location: index.php");

} elseif(isset($_SESSION['username'])) {
    header('Location: index.php');
} else {
    echo '
    <div class="center-center">
        <h3>Registrierung</h3>
        <form action="register.php" method="post">
            <label>Benutzername: </label><br>
            <input type="text" name="username" pattern="[a-z0-9_-]{3,16}$">
            <br>
            <label>E-Mail-Adresse: </label><br>
            <input type="text" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
            <br>
            <label>Passwort: </label><br>
            <input type="password" name="password">
            <br><br>
            <input class="btn btn-primary btn-lg" type="submit" value="Registrieren">
        </form>
        <p>Oder <a href="login.php">einloggen</a></p>
    </div>
    ';
    include('src/php/footer.php');
}
?>