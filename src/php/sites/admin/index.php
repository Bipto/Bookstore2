<!DOCTYPE html>
<html>

<head>
    <title>Admin Site</title>
</head>

<body>

    <h1>Admin localhost website</h1>

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