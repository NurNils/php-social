<?php
/**
 * File: chats.php
 * Chats page, showing all chats of logged in user
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
$currentpage = 'chats';
include 'src/php/header.php';

// User clicked on message button in profile page
if (isset($_GET['user'])) {
  $ownID = $_SESSION['user']->id;
  $userID = intval(htmlspecialchars($_GET['user']));
  $sql = "SELECT id FROM chat WHERE (user1 = $userID AND user2 = $ownID) OR (user1 = $ownID AND user2 = $userID)";
  $res = $db->query($sql);
  // If the user already has a chat with the person, redirects, else creates a new chat
  if ($row = mysqli_fetch_object($res)) {
    header('Location: chats.php?chat=' . $row->id);
  } else {
    $sql = "INSERT INTO chat (`user1`, `user2`) VALUES ($ownID, $userID)";
    $db->query($sql);
    $sql = "SELECT id FROM chat WHERE user1 = $ownID AND user2 = $userID";
    $res2 = $db->query($sql);
    if ($row2 = mysqli_fetch_object($res2)) {
      header('Location: chats.php?chat=' . $row2->id);
    } else {
      header('Location: chats.php');
    }
  }
  // Shows chat of two users
} elseif (isset($_GET['chat'])) {
  $chatID = mysqli_real_escape_string($db, $_GET['chat']);
  $userID = $_SESSION['user']->id;
  $sql = "SELECT chat.id AS chatID, user.id AS userID, user.username, user.verified, user.avatar FROM chat
    INNER JOIN user ON user.id = IF(chat.user1 = $userID, chat.user2, chat.user1)
    WHERE chat.id = $chatID";
  $res = $db->query($sql);
  // Get user from chat and confirm that it is the right chatID
  if ($row = mysqli_fetch_object($res)) {
    $user = new User($row);
    echo '
    <div class="container">
      <a class="material-icons arrow-back text-primary" onclick="window.history.back();">arrow_back</a>
      <div class="chat-user">
        <a class="reply-icon" href="profile.php?user=' . $user->name . '">
          <img src="' . $user->getAvatar() . '" class="profile-pic-follow"/>
        </a>
        <a class="chat-name post-username" href="profile.php?user=' . $user->name . '"><b>' . $user->name . '</b></a>
        <br>
      </div>
      <hr>
      <div class="chat" id="chat">';
    $chatID = mysqli_real_escape_string($db, $_GET['chat']);
    $chat = getChat($chatID); // Show chat messages
    echo $chat['html'];
    echo '
    </div>
    <div class="form-group chat-input">
      <input type="text" class="form-control" oninput="msgChanged()" onkeydown="sendMsgCheck(' . $row->chatID . ')" id="msg-input" placeholder="Neue Nachricht...">
      <button type="button" id="send-msg-btn" disabled onclick="sendMsg(' . $row->chatID . ')" class="btn btn-primary">Senden</button>
    </div>
  </div>
  <script>
    lastMsg = ' . $chat['lastMsg'] . '*1000;
    var chat = document.getElementById("chat");
    chat.scrollTop = chat.scrollHeight;
    startTimeout(' . $chatID . ');</script>';
  } else {
    header('Location: chats.php'); // ChatID not valid -> Redirect
  }
  // Lists all chats with users
} else {
  echo '<div class="container"><h1>Chats</h1>' . getChats() . '</div>';
}

include 'src/php/footer.php'; ?>
