<?php

require_once '/var/www/shared/api.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

$data = [
    'email' => $email,
    'password' => $password
];

$response = json_decode(API::post('/auth/login', $data), TRUE);
$data = json_decode($response['data'], TRUE);
if ($data['success'] === true) {
    $_SESSION['email'] = $email;
    echo '<script>window.location.href="/"</script>';
    exit;
} else {
    echo '<script>alert("Login failed");</script>';
    echo '<script>window.location.href="/login"</script>';
    exit;
}
