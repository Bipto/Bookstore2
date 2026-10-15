document.addEventListener('DOMContentLoaded', function() {
  const profile = document.querySelector('.profile');
  const profileCircle = document.querySelector('#profile-circle');
  const profileDropdown = document.querySelector('#profile-dropdown');

  const cart = document.querySelector('.cart');
  const cartCircle = document.querySelector('#cart-circle');
  const cartDropdown = document.querySelector('#cart-dropdown');

  const cartContent = document.querySelector('#cart-content');
  const cartSpinner = document.querySelector('#cart-spinner');

  const hamburger = document.querySelector('#hamburger');
  const navLinks = document.querySelector('#links');

  // Profile
  profileCircle.addEventListener('click', function(event) {
    event.stopPropagation();

    profile.classList.toggle('open');
    profileDropdown.classList.toggle('show');

    cart.classList.remove('open');
    cartDropdown.classList.remove('show');
  });

  // Cart
  cartCircle.addEventListener('click', async function(event) {
    event.stopPropagation();

    const isOpen = cart.classList.toggle('open');
    cartDropdown.classList.toggle('show', isOpen);

    profile.classList.remove('open');
    profileDropdown.classList.remove('show');

    if (isOpen) {
      cartContent.style.display = 'none';
      cartSpinner.style.display = 'block';

      try {
        const response = await fetch('/ajax/get_cart.php');

        if (!response.ok) {
          throw new Error('Request failed');
        }

        const html = await response.text();

        cartContent.innerHTML = html;
      } catch (error) {
        cartContent.innerHTML = '<p>Something went wrong.</p>';
      } finally {
        cartContent.style.display = 'block';
        cartSpinner.style.display = 'none';
      }
    }
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(event) {
    if (!event.target.closest('.profile, #profile-dropdown')) {
      profile.classList.remove('open');
      profileDropdown.classList.remove('show');
    }

    if (!event.target.closest('.cart, #cart-dropdown')) {
      cart.classList.remove('open');
      cartDropdown.classList.remove('show');
    }
  });

  // Hamburger
  hamburger.addEventListener('click', function() {
    const isOpen = hamburger.classList.toggle('open');

    navLinks.classList.toggle('show', isOpen);

    hamburger.setAttribute('aria-expanded', isOpen);
    hamburger.setAttribute(
        'aria-label',
        isOpen ? 'Close navigation menu' : 'Open navigation menu');
  });
});