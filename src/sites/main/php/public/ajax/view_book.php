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
            <button class="add-to-cart">Add to cart</button>
        </div>
    ';

    $html .= '<script>
    
    const addToCartButton = $(".add-to-cart");
    addToCartButton.on("click", function(event){
        //alert("Adding to cart");

        $.ajax({
            url: "/ajax/add_book_to_cart.php?book_id=' . $id . '",
            type: "GET",
            success: function (html) {
                $(".page").html(html);
            },
            error: function () {
                $(".page").html("<p>Something went wrong.</p>");
            }
        });
    });

    </script>';

    echo $html;
} else {
    echo '<h3>Could not find book!</h3>';
}
