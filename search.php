<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$search_page = [
    'categories' => $categories,
    'page_categories' => $page_categories
];

if (!isset($_GET['search']) || empty(trim($_GET['search']))) {
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'http://' . $_SERVER['SERVER_NAME']) === 0) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    header("Location: /");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $res_search = search_ft_to_db($link, $_GET['search']);
    $search_ft_to_db = $_GET['search'] ?? '';
    $data = [   'res_search' => $res_search,
                'search_ft_to_db' => $search_ft_to_db,
                'categories' => $categories,
                'page_categories' => $page_categories,
            ];

    include_template('search', 'Страница поиска', $categories, $user_avatar, $data, $page_categories);
    exit();
}
include_template('search', 'Страница поиска', $categories, $user_avatar, $search_page, $page_categories);
