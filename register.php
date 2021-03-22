<?php
session_start();
if(isset($_POST['username']) && isset($_POST['passwd'])){
    include('db.php');
    $username = mysqli_real_escape_string($db, $_POST['username']);

    $sql = "INSERT INTO `login` (`id`, `username`, `passwd`) VALUES (NULL, '$username', '".md5($_POST['passwd'])."')";
    $db->query($sql);

    $_SESSION['username'] = $username;
    $_SESSION['passwd'] = md5($_POST['passwd']);

    header("Location: index.php");

} elseif(isset($_SESSION['username'])) {
    header('Location: index.php');
} else {
    echo '
    <html>
    <head>
    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="../../src/css/bootstrap.min.css">

    <!-- Bootstrap Theme -->
    <link rel="stylesheet" href="../../src/css/bootstrap.css">

    <link rel="stylesheet" href="../../src/css/style.css">
    </head>
    <body>

    <div class="center-center">
        <h3>Registrierung</h3>
        <form action="register.php" method="post">
            <label>Benutzername: </label><br>
            <input type="text" name="username">
            <br>
            <label>Passwort: </label><br>
            <input type="password" name="passwd">
            <br><br>
            <input class="btn btn-primary btn-lg" type="submit" value="Registrieren">
        </form>
        <p>Oder <a href="login.php">einloggen</a></p>
    </div>
    ';
    include('src/php/footer.php');
}
?>