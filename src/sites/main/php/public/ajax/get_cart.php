<?php

require_once '/var/www/shared/api.php';

session_start();

$email = $_SESSION['email'] ?? '';
$email = urlencode($email);

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
            $cartItemId = $item['cart_item_id'] ?? -1;
            $quantity = $item['quantity'] ?? 0;
            $quantityDisplay = $quantity > 1 ? "- x{$quantity}" : "";
            $price = ($item['price'] ?? 0) * $quantity;
            $formattedPrice = number_format($price, 2);
            $priceDisplay = "(£{$formattedPrice})";
            echo "
            <div id='cart-id-{$cartItemId}'>
            {$item['title']} - {$item['author']}{$quantityDisplay}{$priceDisplay}
            <br>
            <button onclick='RemoveFromCart({$cartItemId}, event);'>Remove</button>
            <hr>
            </div>";

            $total += $price;
        }
        echo "Order total: £{$total}";
        echo "<br>";
        echo "<button type='button' onclick=\"window.location.href='/checkout'\">Checkout</button>";
        echo "<script>
        function RemoveFromCart(id, event)
        {
        event.stopPropagation();

        const element = document.getElementById('cart-id-' + id);

        if (element)
        {
            element.remove();
        }
        }
        </script>";
    }
} else {
    echo '<p>Failed to get cart</p>';
}
