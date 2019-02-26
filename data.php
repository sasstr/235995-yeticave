<?php
$link = db_connect();
$time_until_midnight = show_time();
$title = 'Главная';
$user_avatar = 'img/user.jpg';
$categories = get_categories($link);
$lots = get_lots($link);

$major_indexes = [
    'categories' => $categories,
    'lots' => $lots,
    'time_until_midnight' => $time_until_midnight
];

$add_lot_page = [
    'categories' => $categories,
];

$p_404 = [
    'categories' => $categories,
];

$all_lots_page = [
    'categories' => $categories,
];

$login_page = [
    'categories' => $categories,
];

$login_page = [
    'categories' => $categories,
];

$my_lots_page = [
    'categories' => $categories,
];

$search_page = [
    'categories' => $categories,
];

$sign_up_page = [
    'categories' => $categories,
];
