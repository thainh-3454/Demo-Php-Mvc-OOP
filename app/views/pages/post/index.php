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

    <h2 style="margin-bottom: 10px">
        Post Page
    </h2>

    <a class="btn green" href="<?= URLROOT ?>/Post/create">
        Create Post
    </a>


    <?php if (empty($data['post'])) : ?>
        <h3>There is no post</h3>
    <?php else : ?>
        <?php foreach ($data['post'] as $post) : ?>
            <div class="container-item">


                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) : ?>
                    <form action="<?= URLROOT ?>/Post/delete/<?= $post['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">
                        <button class="btn red" type="submit">Delete</button>
                    </form>
                    <a class="btn orange" href="<?= URLROOT ?>/Post/update/<?= $post['id'] ?>">Update</a>
                <?php endif; ?>

                <div style="display:flex; gap: 10px; align-items:center">
                    <h2><?= $post['post_name'] ?></h2>
                    <p class="post-type "><?= getTypeName($post['post_type']) ?></p>

                </div>
                <h3><?= $post['post_description'] ?></h3>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>