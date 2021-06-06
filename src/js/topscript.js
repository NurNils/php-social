/**
 * File: topscript.js
 * Functions loaded before anything else
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */

let lastMsg = 0;
let timeout;
let sending = { status: false, counter: 0 };

/**
 * Opens a snackbar with error or success styling
 * @param string message snackbar message
 * @param boolean error define styling (error or success) of snackbar
 */
function openSnackbar(message, error) {
  const snackBar = document.getElementById('snackbar');
  snackBar.className = 'show';
  error ? (snackBar.className += ' error') : (snackBar.className += ' success');
  snackBar.innerHTML = message;
  setTimeout(() => {
    snackBar.className = snackBar.className.replace('show', '');
  }, 3000);
}

/**
 * Refreshes chat messages of a specified chat
 * @param string chatID id of the chat
 */
function refreshMessages(chatID) {
  var formData = new FormData();
  formData.append('lastMsg', lastMsg / 1000);
  formData.append('chat', chatID);

  const request = new XMLHttpRequest();
  request.open('POST', `http://localhost/api.php`);
  request.setRequestHeader('Accept', 'text/plain');

  request.onreadystatechange = function () {
    if (request.readyState == 4) {
      if (request.status == 200) {
        console.log(request.responseText);
        try {
          res = JSON.parse(request.responseText);
          lastMsg = res.lastMsg * 1000;
          if (res.html != '') {
            document.getElementById('chat').innerHTML += res.html;
            var chat = document.getElementById('chat');
            chat.scrollTop = chat.scrollHeight;
          }
        } catch (e) {
          openSnackbar('Ein Fehler ist aufgetreten', true);
        }
      } else if (request.status == 401) {
        openSnackbar('Du hast hierfür keine Berechtigungen', true);
      } else {
        openSnackbar('Ein Fehler ist aufgetreten', true);
      }
    }
  };
  request.send(formData);
}

/**
 * Starts timeout to refresh messages
 * @param string chatID id of the chat
 */
function startTimeout(chatID) {
  timeout = setTimeout(() => {
    if (!sending.status) refreshMessages(chatID);
    startTimeout(chatID);
  }, 500);
}
