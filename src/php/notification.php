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
            <i class="gray">'.prettyTime($this->time).'</i>
        </a>';
    }
}