<?php
class Post
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getPost()
    {
        $sql = "SELECT * FROM posts";
        return $this->db->select($sql);
    }

    public function createPost($posts)
    {
        Helper::startSession();

        $userID = $_SESSION['user_id'];
        list("post_name" => $postName, "post_description" => $postDescription, "post_type" => $postType) = $posts;

        $conn = $this->db->getConnection();


        $sql = "INSERT INTO posts (post_name,post_description,post_type,user_id) VALUES (?, ?, ?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $postName, $postDescription, $postType, $userID);
        $success = $stmt->execute();


        $stmt->close();
        $conn->close();

        if ($success) {
            return true;
        }

        return false;
    }

    public function findPostById($id)
    {
        $conn = $this->db->getConnection();



        $sql = "SELECT * FROM posts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // 'i' indicates integer type for the parameter
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();

        return $post;
    }

    public function updatePost($id, $postInfor)
    {
        list("post_name" => $postName, "post_description" => $postDescription, "post_type" => $postType) = $postInfor;

        $conn = $this->db->getConnection();
        $userID = $_SESSION['user_id'];
        $sql = "UPDATE posts SET post_name = ?, post_description = ?, post_type = ?,
         update_at =NOW(), user_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $postName, $postDescription, $postType, $userID, $id);

        // Execute the statement
        $success = $stmt->execute();

        if ($success) {
            return true;
        }

        return false;
    }

    public function deletePost($id)
    {

        $conn = $this->db->getConnection();

        $sql = "DELETE FROM posts WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $success = $stmt->execute();

        if ($success) {
            return true;
        }

        return false;
    }
}
