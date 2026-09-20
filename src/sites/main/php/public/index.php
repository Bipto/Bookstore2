<?php

session_start();

?>

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
    $router->get('/', '/var/www/sites/main/private/pages/main.php');
    $router->get('/books', '/var/www/sites/main/private/pages/books.php');
    $router->get('/categories', '/var/www/sites/main/private/pages/categories.php');
    $router->get('/about', '/var/www/sites/main/private/pages/about.php');
    $router->get('/contact', '/var/www/sites/main/private/pages/contact.php');
    $router->get('/view_book/{book_id}', '/var/www/sites/main/private/pages/view_book.php');
    $router->get('/login', '/var/www/sites/main/private/pages/login.php');
    $router->get('/create_account', '/var/www/sites/main/private/pages/create_account.php');
    $router->get('/logout', '/var/www/sites/main/private/utils/handle_logout.php');

    $router->post('/login', '/var/www/sites/main/private/utils/handle_login.php');
    $router->post('/create_account', '/var/www/sites/main/private/utils/handle_registration.php');

    $router->dispatch();

    ?>

    <script src="/js/menubar.js"></script>
</body>

</html>