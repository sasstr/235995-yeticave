<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$all_lots_page = [
    'categories' => $categories,
];

$all_lots = render('all-lots', $all_lots_page);
print render('layout', [
    'content' => $all_lots,
    'title' => 'Все лоты',
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
