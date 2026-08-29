<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Main Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <!-- <h1>Main localhost website</h1>

    <p>

        <?php


        require '/var/www/shared/common.php';
        echo 'You are visiting: ' . siteName();

        ?>

    </p>

    <p>
        PHP version:
        <?= PHP_VERSION ?>
    </p> -->

    <?php

    require_once '/var/www/shared/menubar.php';

    $links = [
        [
            'Home' => '/',
            'Books' => '/books',
            'Categories' => '/categories',
            'About' => '/about',
            'Contact' => '/contact'
        ]
    ];

    $menubar = new Menubar($links);
    echo $menubar->build();

    ?>

    <script src="js/menubar.js"></script>
</body>

</html>