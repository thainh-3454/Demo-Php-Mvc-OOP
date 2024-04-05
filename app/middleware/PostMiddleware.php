<?php

class PostMiddleware extends Middleware
{
    

    private function checkPostId($postId)
    {

        if (!isset($postId)) {
            $this->redirect('Post/index');
        }
    }

    private function checkPostOwner($userPostId)
    {

        if ($this->userID != $userPostId) {
            $this->redirect('Post/index');
        }
    }

    public function checkPost($postId, $userPostId)
    {
        $this->checkPostId($postId);
        $this->checkPostOwner($userPostId);
    }
}
