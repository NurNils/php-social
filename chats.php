<?php
$currentpage = "chats";
include('src/php/header.php');

if(isset($_GET['user'])) {
    // TODO: Check if user has chat with that user or create new chat

    $ownID = $_SESSION['user']->id;
    $userID = htmlspecialchars($_GET['user']);
    $sql ="SELECT id FROM chat WHERE (user1 = $userID AND user2 = $ownID) OR (user1 = $ownID AND user2 = $userID)";
    $res = $db->query($sql);
    if($row = mysqli_fetch_object($res)) {
        if(is_null($row->id)) {
            $sql = "INSERT INTO chat (`user1`, `user2`) VALUES ($ownID, $userID)";
            $db->query($sql);
            echo(getChat($db, $row->id));
        } else {
            echo(getChat($db, $row->id));
        }
    }

} else if(isset($_GET['chat'])) {
    echo('<div class="container"><div class="chat">');
    $chatID = mysqli_real_escape_string($db, $_GET['chat']);
    echo(getChat($db, $chatID));
    echo('</div>
        <div class="form-group chat-input">
            <input type="text" class="form-control" placeholder="Neue Nachricht...">
            <button type="button" class="btn btn-primary">Senden</button>
        </div>
    </div>');
} else {
    echo('
    <div class="container">
        <h1>Chats</h1>
        '.getChats($db).'
    </div>
    ');
}
?>


<?php include('src/php/footer.php'); ?>