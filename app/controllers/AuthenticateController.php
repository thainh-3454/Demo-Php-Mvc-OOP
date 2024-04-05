<?php
class AuthenticateController extends Controller
{
    private $userModel;
    public function __construct()
    {
        //gọi model User
        $this->userModel = $this->model('User');
    }

    public function login()
    {

        $fields = ['email', 'password', 'remember'];
        $userInput = Helper::getFormValues($fields);

        $checkFields = [
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true, 'min_length' => 6, 'max_length' => 30],
        ];

        $errors = [];
        $result = null;

        if (isset($_POST['submit'])) {
            Helper::validateFields($checkFields, $errors);
            Helper::checkCsrfToken();

            // If no validation errors, proceed with creating user
            if (empty($errors)) {
                $result = $this->userModel->login(
                    $userInput
                );

                if ($result === "locked") {
                    $errors['email'] = 'User is locked';
                } elseif ($result) {
                    header('location:' . URLROOT . '/home/index');
                    exit(); // Ensure that no further code executes after redirection
                }
            }
        }

        $csrf_token =  Helper::generateCsrfToken();

        $this->view('authenticate/login/index', [
            'displayValue' => $userInput,
            'errors' => $errors,
            'result' => $result,
            'csrf_token' => $csrf_token
        ]);
    }



    public function register()
    {
        $fields = ['name', 'email', 'password', 're-password'];
        $userInput = Helper::getFormValues($fields);

        $checkFields = [
            'name' => ['required' => true, 'min_length' => 3, 'max_length' => 50],
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true, 'min_length' => 6, 'max_length' => 30, 'match' => 're-password'],
        ];

        $errors = [];
        $result = null;

        if (isset($_POST['submit'])) {
            Helper::checkCsrfToken();
            Helper::validateFields($checkFields, $errors);

            // If no validation errors, proceed with creating user
            if (empty($errors)) {
                $result = $this->userModel->register(
                    $userInput
                );

                if ($result === "exist email") {
                    $errors['email'] = "Email already exist";
                } elseif ($result) {
                    header('location:' . URLROOT . '/Authenticate/login');
                    exit(); // Ensure that no further code executes after redirection
                }
            }
        }

        $this->view('authenticate/register/index', [
            'displayValue' => $userInput,
            'errors' => $errors,
            'result' => $result,
            'csrf_token' => Helper::generateCsrfToken()
        ]);
    }

    public function logout()
    {

        $this->userModel->clearSession();

        // Redirect to login page
        header('location:' . URLROOT . '/Authenticate/login');
        exit;
    }
}
