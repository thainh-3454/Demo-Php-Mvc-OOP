<?php require_once APPROOT . '/views/includes/header.php'; ?>
<div class="container">


    <h2>
        HOME - PHP
    </h2>
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) : ?>
        <!-- User is logged in, display user's name -->
        <h4>
            Welcome <?= $_SESSION['user_name'] ?>
        </h4>
    <?php else : ?>
        <!-- User is not logged in -->
        <h4>
            You are not logged in
        </h4>
    <?php endif; ?>


    <a class="btn green" href="<?= URLROOT ?>/Post/index">
        Go to Post page
    </a>

</div>