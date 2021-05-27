<?php
class Notification {
    public string $message;
    public string $time;
    public string $username;

    function __construct($row) {
        $this->message = $row->message;
        $this->time = $row->time;
        $this->username = $row->username;
    }

    function getHtml() {
        return 
        '<a class="dropdown-item">
            <b>'.$this->username.'</b> 
            <span class="notifications-message">'.$this->message.'</span>
            <i class="notifications-gray">'.$this->getTime().'</i>
        </a>';
    }

    function getTime(){
        $time = strtotime($this->time);
        $now = strtotime(date("Y-m-d H:i:s"));
        $diff = $now - $time;
        if($diff - 60 < 0) {
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