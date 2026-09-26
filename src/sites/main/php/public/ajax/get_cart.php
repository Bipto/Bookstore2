<?php

require_once '/var/www/shared/api.php';

session_start();

urlencode($email = $_SESSION['email'] ?? null);

$url = "/cart?email={$email}";

$response = json_decode(API::get($url), TRUE);

if ($response['success'] == true) {
    $data = json_decode($response['data'], TRUE) ?? [];
    $cartData = $data['cart'] ?? [];

    if (count($cartData) == 0) {
        echo '<p>The cart is currently empty</p>';
    } else {
        $total = 0;

        foreach ($cartData as $item) {
            $quantity = $item['quantity'] ?? 0;
            $quantityDisplay = $quantity > 1 ? "- x{$quantity}" : "";
            $price = ($item['price'] ?? 0) * $quantity;
            $formattedPrice = number_format($price, 2);
            $priceDisplay = "(£{$formattedPrice})";
            echo "{$item['title']} - {$item['author']}{$quantityDisplay}{$priceDisplay}<hr>";

            $total += $price;
        }
        echo "Order total: £{$total}";
    }
} else {
    echo '<p>Failed to get cart</p>';
}
