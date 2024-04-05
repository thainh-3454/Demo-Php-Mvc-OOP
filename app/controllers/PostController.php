<?php

require_once './app/middleware/PostMiddleware.php';
require_once './app/middleware/AuthMiddleware.php';


class PostController extends Controller
{
    private $postModel;
    private $postMiddleware;
    public function __construct()
    {
        //gọi model User
        $this->postModel = $this->model('Post');
        $authMiddleware = new AuthMiddleware();
        $authMiddleware->checkLogin();
        $this->postMiddleware = new PostMiddleware();
    }

    public function index()
    {
        //gọi method getuser
        $post  = $this->postModel->getPost();

        //gọi và show dữ liệu ra view
        $this->view('post/index', [
            'post' => $post,
            'csrf_token' => Helper::generateCsrfToken()
        ]);
    }

    public function create()
    {
        $fields = ['post_name', 'post_description', 'post_type'];
        $postInput = Helper::getFormValues($fields);

        $checkFields = [
            'post_name' => ['required' => true, 'min_length' => 2, 'max_length' => 50],
            'post_description' => ['required' => false, 'max_length' => 50],
        ];

        $errors = [];
        $result = null;

        if (isset($_POST['submit'])) {
            Helper::checkCsrfToken();
            Helper::validateFields($checkFields, $errors);

            // If no validation errors, proceed with creating user
            if (empty($errors)) {
                $result = $this->postModel->createPost(
                    $postInput
                );
                if ($result) {
                    header('location:' . URLROOT . '/Post/index');
                    exit(); // Ensure that no further code executes after redirection
                }
            }
        }


        $this->view('post/create', [
            'displayValue' => $postInput,
            'errors' => $errors,
            'result' => $result,
            'csrf_token' => Helper::generateCsrfToken(),
        ]);
    }

    public function update($id)
    {

        $findPost = $this->postModel->findPostById($id);
        $this->postMiddleware->checkPost($findPost['id'], $findPost['user_id']);
        $fields = ['id', 'post_name', 'post_description', 'post_type'];
        $postInput = Helper::getFormValues($fields, $findPost);

        $checkFields = [
            'post_name' => ['required' => true, 'min_length' => 2, 'max_length' => 50],
            'post_description' => ['required' => false, 'max_length' => 50],
        ];

        $errors = [];
        $result = null;


        if (isset($_POST['submit']) && $findPost) {
            Helper::checkCsrfToken();

            Helper::validateFields($checkFields, $errors);
            if (empty($errors)) {


                $result = $this->postModel->updatePost($id, $postInput);
                if ($result) {
                    header('location:' . URLROOT . '/Post/index');
                }
            }
        }

        $this->view('post/update', [
            'displayValue' => $postInput,
            'errors' => $errors,
            'result' => $result,
            'csrf_token' => Helper::generateCsrfToken(),
        ]);
    }

    public function delete($id)
    {
        Helper::checkCsrfToken();
        $delete = $this->postModel->deletePost($id);
        if ($delete) {
            header('location:' . URLROOT . '/Post/index');
        }
        $this->view('post/index', ["result" => "Failed to delete post"]);
    }
}
