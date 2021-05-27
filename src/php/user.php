<?php
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

    function getAvatar() {
        return 'files/avatar/' . ($this->avatar ? $this->avatar : 'defaultProfile.png');
    }

    function getBanner() {
        return 'files/banner/' . ($this->banner ? $this->banner : 'notFound.png');
    }
}