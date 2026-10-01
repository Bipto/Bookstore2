<?php

require_once '/var/www/shared/api.php';

function truncate(string $text, int $maxLength): string
{
    if (strlen($text) <= $maxLength) {
        return $text;
    }

    $text = substr($text, 0, $maxLength - 3);
    $text = substr($text, 0, strrpos($text, ' '));

    return $text . '...';
}


$id = $_GET['id'] ?? 1;

$response = API::get("/books/{$id}");
$results = json_decode($response, true);

$output = [
    'success' => false
];

if ($results['success']) {
    $data = json_decode($results['data'], true);

    $imagePath = "/img/{$data['image_path']}";
    $price = ($data['price'] ?? 0);
    $formattedPrice = number_format($price, 2);

    $description = $data['book_description'];
    $displayDescription = truncate($description, 450);

    $header = "
    <h2>{$data['title']} - {$data['author']}</h2>
    ";

    $content = "
        <div class='view-book'>
            <img src='{$imagePath}' class='view-book-image' loading='lazy'>
            <h4>£{$formattedPrice}</h4>
            <p>{$displayDescription}</p>
        </div>";

    $encodedId = urlencode($id);

    $footer = "
    <a href='/view_book/" . $encodedId . "'>
    <button>View More</button>
    </a>
    <button class='add-to-cart' onclick='addToCart(" . $id . ")'>Add to cart</button>";

    $output['header'] = $header;
    $output['content'] = $content;
    $output['footer'] = $footer;
} else {
    $output['error'] = '<h3>Could not find book!</h3>';
}

echo json_encode($output);
