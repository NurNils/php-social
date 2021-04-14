function feedback(like, userID, postID){
    const request = new XMLHttpRequest();
    // TODO Add token
    request.open('GET', `http://localhost/api.php?token=1&userID=${userID}&postID=${postID}&like=${like}`);
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