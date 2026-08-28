<!DOCTYPE html>
<html>

<head>
    <title>Main Site</title>
    <link rel="stylesheet" href="/css/main.css">
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
            'Home' => 'index.php',
            'Books' => 'books.php',
            'About' => 'about.php'
        ]
    ];

    $menubar = new Menubar($links);
    echo $menubar->build();

    ?>

    <script src="js/menubar.js"></script>
</body>

</html>