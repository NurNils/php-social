<?php
session_start();
if(isset($_POST['username'])){
    include('db.php');

    $sql = "SELECT * FROM login WHERE username='".htmlspecialchars($_POST['username'])."' and passwd='".md5( $_POST['passwd'])."'";
    $res = $db->query($sql);

    while($row = mysqli_fetch_object($res)) {
        $_SESSION['username'] = $row->username;
        $_SESSION['passwd'] = $row->passwd;
        header("Location: index.php");
    }
    echo "Falsches Passwort oder Benutzername";

} elseif(isset($_SESSION['username'])){
    header("Location: index.php");
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
        <form action="login.php" method="post">
            <label>Benutzername: </label><br>
            <input type="text" name="username">
            <br>
            <label>Passwort: </label><br>
            <input type="password" name="passwd">
            <br><br>
            <input class="btn btn-primary btn-lg" type="submit" value="Login">
        </form>
        <p>Kein Profil? Jetzt <a href="register.php">registrieren</a></p>
    </div>
    ';
    include('src/php/footer.php');
}
?>