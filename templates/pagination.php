<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if($link) {
    $cur_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page_items = 9;
// учитывать лоты по каетегориям на стр лоты категорий
    // $result = mysqli_query($link, "SELECT COUNT(*) as cnt FROM lots;");
// учитывать лоты по каетегориям на стр лоты категорий

    if(isset($_GET['id']) && int ($_GET['id'])) {
        $sql = "SELECT COUNT(*) as cnt
                FROM lots
                JOIN categories
                ON lots.category_id = categories.id
                WHERE lots.finishing_date > NOW()
                AND lots.winner_id IS NULL
                AND lots.category_id = ?;";

        $result_category = db_select ($link, $sql, [$_GET['id']]);
    }

    // $result_category = mysqli_query($link, $sql);
// учитывать лоты по поиску на стр лоты поиска
    $result_search = mysqli_query($link, "SELECT COUNT(*) as cnt
    FROM lots
    WHERE lots.finishing_date > NOW()
    AND lots.winner_id IS NULL;");
// для главной исключить завершенные лоты
    /* $result_index = mysqli_query($link, "SELECT COUNT(*) as cnt
                                    FROM lots
                                    WHERE lots.finishing_date > NOW()
                                    AND lots.winner_id IS NULL;"); */

    $items_count = mysqli_fetch_assoc($result)['cnt'];

    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);

    $lots_pagination = get_lots_pagination($link, $page_items, $offset);
}
