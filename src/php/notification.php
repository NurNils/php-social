<?php
/**
 * File: notification.php
 * Notification class to create a notification object 
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
/**
 * Name: Notification
 * 
 * Represents a notification for the user
 */
class Notification {
    public string $message;
    public string $time;
    public string $username;

    function __construct($row) {
        $this->message = $row->message;
        $this->time = $row->time;
        $this->username = $row->username;
    }

    /**
     * Get html content for the notification drop down
     * @return string 
     */
    function getHtml() {
        return 
        '<a class="dropdown-item">
            <b>'.$this->username.'</b> 
            <span class="notifications-message">'.$this->message.'</span>
            <i class="gray">'.prettyTime($this->time).'</i>
        </a>';
    }
}