<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if (empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['user']) && isset($_GET['id'])) {
    $my_lots = get_my_lots($link);

    $my_lots_data = [
        'page_categories' => $page_categories,
        'my_lots' => $my_lots,
        'categories' => $categories
    ];
    include_template ('my-lots', 'Мои лоты', $categories, $user_avatar, $my_lots_data, $page_categories);
}

$my_lots_data = [
    'page_categories' => $page_categories,
    'categories' => $categories
];
include_template ('my-lots', 'Мои лоты', $categories, $user_avatar, $my_lots_data, $page_categories);
/*
4.7. Мои ставки
Страница, где пользователь видит все свои ставки на различные лоты.

Каждая ставка имеет следующую информацию:

название лота (ссылка на страницу);
сумма ставки;
дата/время;

Выигравшие ставки должны выделяться отдельным цветом,
а также там должны быть размещены контакты владельца лота.

*/
