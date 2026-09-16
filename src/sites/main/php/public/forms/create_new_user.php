<?php

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

    $db = new RelationalDatabase(
        $_ENV['POSTGRES_DRIVER'],
        $_ENV['POSTGRES_HOST'],
        $_ENV['POSTGRES_PORT'],
        $_ENV['POSTGRES_DB'],
        $_ENV['POSTGRES_USER'],
        $_ENV['POSTGRES_PASSWORD'],
        $_ENV['POSTGRES_CHARSET']
    );

    /* $queryBuilder = QueryBuilder::table('bookstore.books')->insert([
            'first-name' => $firstName
        ])->where(''); */
}
