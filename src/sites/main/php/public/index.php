<!DOCTYPE html>
<html>

<head>
    <title>Main Site</title>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body>

    <h1>Main localhost website</h1>

    <p>

        <?php


        require '/var/www/shared/common.php';
        echo 'You are visiting: ' . siteName();

        ?>

    </p>

    <p>
        PHP version:
        <?= PHP_VERSION ?>
    </p>

</body>

</html>