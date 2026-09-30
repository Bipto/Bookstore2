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

            $cartItemId = (int) ($item['cart_item_id'] ?? -1);
            $quantity = (int) ($item['quantity'] ?? 0);

            $price = ($item['price'] ?? 0) * $quantity;
            $formattedPrice = number_format($price, 2);

            $title = htmlspecialchars(
                $item['title'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            );

            $author = htmlspecialchars(
                $item['author'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            );

            echo "
                <div class='cart-item'>
                    <div class='cart-item-info'>
                        <div class='cart-item-title'>
                            {$title}
                        </div>

                        <div class='cart-item-author'>
                            {$author}
                        </div>";

            if ($quantity > 1) {
                echo "
                    <div class='cart-item-quantity'>
                        Quantity: {$quantity}
                    </div>";
            }

            echo "
                    <button
                        class='cart-remove'
                        type='button'
                        onclick='RemoveFromCart({$cartItemId}, event);'
                    >
                        Remove
                    </button>
                </div>

                <div class='cart-item-price'>
                    £{$formattedPrice}
                </div>

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

        fetch('/ajax/remove_item_from_cart.php?id=' + id)
            .then(() => {
                return fetch('/ajax/get_cart.php');
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('cart-content').innerHTML = html;
            });


        }
        </script>";
    }
} else {
    echo '<p>Failed to get cart</p>';
}
