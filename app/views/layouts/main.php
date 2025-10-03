<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
<?php foreach ($styles as $style): ?>
    <link rel="stylesheet" href="<?= "/public/css/$style.css" ?>">
<?php endforeach; ?>
</head>
<body>
<header>
</header>
<main>
    <?php echo $content; // Here we display the page content ?>
</main>
<footer>
</footer>
</body>
</html>
<?php
