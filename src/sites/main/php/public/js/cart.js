async function AddToCart(id) {
  try {
    const url = '/ajax/cart/add_book_to_cart.php?book_id=' + id;
    const response = await fetch(url, {method: 'GET'});

    if (!response.ok) {
      throw new Error('Request failed');
    }

    window.location.href = '/';
  } catch (error) {
    document.querySelector('.page').innerHTML = '<p>Something went wrong.</p>';
  }
}

function RemoveFromCart(id, event) {
  event.stopPropagation();

  fetch('/ajax/cart/remove_item_from_cart.php?id=' + id)
      .then(() => fetch('/ajax/cart/get_cart.php'))
      .then(response => response.text())
      .then(html => {
        document.getElementById('cart-content').innerHTML = html;

        const cartCount = document.getElementById('cart-count');
        const count = parseInt(cartCount.textContent, 10);
        const newCount = count - 1;

        cartCount.textContent = newCount;

        if (newCount === 0) {
          cartCount.style.display = 'none';
        }
      });
}