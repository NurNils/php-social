<?php
/**
 * File: user.php
 * User class to create a user object
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
/**
 * Name: User
 * 
 * Represents a user with default settings
 */
class User {
    public int $id;
    public string $name;
    public bool $verified;
    public ?string $avatar;
    public ?string $banner;

    function __construct($row) {
        $this->id = $row->userID;
        $this->name = $row->username;
        $this->verified = $row->verified;
        $this->avatar = $row->avatar;
        $this->banner = isset($row->banner) ? $row->banner : NULL;
    }

    /**
     * Gets the users avatar or if not exist the default avatar
     * @return string 
     */
    function getAvatar() {
        return 'files/avatar/' . ($this->avatar ? $this->avatar : 'defaultProfile.png');
    }

    /**
     * Gets the users banner or if not exist the default banner
     * @return string 
     */
    function getBanner() {
        return 'files/banner/' . ($this->banner ? $this->banner : 'notFound.png');
    }
}