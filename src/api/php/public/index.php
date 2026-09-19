<?php

require_once '/var/www/shared/db.php';
require_once '/var/www/shared/router.php';

require_once '/var/www/api/private/url_validator.php';

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

$router = new Router();
$router->get('/books', function () {
    $db = openDB();
    $queryBuilder = QueryBuilder::table('bookstore.books')
        ->select();

    $result = $db->executeAndReturnAll($queryBuilder);

    echo json_encode($result);
});
$router->get('/books/{id}', function ($id) {
    $db = openDB();
    $queryBuilder = QueryBuilder::table('bookstore.books')
        ->select()
        ->where(['book_id' => $id]);

    $result = $db->executeAndReturnOne($queryBuilder);
    echo json_encode($result);
});

$router->post(
    '/users',
    function () {
        $data = json_decode(file_get_contents('php://input'), true);

        $db = openDB();
        $queryBuilder = QueryBuilder::table('bookstore.users')
            ->insert([
                'email' => $data['email'],
                'first_name' =>  $data['firstName'],
                'last_name' => $data['lastName'],
                'password' => $data['password']
            ]);
        $db->execute($queryBuilder);
    }

);

$router->dispatch();
