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
    function () {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = $data['email'];
        $bookId = $data['bookId'];

        $db = openDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare('
            SELECT user_id FROM bookstore.users
            WHERE email = :email
        ');

        $succeeded = $stmt->execute(['email' => $email]);
        $userId = $succeeded ? $stmt->fetch(PDO::FETCH_ASSOC)['user_id'] : null;

        $stmt = $pdo->prepare('
            WITH inserted AS (
                INSERT INTO bookstore.carts (user_id)
                SELECT :user_id
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM bookstore.carts
                    WHERE user_id = :user_id
                )
                RETURNING cart_id
            )
            SELECT cart_id FROM inserted
            UNION ALL
            SELECT cart_id
            FROM bookstore.carts
            WHERE user_id = :user_id
            LIMIT 1;
        ');
        $succeeded = $stmt->execute(['user_id' => $userId]);
        $cartId = $stmt->fetch(PDO::FETCH_ASSOC)['cart_id'] ?? null;

        /* $queryBuilder = QueryBuilder::table('bookstore.users')
            ->insert([
                'email' => $data['email'],
                'first_name' =>  $data['firstName'],
                'last_name' => $data['lastName'],
                'password' => $password
            ]);

        $json = [];
        $json['success'] = $db->execute($queryBuilder);
        echo json_encode($json); */
    }
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
