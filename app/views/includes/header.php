<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // echo "<h3> PHP List All Session Variables</h3>";
    // foreach ($_SESSION as $key => $val)
    //     echo $key . " " . $val . "<br/>";
    ?>

    <nav class="top-nav">
        <div class="top-main">
            <div class="left">
                <img src="<?= IMAGE ?>/logo-pink.png" width="50px">
            </div>
            <ul>
                <li>
                    <a href="<?php echo URLROOT; ?>/index">Home</a>
                </li>

                <li>
                    <a href="<?php echo URLROOT; ?>/Post/index">Post</a>
                </li>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <!-- User is logged in, display Logout button -->
                    <li>
                        <a href="<?= URLROOT ?>/Authenticate/logout">Logout</a>
                    </li>
                <?php else : ?>
                    <!-- User is not logged in, display Login button -->
                    <li>
                        <a href="<?= URLROOT ?>/Authenticate/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>