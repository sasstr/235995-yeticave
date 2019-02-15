<?php
$link = db_connect();
$time_until_midnight = show_time();
$is_auth = rand(0, 1);
$title = 'Главная';
$user_name = htmlspecialchars('Александр Страховенко');
$user_avatar = 'img/user.jpg';
$categories = get_categories($link);
$lots = get_lots($link);

$major_indexes = [
    'categories' => $categories,
    'lots' => $lots,
    'time_until_midnight' => $time_until_midnight
];

$lot_page = [
    'categories' => $categories,
];

$add_lot_page = [
    'categories' => $categories,
];
