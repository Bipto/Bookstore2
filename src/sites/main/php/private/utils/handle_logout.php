<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

unset($_SESSION['email']);
echo '<script>window.location.href="/"</script>';
