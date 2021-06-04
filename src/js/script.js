/**
 * File: script.js
 * Functions loaded with body
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
if(localStorage.getItem('light')) {
    modeChange();
} else {
    document.getElementById("light-dark-switch").click();
}

/**
 * Load feedback (set like or dislike)
 * @param boolean like is post liked or disliked
 * @param string postID the post id
 */
function feedback(like, postID){
    const request = new XMLHttpRequest();
    request.open('GET', `http://localhost/api.php?postID=${postID}&like=${like}`);
    request.setRequestHeader('Accept', 'text/plain');
    
    request.onreadystatechange = function() {
      if(request.readyState == 4) {
        if(request.status == 200) {
            if(like) {
                let like = document.getElementById("like-count" + postID).innerHTML;
                if(document.getElementById("dislike-btn" + postID).classList.contains("text-danger")){
                    document.getElementById("like-count" + postID).innerHTML = parseInt(like) + 2;
                } else if(document.getElementById("like-btn" + postID).classList.contains("text-success")){
                    document.getElementById("like-count" + postID).innerHTML = parseInt(like) - 1;
                } else {
                    document.getElementById("like-count" + postID).innerHTML = parseInt(like) + 1;
                }
                document.getElementById("like-btn" + postID).classList.toggle("text-success");
                document.getElementById("dislike-btn" + postID).classList.remove("text-danger");
            } else {
                let like = document.getElementById("like-count" + postID).innerHTML;
                if(document.getElementById("like-btn" + postID).classList.contains("text-success")) {
                    document.getElementById("like-count" + postID).innerHTML = parseInt(like) - 2;
                } else if(document.getElementById("dislike-btn" + postID).classList.contains("text-danger")){
                    document.getElementById("like-count" + postID).innerHTML = parseInt(like) + 1;
                } else {
                    document.getElementById("like-count" + postID).innerHTML = parseInt(like) - 1;
                }
                document.getElementById("like-btn" + postID).classList.remove("text-success");
                document.getElementById("dislike-btn" + postID).classList.toggle("text-danger");
            }
        } else if(request.status == 401){
            openSnackbar("Nicht authorisiert!");
        } else {
            openSnackbar("Fehler");
        }
      }
    };
    request.send();
}

/**
 * Set light or dark theme
 */
function modeChange() {
    let checked = document.getElementById("light-dark-switch").checked;
    if(checked) {
        // Change to dark
        document.getElementById('light-dark-icon').innerHTML = "nightlight_round";
        if(document.getElementById('bootstrap')) document.getElementById('bootstrap').remove();
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.id = 'bootstrap';
        link.href = 'src/css/bootstrap.darkly.css';
        document.head.appendChild(link);
        if(localStorage.getItem('light')) {
            localStorage.removeItem('light');
        }
        let footer = document.getElementsByClassName('footer');
        for(i = 0; i < footer.length; i++) {
            footer[i].style.backgroundColor = '#575757';
        }
        let postusername = document.getElementsByClassName('post-username');
        for(i = 0; i < postusername.length; i++) {
            postusername[i].style.color = 'white';
        }
        let searchbar = document.getElementsByClassName('searchbar');
        for(i = 0; i < searchbar.length; i++) {
            searchbar[i].style.backgroundColor = '#353b48';
        }
        let searchbarMain = document.getElementsByClassName('searchbar-main');
        for(i = 0; i < searchbarMain.length; i++) {
            searchbarMain[i].style.backgroundColor = '#353b48';
        }
        try {
            document.getElementById('postContent').style.color = "white";
            document.getElementById('change-description').style.color = "white";
        } catch(e) {}
    } else {
        // Change to light
        document.getElementById('light-dark-icon').innerHTML = "wb_sunny";
        document.getElementById('light-dark-label').style.color = "white";
        if(document.getElementById('bootstrap')) document.getElementById('bootstrap').remove();
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.id = 'bootstrap';
        link.href = 'src/css/bootstrap.flatly.css';
        document.head.appendChild(link);
        localStorage.setItem('light', 'light');
        let footer = document.getElementsByClassName('footer');
        for(i = 0; i < footer.length; i++) {
            footer[i].style.backgroundColor = '#a7a7a7';
        }
        let postusername = document.getElementsByClassName('post-username');
        for(i = 0; i < postusername.length; i++) {
            postusername[i].style.color = 'black';
        }
        let searchbar = document.getElementsByClassName('searchbar');
        for(i = 0; i < searchbar.length; i++) {
            searchbar[i].style.backgroundColor = '#375a7f';
        }
        let searchbarMain = document.getElementsByClassName('searchbar-main');
        for(i = 0; i < searchbarMain.length; i++) {
            searchbarMain[i].style.backgroundColor = '#375a7f';
        }
        document.getElementById('postContent').style.color = "black";
        document.getElementById('change-description').style.color = "black";
    }
}

