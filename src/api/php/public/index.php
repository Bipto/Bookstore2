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

    $queryString = $_SERVER['QUERY_STRING'];
    $parameters = [];
    parse_str($queryString, $parameters);

    $title = $parameters['title'] ?? '';
    $author = $parameters['author'] ?? '';

    $db = openDB();
    $pdo = $db->getPDO();
    $stmt = $pdo->prepare("
    SELECT
        b.book_id,
        b.title,
        a.name AS author,
        b.book_description,
        b.price,
        b.stock_count,
        b.image_path,
        COALESCE(
        json_agg(
                json_build_object(
                    'genre_id', g.genre_id,
                    'name', g.name
                )
                ORDER BY g.name
            ) FILTER (WHERE g.name IS NOT NULL),
            '[]'::json
        ) AS genres
    FROM bookstore.books AS b
    INNER JOIN bookstore.authors AS a
        ON b.author_id = a.author_id
    LEFT JOIN bookstore.book_genres AS bg
        ON b.book_id = bg.book_id
    LEFT JOIN bookstore.genres AS g
        ON bg.genre_id = g.genre_id
    WHERE b.title ILIKE '%' || :title || '%'
    AND a.name ILIKE '%' || :author || '%'
    GROUP BY
        b.book_id,
        b.title,
        a.name,
        b.book_description,
        b.price,
        b.stock_count,
        b.image_path
    ");

    $stmt->execute([
        'title' => $title,
        'author' => $author
    ]);
    $data = $stmt->fetchAll();
    echo json_encode($data);
});

$router->get('/books/{id}', function ($id) {
    $db = openDB();
    $pdo = $db->getPDO();

    $stmt = $pdo->prepare("
        SELECT
            b.book_id,
            b.title,
            a.name AS author,
            b.book_description,
            b.price,
            b.stock_count,
            b.image_path,
            COALESCE(
                json_agg(
                        json_build_object(
                            'genre_id', g.genre_id,
                            'name', g.name
                        )
                        ORDER BY g.name
                    ) FILTER (WHERE g.name IS NOT NULL),
                    '[]'::json
                ) AS genres
        FROM bookstore.books AS b
        INNER JOIN bookstore.authors AS a
            ON b.author_id = a.author_id
        LEFT JOIN bookstore.book_genres AS bg
            ON b.book_id = bg.book_id
        LEFT JOIN bookstore.genres AS g
            ON bg.genre_id = g.genre_id
        WHERE b.book_id = :book_id
        GROUP BY
            b.book_id,
            b.title,
            a.name,
            b.book_description,
            b.price,
            b.stock_count,
            b.image_path
    ");

    $stmt->execute(['book_id' => $id]);
    $result = $stmt->fetch();

    echo json_encode($result);
});

$router->get('/genres', function () {

    $queryString = $_SERVER['QUERY_STRING'];
    $parameters = [];
    parse_str($queryString, $parameters);

    $active = $parameters['active'] ?? false;

    $joinType = $active ? 'INNER' : 'LEFT';

    $sqlString = "
    SELECT
        g.genre_id,
        g.name,
        COUNT(bg.book_id) AS book_count
    FROM bookstore.genres AS g
    {$joinType} JOIN bookstore.book_genres AS bg
        ON g.genre_id = bg.genre_id
    GROUP BY
        g.genre_id,
        g.name;";

    $db = openDB();
    $pdo = $db->getPDO();
    $stmt = $pdo->prepare($sqlString);

    $stmt->execute();
    $data = $stmt->fetchAll();
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
    function () {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = $data['email'];
        $bookId = $data['bookId'];
        $quantity = $data['quantity'] ?? 1;

        $db = openDB();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare('
            SELECT user_id FROM bookstore.users
            WHERE email = :email
        ');

        $succeeded = $stmt->execute(['email' => $email]);
        if (!$succeeded) {
            return json_encode(['success' => false]);
        }

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
        if (!$succeeded) {
            return json_encode(['success' => false]);
        }

        $cartId = $stmt->fetch(PDO::FETCH_ASSOC)['cart_id'] ?? null;

        $stmt = $pdo->prepare('
        INSERT INTO bookstore.cart_items(cart_id, book_id, quantity)
        VALUES(:cart_id, :book_id, :quantity)
        ');
        $succeeded = $stmt->execute([
            ':cart_id' => $cartId,
            ':book_id' => $bookId,
            ':quantity' => $quantity
        ]);

        return json_encode(['success' => $succeeded]);
    }
);

$router->get(
    '/cart',
    function () {
        $db = openDB();

        $email = $_GET['email'] ?? null;

        /* $stmt = $pdo->prepare('
            SELECT cart_item_id, title, a.name AS author, price, b.image_path, quantity
            FROM bookstore.books AS b
            INNER JOIN bookstore.cart_items AS ci
                ON ci.book_id = b.book_id
            INNER JOIN bookstore.carts AS c
                ON c.cart_id = ci.cart_id
            INNER JOIN bookstore.users AS u
                ON u.user_id = c.user_id
            INNER JOIN bookstore.authors AS a
                ON a.author_id = b.author_id
            WHERE u.email = :email
        ');

        $json = [];
        $success =         $stmt->execute([':email' => $email]);
        if ($success) {
            $json['success'] = $success;
            $json['cart'] = $stmt->fetchAll();
        } 
        echo json_encode($json);*/

        $query = QueryBuilder::table('bookstore.books', 'b')
            ->select(
                [
                    'cart_item_id',
                    'title',
                    'a.name AS author',
                    'price',
                    'b.image_path',
                    'quantity'
                ]
            )
            ->innerJoin('bookstore.cart_items', 'ci.book_id', 'b.book_id', 'ci')
            ->innerJoin('bookstore.carts', 'c.cart_id', 'ci.cart_id', 'c')
            ->innerJoin('bookstore.users', 'u.user_id', 'c.user_id', 'u')
            ->innerJoin('bookstore.authors', 'a.author_id', 'b.author_id', 'a');

        if ($email !== null && $email != '') {
            $query->where([
                'email' => $email
            ]);
        }

        $data = $db->executeAndReturnAll($query);
        $json = [];
        $json['success'] = true;
        $json['cart'] = $data;

        echo json_encode($json);
    }
);

$router->delete(
    '/cart',
    function () {
        $db = openDB();
        $pdo = $db->getPDO();

        $id = $_GET['id'];

        $stmt = $pdo->prepare('DELETE FROM bookstore.cart_items WHERE cart_item_id = :cart_item_id');

        $json = [];
        $json['success'] = $stmt->execute([':cart_item_id' => $id]);

        echo json_encode($json);
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
