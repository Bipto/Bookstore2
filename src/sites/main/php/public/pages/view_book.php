<?php

require_once '/var/www/shared/db.php';

/** @var array{id: string, title: string, author: string} $params */


$id = $params[0];

$db = new RelationalDatabase(
    $_ENV['POSTGRES_DRIVER'],
    $_ENV['POSTGRES_HOST'],
    $_ENV['POSTGRES_PORT'],
    $_ENV['POSTGRES_DB'],
    $_ENV['POSTGRES_USER'],
    $_ENV['POSTGRES_PASSWORD'],
    $_ENV['POSTGRES_CHARSET']
);

$queryBuilder = QueryBuilder::table('bookstore.books')
    ->select()
    ->where('book_id = ' . $id);

$result = $db->executeAndReturnOne($queryBuilder);

if ($result) {
    $imagePath = '/img/' . $result['image_path'];

    $html = '
<div class="view-book">
        <h1>' . $result['title'] . '</h1>
        <h2>Author: ' . $result['author'] . ' </h2>
        <img src=' . $imagePath . ' class="view-book-image" loading="lazy">
        <p>' . $result['book_description'] . '</p>
    </div>
';

    echo $html;
} else {
    echo '<h3>Could not find book!</h3>';
}
