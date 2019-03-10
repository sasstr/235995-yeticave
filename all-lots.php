<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($_GET['id'] && $_GET['name']) {
    $categ_id = (int) $_GET['id'];
    $page_name = $_GET['name'];
    $category_lots = get_lots_by_category_id($link, $categ_id);

    include_template ('all-lots', 'Все лоты', $categories, $user_avatar,[
        'categories' => $categories,
        'category_lots' => $category_lots,
        'page_categories' => $page_categories
        ], $page_categories);
} else {
    include_template ('all-lots', 'Все лоты', $categories, $user_avatar,
        ['categories' => $categories,
        'page_categories' => $page_categories
        ], $page_categories);
}


