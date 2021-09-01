<!DOCTYPE html>
<html lang="ru">

<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- Primary Meta Tags -->
    <title><?= $title ?></title>
    <meta name="title" content="anchat">
    <meta name="description" content="анонимный чат с возможностью создавать комнаты">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="anchat">
    <meta property="og:description" content="анонимный чат с возможностью создавать комнаты">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="anchat">
    <meta property="twitter:description" content="анонимный чат с возможностью создавать комнаты">
    <script defer="defer" src="./js/main.js"></script>
    <link href="./css/main.css?v=<?= time() ?>" rel="stylesheet">
</head>

<body>
    <header>
        <a class="header__main-link" href="./index.php">anchat</a>
        <form class="header__exit-form" action="php/validation/exit.php" method="POST">
            <button class="exit-form__btn">Выйти</button>
        </form>
    </header>