<?php

require_once '/var/www/shared/api.php';

/** @var array{id: string, title: string, author: string} $params */

$id = $params[0];

$response = API::get("/books/{$id}");
$results = json_decode($response, true);

if ($results['success']) {
    $data = json_decode($results['data'], true);

    $imagePath = "/img/{$data['image_path']}";

    $html = '
        <div class="view-book">
            <h1>' . $data['title'] . '</h1>
            <h2>Author: ' . $data['author'] . ' </h2>
            <img src=' . $imagePath . ' class="view-book-image" loading="lazy">
            <p>' . $data['book_description'] . '</p>
        </div>
    ';

    echo $html;
} else {
    echo '<h3>Could not find book!</h3>';
}

/* $queryBuilder = QueryBuilder::table('bookstore.books')
    ->insert([
        'title' => 'The Very Hungry Caterpillar',
        'author' => 'Test',
        'book_description' => 'Test Description',
        'genre' => 'Children',
        'price' => 5.99,
        'stock_count' => 50,
        'image_path' => 'test'
    ]);

echo $queryBuilder->queryString();
echo '<br>';
echo json_encode($queryBuilder->getBindingParameters());
echo '<br>';

$db->executeAndReturnOne($queryBuilder); */