function feedback(like, userID, postID){
    console.log(postID)
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
                document.getElementById("like-btn" + postID).classList.toggle("text-success");
                document.getElementById("dislike-btn" + postID).classList.remove("text-danger");
            } else {
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