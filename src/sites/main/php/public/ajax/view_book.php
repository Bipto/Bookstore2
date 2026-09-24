<?php

require_once '/var/www/shared/api.php';

$id = $_GET['id'] ?? 1;

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
