<?php

class Middleware
{
    protected $userID = null;
    public function __construct()
    {
        Helper::startSession();
        $this->userID = $_SESSION['user_id'] ?? null;
    }

    protected function redirect($url)
    {

        header('location:' . URLROOT . '/' . $url);
        exit();
    }
}
