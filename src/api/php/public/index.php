<?php

require_once '/var/www/shared/db.php';
require_once '/var/www/shared/router.php';

require_once '/var/www/api/private/url_validator.php';
require_once '/var/www/api/private/controllers/book_controller.php';
require_once '/var/www/api/private/controllers/cart_controller.php';

//handle authorization of requesting URL
$allowedOrigins = [
    "",
    'http://localhost',
    'http://admin.localhost',
];

if (!UrlValidator::validate($allowedOrigins)) {
    exit;
}

function openDB(): RelationalDatabase
{
    return new RelationalDatabase(
        $_ENV['POSTGRES_DRIVER'],
        $_ENV['POSTGRES_HOST'],
        $_ENV['POSTGRES_PORT'],
        $_ENV['POSTGRES_DB'],
        $_ENV['POSTGRES_USER'],
        $_ENV['POSTGRES_PASSWORD'],
        $_ENV['POSTGRES_CHARSET']
    );
}

$db = openDB();
$router = new Router();
$bookController = new BookController($db);
$cartController = new CartController($db);

$router->get('/books', [$bookController, 'getBooks']);
$router->get('/books/{id}', fn($id) => $bookController->getBookById($id));

$router->get('/genres', function () {

    $queryString = $_SERVER['QUERY_STRING'];
    $parameters = [];
    parse_str($queryString, $parameters);

    $active = $parameters['active'] ?? false;

    $db = openDB();

    $query = QueryBuilder::table('bookstore.genres', 'g')
        ->select([
            'g.genre_id',
            'g.name',
            'COUNT(bg.book_id) AS book_count'
        ])
        ->groupBy([
            'g.genre_id',
            'g.name'
        ]);

    $active ?
        $query->innerJoin('bookstore.book_genres', 'g.genre_id', 'bg.genre_id', 'bg') :
        $query->leftJoin('bookstore.book_genres', 'g.genre_id', 'bg.genre_id', 'bg');

    $data = $db->executeAndReturnAll($query);

    echo json_encode($data);
});

$router->post(
    '/users',
    function () {
        $data = json_decode(file_get_contents('php://input'), true);

        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $db = openDB();
        $queryBuilder = QueryBuilder::table('bookstore.users')
            ->insert([
                'email' => $data['email'],
                'first_name' =>  $data['firstName'],
                'last_name' => $data['lastName'],
                'password' => $password
            ]);

        $json = [];
        $json['success'] = $db->execute($queryBuilder);
        echo json_encode($json);
    }
);

$router->post(
    '/add_book_to_cart',
    [$cartController, 'addBook']
);

$router->get(
    '/cart',
    [$cartController, 'getCart']
);

$router->delete(
    '/cart',
    [$cartController, 'removeItem']
);

$router->post(
    '/auth/login',
    function () {
        $data = json_decode(file_get_contents('php://input'), true);

        $db = openDB();
        $queryBuilder = QueryBuilder::table('bookstore.users')
            ->select()
            ->where([
                'email' => $data['email']
            ]);

        $result = $db->executeAndReturnOne($queryBuilder);

        $json = [];
        $json['success'] = password_verify($data['password'], $result['password']);
        echo json_encode($json);
    }
);
$router->dispatch();
