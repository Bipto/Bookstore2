<?php

require_once '/var/www/shared/api.php';

$email = $_POST['email'] ?? null;
$firstName = $_POST['first-name'] ?? null;
$lastName = $_POST['last-name'] ?? null;
$password = $_POST['password'] ?? null;

echo $email;
echo '<br>';
echo $firstName;
echo '<br>';
echo $lastName;
echo '<br>';
echo $password;

if (!is_null($email) && !is_null($firstName) && !is_null($lastName) && !is_null($password)) {

    $data = [
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'password' => $password
    ];

    API::post('/users', $data);
}
