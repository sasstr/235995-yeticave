<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if (empty($_SESSION['user'])) {
    http_response_code(403);
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {

    $lots_user_id = $_GET['id'];
    $my_lots = get_my_lots($link, $lots_user_id);

    if(isset($my_lots)) {
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
}

$my_lots_data = [
    'page_categories' => $page_categories,
    'categories' => $categories
];
include_template ('my-lots', 'Мои лоты', $categories, $user_avatar, $my_lots_data, $page_categories);
