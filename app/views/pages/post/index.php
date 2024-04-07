<?php
// Define the function outside the HTML section
function getTypeName($type)
{
    if ($type == 1) {
        return "Technology";
    }
    if ($type == 2) {
        return "Funny";
    }
    if ($type == 3) {
        return "Share";
    }
}
require_once APPROOT . '/views/includes/header.php';
?>
<div class="container">
    <?php if (isset($data['result'])) : ?>
        <script type="text/javascript">
            toastr.error('Failed to delete a post');
        </script>
    <?php endif; ?>

    <h2 style="margin-bottom: 10px;">
        Post Page
    </h2>

    <a class="btn green" style="margin-bottom: 10px;" href="<?= URLROOT ?>/Post/create">
        Create Post
    </a>

    <?php if (empty($data['post'])) : ?>
        <h3>There is no post</h3>
    <?php else : ?>
        <table class="table" style="margin-top: 25px;">
            <thead>
                <tr>
                    <th>Post Name</th>
                    <th>Post Type</th>
                    <th>Post Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['post'] as $post) : ?>
                    <tr>
                        <td><?= $post['post_name'] ?></td>
                        <td><?= getTypeName($post['post_type']) ?></td>
                        <td><?= $post['post_description'] ?></td>
                        <td>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) : ?>
                                <form action="<?= URLROOT ?>/Post/delete/<?= $post['id'] ?>" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">
                                    <button class="btn red" type="submit">Delete</button>
                                </form>
                                <a class="btn orange" href="<?= URLROOT ?>/Post/update/<?= $post['id'] ?>">Update</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>