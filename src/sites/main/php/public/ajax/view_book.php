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
            <button class='add-to-cart'>Add to cart</button>
        </div>";

    $html .= '<script>
    
        const addToCartButton = document.querySelector(".add-to-cart");

        addToCartButton.addEventListener("click", async function (event) {
            try {
                const response = await fetch(
                    "/ajax/add_book_to_cart.php?book_id=' . $id . '",
                    { method: "GET" }
                );

                if (!response.ok) {
                    throw new Error("Request failed");
                }

                window.location.href = "/";
            } catch (error) {
                document.querySelector(".page").innerHTML =
                    "<p>Something went wrong.</p>";
            }
        });

    </script>';

    echo $html;
} else {
    echo '<h3>Could not find book!</h3>';
}
