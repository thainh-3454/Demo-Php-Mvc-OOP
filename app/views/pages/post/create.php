<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container center">
    <h1>
        Create new post
    </h1>


    <?php $displayValue = $data['displayValue'];

    ?>

    <?php if (isset($data['result'])  &&  !$data['result']) : ?>
        <script type="text/javascript">
            toastr.error('Failed to create a post');
        </script>
    <?php endif; ?>

    <form action="<?= URLROOT ?>/Post/create" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">
        <div class="form-item">
            <input type="text" name="post_name" value="<?= htmlspecialchars($displayValue['post_name']) ?>" placeholder="Post Name...">
            <?php if (isset($data['errors']['post_name'])) : ?>
                <p class="error"><?= $data['errors']['post_name']; ?></p>
            <?php endif; ?>
        </div>
        <div class="form-item">
            <input type="text" name="post_description" value="<?= htmlspecialchars($displayValue['post_description']) ?>" placeholder="Post description...">
            <?php if (isset($data['errors']['post_description'])) : ?>
                <p class="error"><?= $data['errors']['post_description']; ?></p>
            <?php endif; ?>
        </div>
        <div class="form-item">
            <label for="post_type">Post type:</label>
            <select name="post_type" id="post_type">
                <option value="1">Technology</option>
                <option value="2">Funny</option>
                <option value="3">Share</option>
            </select>

        </div>
        <button class="btn green" name="submit" style="margin: 10px;" type="submit">Create</button>
    </form>
</div>