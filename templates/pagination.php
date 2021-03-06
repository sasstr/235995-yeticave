<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if($link) {
    $cur_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page_items = 9;

    $result = mysqli_query($link, "SELECT COUNT(*) as cnt FROM lots;");
    $items_count = mysqli_fetch_assoc($result)['cnt'];

    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);

    $lots_pagination = get_lots_pagination($link, $page_items, $offset);
}
