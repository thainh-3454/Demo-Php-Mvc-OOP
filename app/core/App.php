<?php

class App
{
    protected $controller = 'HomeController';
    protected $action = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->urlProcess();
        //lọc controller
        if (isset($url[0])) {
            //nếu người dùng nhập controller nào tồn tại thì 
            if (file_exists('./app/controllers/' . $url[0] . 'Controller' . '.php')) {
                //gán controller đó
                $this->controller = $url[0] . 'Controller';
            }
            unset($url[0]);
        }
        //nếu người dùng nhập controller ko tồn tại hoặc ko nhập controller thì mặc định controller = 'Home'
        require_once './app/controllers/' . $this->controller . '.php';
        //khởi tại controller mới
        $this->controller = new $this->controller;

        //lọc action
        if (isset($url[1])) {
            //nếu action người dùng nhập vào có tồn tại trong controller thì
            if (method_exists($this->controller, $url[1])) {
                //gán action đó
                $this->action = $url[1];
            }
            unset($url[1]);
        }

        $this->checkRemember();

        //get params
        //nếu tồn tại tham số thì gán, ngược lại params = rỗng
        $this->params = $url ? array_values($url) : [];

        //khởi tại class từ controller
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    public function urlProcess()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $filter = filter_var($url);
            return explode('/', $filter);
        }
    }

    public function checkRemember()
    {

        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        if (isset($_COOKIE['remember_me'])) {


            list($userId, $token) = explode(':', $_COOKIE['remember_me']);

            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $userId);
            $stmt->execute();


            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            // Verify that the token matches the one stored in the database
            if ($user && hash_equals($user['remember_token'], $token)) {

                Helper::startSession();
                // Log the user in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
            }
        }
    }
}
