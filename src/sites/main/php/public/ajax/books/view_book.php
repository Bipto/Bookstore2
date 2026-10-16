<?php

require_once '/var/www/shared/api.php';

$id = $_GET['id'] ?? 1;

$response = API::get("/books/{$id}");
$results = json_decode($response, true);

if ($results['success']) {
    $data = json_decode($results['data'], true);

    $imagePath = "/img/{$data['image_path']}";
    $price = ($data['price'] ?? 0);
    $formattedPrice = number_format($price, 2);

    $html = "
        <div class='view-book'>
            <h1>{$data['title']}</h1>
            <h2>Author:{$data['author']}</h2>
            <img src='{$imagePath}' class='view-book-image' loading='lazy'>
            <h4>£{$formattedPrice}</h4>
            <p>{$data['book_description']}</p>
            <button class='add-to-cart' onclick='AddToCart({$id})'>Add to cart</button>
        </div>";

    echo $html;
} else {
    echo '<h3>Could not find book!</h3>';
}
