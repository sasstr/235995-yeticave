<?php

$link = db_connect();
$time_until_midnight = show_time();
$categories = get_categories($link);
$lots = get_lots($link);
$page_categories = render ('menu_categories', ['categories' => $categories]);
$user_avatar = MOCK_IMG;
define ('PAGE_CATEGORIES', render ('menu_categories', ['categories' => $categories]));

if (isset($_SESSION['user'])){
    $user_avatar = $_SESSION['user']['avatar'];
    };
