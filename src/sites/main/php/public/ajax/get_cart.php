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
            echo "{$item['title']} - {$item['author']} - x{$item['quantity']})<hr>";

            $total += $item['price'];
        }
        echo "Order total: £{$total}";
    }
} else {
    echo '<p>Failed to get cart</p>';
}
