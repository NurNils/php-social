<?php
class Post {
    public int $id;
    public ?int $referencedPostID; // PostID of parent post
    public ?string $content;
    public ?string $media;
    public string $postDate;
    public string $replycount;
    public int $likecount;
    public ?bool $liked;
    public bool $deleted;
    public User $user; // User that posted the post with all important information

    function __construct($row) {
        $this->id = $row->id;
        $this->referencedPostID = $row->referencedPostID;
        $this->content = $row->content;
        $this->media = $row->media;
        $this->postDate = $row->postDate;
        $this->replycount = $row->replycount;
        $this->likecount = $row->likedcount;
        $this->liked = $row->liked;
        $this->deleted = $row->deleted;

        $this->user = new User($row);
    }

    function getHtml($actions = true) {
        $html = "";
        if($this->deleted) {
            $html = '
            <div class="card post" id="post'.$this->id.'">
                <img src="files/avatar/defaultProfile.png" class="posted-profile-pic"/>
                <div class="card-body post-content">
                    <h5 class="card-title post-headline">
                        <i><a class="post-username deleted">[Gelöscht]</a> </i>
                        <span class="card-subtitle mb-2 text-muted post-date">· &nbsp;' .$this->getTime().'</span>
                    </h5>
                    <p class="card-text deleted">[Gelöscht]</p>
                </div>
            </div>
            ';
        } else {
            $html = '
            <div class="card post" id="post'.$this->id.'">
                <a href="profile.php?user='.$this->user->name.'">
                  <img src="' . $this->user->getAvatar() . '" class="posted-profile-pic"/>
                </a>
                <div class="card-body post-content">
                    <h5 class="card-title post-headline">
                        <a class="post-username"  href="profile.php?user='.$this->user->name.'">'.$this->user->name.'</a> 
                        '.($this->user->verified ? '<b class="material-icons verified-follow">verified</b>' : '').'
                        <span class="card-subtitle mb-2 text-muted post-date">· &nbsp;' .$this->getTime().'</span>
                        '.(($_SESSION['user']->verified || $_SESSION['user']->id == $this->user->id) && $actions ? '<span class="material-icons delete-post text-danger" onclick="deletePost('.$this->id.')">delete</span>' : '').'
                    </h5>
                    <p class="card-text">'.$this->getContent().'</p>
                    '. $this->getMedia() .'
                    ' . ($actions ?  
                    '<span onclick="feedback(1, '.$_SESSION['user']->id.', '.$this->id.')" id="like-btn'.$this->id.'" class="material-icons feedback text-primary '.($this->liked == "1" ? 'text-success' : '').'">thumb_up</span>
                    <span class="like-count text-primary" id="like-count'.$this->id.'">'.$this->likecount.'</span>
                    <span onclick="feedback(0, '.$_SESSION['user']->id.', '.$this->id.')" id="dislike-btn'.$this->id.'" class="text-primary material-icons feedback '.($this->liked == "0" && !is_null($this->liked) ? 'text-danger' : '').'">thumb_down</span>
                    <div class="reply">
                        <a href="post.php?refPost='.$this->id.'" class="material-icons text-success reply-icon">reply</a>
                        <span class="reply-count text-success">'.$this->replycount.'</span>
                    </div>'
                    : ""). '
                </div>
            </div>
            ';
        }
        return $html;
    }

    function getContent() {
        $changedContent = "";
        if(isset($this->content) && $this->content != "") {
            $changedContent = htmlspecialchars($this->content);
            $changedContent = preg_replace('/(?<= |^)(#[a-zA-Z0-9]+)(?= |$)/', '<span class="hashtag" onclick="search(\'$1\')">$1</span>', $changedContent);
            $changedContent = preg_replace('/(?<= |^)(@[a-z0-9_-]{3,16}+)(?= |$)/', '<span class="username" onclick="openUser(\'$1\')">$1</span>', $changedContent);
            $changedContent = preg_replace('/((http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/im', '<a class="content-link" href="$1">$1</a>', $changedContent);
        }
        return $changedContent;
    }

    function getMedia() {
        $image = "";
        if($this->media) {
            $image = "<img src=\"files/post/$this->media\" class=\"post-media\"/><br><br>";
        }
        return $image;
    }

    function getTime(){
        $time = strtotime($this->postDate);
        $now = strtotime(date("Y-m-d H:i:s"));
        $diff = $now - $time;
        if($diff == 0) {
            return "Gerade eben";
        } else if($diff - 60 < 0) {
            // Show seconds
            return $diff." sek";
        } elseif ($diff - 60*60 < 0) {
            // Show minutes
            return round($diff/60)." min";
        } elseif ($diff - 60*60*24 < 0) {
            // Show hours
            return round($diff/60/60)." std";
        } elseif (strftime("%Y", $time) == strftime("%Y", $now)) {
            // Show date
            return strftime("%d %h", $time);
        } else {
            // Show date and year
            return strftime("%d %h %y", $time);
        }
    }
}