/**
 * Search posts
 * @param string query search terms
 */
function search(query) {
    window.open(`index.php?query=${query.replace('#', '%23')}`, "_self");
}

/**
 * Open a user page
 * @param string user name of the user should be opened
 */
function openUser(user) {
    window.open(`profile.php?user=${user.slice(1)}`, "_self");
}

/**
 * Open selected profile tab
 * @param event evt triggered event
 * @param int tabId id of the tab
 */
function openProfileTabs(evt, tabId) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabId).style.display = "block";
    evt.currentTarget.className += " active";
}

/**
 * Opens a snackbar with error or success styling
 * @param string message snackbar message
 * @param boolean error define styling (error or success) of snackbar
 */
function openSnackbar(message, error) {
    const snackBar = document.getElementById("snackbar");
    snackBar.className = "show";
    error ? snackBar.className += " error" : snackBar.className += " success";
    snackBar.innerHTML = message;
    setTimeout(() => {
        snackBar.className = snackBar.className.replace("show", "");
    }, 3000);
}

/**
 * Opens notifcation 
 */
function openNotifications() {
    const request = new XMLHttpRequest();
    request.open('GET', `http://localhost/api.php?openNotification=true`);
    request.setRequestHeader('Accept', 'text/plain');
    
    request.onreadystatechange = function() {
      if(request.readyState == 4) {
        if(request.status == 200) {
        } else if(request.status == 401){
            openSnackbar("Nicht authorisiert!");
        } else {
            openSnackbar("Fehler");
        }
      }
    };
    request.send();
}

/**
 * Deletes post by id
 * @param string id id of the post
 */
function deletePost(id) {
    if(confirm('Wollen Sie diesen Post wirklich löschen?')) {

        const request = new XMLHttpRequest();
        request.open('GET', `http://localhost/api.php?postID=${id}&delete=true`);
        request.setRequestHeader('Accept', 'text/plain');

        request.onreadystatechange = function() {
          if(request.readyState == 4) {
            if(request.status == 200) {
                openSnackbar('Post erfolgreich gelöscht', false);
                location.reload();
            } else if(request.status == 401){
                openSnackbar('Du hast hierfür keine Berechtigungen', true);
            } else {
                openSnackbar('Ein Fehler ist aufgetreten', true);
            }
          }
        };
        request.send();
    }
}


function sendMsg(chatID) {
    let msg = document.getElementById("msg-input").value;

    var formData = new FormData();
    formData.append("chat", chatID);
    formData.append("message", msg);
    
    const request = new XMLHttpRequest();
    request.open('POST', `http://localhost/api.php`);
    request.setRequestHeader('Accept', 'text/plain');
    let ownID = sending.counter+=1;
    sending = {status: true, counter: ownID};
    clearTimeout(timeout);
    request.onreadystatechange = function() {
      if(request.readyState == 4) {
        if(request.status == 200) {
            let msg = document.getElementById("msg-input").value;
            
            document.getElementById("msg-input").value = "";
            document.getElementById("send-msg-btn").disabled = true;
            console.log(request.responseText);
            console.log(ownID, sending);
            if(sending.counter == ownID) {
                sending.status = false;
            }
            if(request.responseText == "true") {
                if(!sending.status) {
                    startTimeout(chatID);
                }
            }
        } else if(request.status == 401){
            openSnackbar('Du hast hierfür keine Berechtigungen', true);
        } else {
            openSnackbar('Ein Fehler ist aufgetreten', true);
        }
      }
    };
    request.send(formData);
}

function sendMsgCheck(chatID) {
    let msg = document.getElementById("msg-input").value;
    if(event.key === 'Enter' && msg != "") {
        sendMsg(chatID);      
    }
}

function msgChanged() {
    let msg = document.getElementById("msg-input").value;
    if(msg != "" ) {
        document.getElementById("send-msg-btn").disabled = false;
    } else {
        document.getElementById("send-msg-btn").disabled = true;
    }
}