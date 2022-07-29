<?php /** @var array $data -> Prepared parameters */ ?>
<!DOCTYPE html>
<html lang="<?= $pageParams['_lang'] ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $pageParams['_title'] ?></title>
    <meta name="theme-color" content="<?= $pageParams['_theme-color'] ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="<?= $pageParams['_amwasbs'] ?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="/dist/styles/main.css">
</head>
<body>
    <div class="container">
<?php include_once $part['content']; ?>
<?php include $part['footer']; ?>
    </div>
    <?php include $part['scripts']; ?>
</body>
</html>

