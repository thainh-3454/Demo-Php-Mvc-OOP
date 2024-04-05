<?php require_once APPROOT . '/views/includes/header.php'; ?>

<div class="container center">
    <h1>
        Login
    </h1>

    <?php $displayValue = $data['displayValue']; ?>
    <?php if (isset($data['result'])  &&  !$data['result']) : ?>
        <script type="text/javascript">
            toastr.error('Wrong email or password');
        </script>
    <?php endif; ?>


    <form action="<?= URLROOT ?>/Authenticate/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">


        <div class="form-item">
            <input type="text" name="email" value="<?= htmlspecialchars($displayValue['email']) ?>" placeholder="Email...">

        </div>

        <?php if (isset($data['errors']['email'])) : ?>
            <p class="error"><?= $data['errors']['email']; ?></p>
        <?php endif; ?>
        <div class="form-item">
            <input type="password" name="password" value="<?= htmlspecialchars($displayValue['password']) ?>" placeholder="Password...">

        </div>
        <?php if (isset($data['errors']['password'])) : ?>
            <p class="error"><?= $data['errors']['password']; ?></p>
        <?php endif; ?>
        <div class="d-flex " style="display: flex; gap: 10px; align-items: center; justify-content: center">
            <input type="checkbox" style="width: 20px; height: 20px" name="remember">
            <div>
                Remember login
            </div>
        </div>

        <div class="form-item">



        </div>
        <button class="btn green" name="submit" type="submit">Login</button>
        <div style="margin-top: 10px;">
            <a href="<?= URLROOT ?>/Authenticate/register">Don't have account? Register here</a>

        </div>
    </form>
</div>