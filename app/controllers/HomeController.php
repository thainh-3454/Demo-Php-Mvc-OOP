<?php
class HomeController extends Controller
{
    private $userModel;
    public function __construct()
    {
        //gọi model User
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        //gọi method getuser
        $user  = $this->userModel->getUser();

        //gọi và show dữ liệu ra view
        $this->view('home/index', [
            'user' => $user
        ]);
    }

}
