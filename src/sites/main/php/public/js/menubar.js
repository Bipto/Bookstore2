$(function() {
  const $profile = $('.profile');
  const $profileCircle = $('#profile-circle');
  const $profileDropdown = $('#profile-dropdown');

  const $cart = $('.cart');
  const $cartCircle = $('#cart-circle');
  const $cartDropdown = $('#cart-dropdown');

  const $hamburger = $('#hamburger');
  const $navLinks = $('#links');

  // Profile
  $profileCircle.on('click', function(event) {
    event.stopPropagation();

    $profile.toggleClass('open');
    $profileDropdown.toggleClass('show');

    $cart.removeClass('open');
    $cartDropdown.removeClass('show');
  });

  // Cart
  $cartCircle.on('click', function(event) {
    event.stopPropagation();

    $cart.toggleClass('open');
    $cartDropdown.toggleClass('show');

    $profile.removeClass('open');
    $profileDropdown.removeClass('show');

    $.ajax({
      url: '/ajax/get_cart.php',
      type: 'GET',
      success: function(html) {
        $('#cart-dropdown').html(html);
      },
      error: function() {
        $('#cart-dropdown').html('<p>Something went wrong.</p>');
      }
    });
  });

  // Close profile/cart when clicking outside
  $(document).on('click', function(event) {
    if (!$(event.target).closest('.profile').length) {
      $profile.removeClass('open');
      $profileDropdown.removeClass('show');
    }

    if (!$(event.target).closest('.cart').length) {
      $cart.removeClass('open');
      $cartDropdown.removeClass('show');
    }
  });

  // Hamburger
  $hamburger.on('click', function() {
    const isOpen = $(this).toggleClass('open').hasClass('open');

    $navLinks.toggleClass('show', isOpen);

    $(this)
        .attr('aria-expanded', isOpen)
        .attr(
            'aria-label',
            isOpen ? 'Close navigation menu' : 'Open navigation menu');
  });
});
