<?php

class AuthMiddleware extends Middleware
{
    public function checkLogin()
    {

        if (!isset($this->userID)) {
            $this->redirect('Authenticate/login');
        }
    }
}
