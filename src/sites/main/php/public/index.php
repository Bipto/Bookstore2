<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Selby Bookstore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
</head>

<body>
    <?php

    require_once '/var/www/shared/menubar.php';
    require_once '/var/www/shared/router.php';

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

    $router = new Router();
    $router->register('/', '/var/www/sites/main/public/pages/main.php');
    $router->register('/books', '/var/www/sites/main/public/pages/books.php');
    $router->register('/categories', '/var/www/sites/main/public/pages/categories.php');
    $router->register('/about', '/var/www/sites/main/public/pages/about.php');
    $router->register('/contact', '/var/www/sites/main/public/pages/contact.php');
    $router->dispatch();

    ?>

    <script src="js/menubar.js"></script>
</body>

</html>