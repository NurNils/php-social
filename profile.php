<?php
$currentpage = "profile";
include('src/php/header.php');

if(isset($_GET['user'])){

    $sql = "SELECT * FROM login WHERE username='" . htmlspecialchars($_GET['user']) . "'";
    $res = $db->query($sql);

    while($row = mysqli_fetch_object($res)) {
        $username = $row->username;
    }
    echo('
    <div class="container">
        <b>' . $username . '</b>
        <p>Stream Money Boy "Feed the Skreetz" OUT NOW</p>
        <p>Join Date: October 2018</p>
        <p></p>
    
    
    
    </div>
    
    
    
    ');

}
else{
    echo('Undefined User');
}









include('src/php/footer.php');
?>