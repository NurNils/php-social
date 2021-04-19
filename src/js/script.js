if(localStorage.getItem('light')) {
    modeChange();
} else {
    document.getElementById("light-dark-switch").click();
}

function feedback(like, userID, postID){
    const request = new XMLHttpRequest();
    // TODO Add token
    request.open('GET', `http://localhost/api.php?userID=${userID}&postID=${postID}&like=${like}`);
    // request.setRequestHeader('Authorization', `Basic ${getToken()}`);
    request.setRequestHeader('Accept', 'text/plain');
    request.responseType = 'json';
    
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
            alert("unauthorized")
        } else {
            alert("error")
        }
      }
    };
    request.send();
}

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
    }
}

function search(query) {
    window.open(`index.php?query=${query.replace('#', '%23')}`, "_self");
}

function openUser(user) {
    window.open(`profile.php?user=${user.slice(1)}`, "_self");
}