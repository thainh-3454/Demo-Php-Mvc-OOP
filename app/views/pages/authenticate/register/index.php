<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container center">
    <h1>
        Register
    </h1>


    <?php $displayValue = $data['displayValue']; ?>

    <form action="<?= URLROOT ?>/Authenticate/register" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">
        
        <div class="form-item">
            <input type="text" value="<?= htmlspecialchars($displayValue['email']) ?>" name="email" placeholder="Email...">

        </div>
        <?php if (isset($data['errors']['email'])) : ?>
            <p class="error"><?= $data['errors']['email']; ?></p>
        <?php endif; ?>
        <div class="form-item">
            <input type="text" value="<?= htmlspecialchars($displayValue['name']) ?>" name="name" placeholder="Name...">
        </div>
        <?php if (isset($data['errors']['name'])) : ?>
            <p class="error"><?= $data['errors']['name']; ?></p>
        <?php endif; ?>
        <div class="form-item">
            <input type="password" value="<?= htmlspecialchars($displayValue['password']) ?>" name="password" placeholder="Password...">
        </div>
        <?php if (isset($data['errors']['password'])) : ?>
            <p class="error"><?= $data['errors']['password']; ?></p>
        <?php endif; ?>
        <div class="form-item">
            <input type="password" value="<?= htmlspecialchars($displayValue['re-password']) ?>" name="re-password" placeholder="Re-enter Password...">
        </div>
        <button class="btn green" name="submit" type="submit">Register</button>
        <div style="margin-top: 10px;">
            <a href=" <?= URLROOT ?>/Authenticate/login">Login here!</a>
        </div>
    </form>
</div>