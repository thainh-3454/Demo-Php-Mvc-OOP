<?php
$postTypeOptions = ["Technology" => 1, "Funny" => 2, "Share" => 3];

require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container center">
    <h1>
        Update Post
    </h1>


    <?php $displayValue = $data['displayValue'];

    ?>

    <?php if (isset($data['result'])  &&  !$data['result']) : ?>
        <script type="text/javascript">
            toastr.error('Failed to update a post');
        </script>
    <?php endif; ?>



    <form action="<?= URLROOT ?>/Post/update/<?= $displayValue['id'] ?>" method="POST">
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
                <?php foreach ($postTypeOptions as $key => $value) : ?>
                    <option value="<?= $value ?>" <?= $value == $displayValue['post_type'] ? 'selected' : '' ?>><?= $key ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <button class="btn green" name="submit" style="margin: 10px;" type="submit">Update</button>
    </form>
</div>