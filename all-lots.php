<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($_GET['id'] && $_GET['name']) {
    $categ_id = (int) $_GET['id'];
    $page_title = $_GET['name'];
    $category_lots = get_lots_by_category_id($link, $categ_id);

    include_template ('all-lots', "Все лоты в категории: $page_title", $categories, $user_avatar,[
        'categories' => $categories,
        'page_name' => $page_title,
        'category_lots' => $category_lots,
        'page_categories' => $page_categories
        ], $page_categories);
} else {
    include_template ('all-lots', 'Все лоты', $categories, $user_avatar,
        ['categories' => $categories,
        'page_categories' => $page_categories
        ], $page_categories);
}


