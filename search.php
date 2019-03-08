<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search_query = trim($_GET['search']);
    if (!empty($search_query)) {
        $sql = 'SELECT  `lots`.`id`,
                        `lots`.`img_path`,
                        `lots`.`title`,
                        `lots`.`starting_price`,
                        `lots`.`description`,
                        `categories`.`name`
                FROM `lots`
                JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
                JOIN users ON `lots`.`user_id` = `users`.`id`
                WHERE MATCH(`lots`.`title`, `lots`.`description` ) AGAINST(? IN BOOLEAN MODE)
                AND (`lots`.`winner_id` IS NULL)
                AND (`lots`.`finishing_date` > NOW())
                ORDER BY `lots`.`starting_date` DESC;';
    }
    $search_ft_to_db = [$_GET['search']];
    $res_search = db_select ($link, $sql, $search_ft_to_db);
    $id = $_GET['id'];
    $data = [$res_search, $search_ft_to_db];

    include_template('search', 'Страница поиска', $categories, $user_avatar, $data, $id);
    /* $search = render('search', $search_page);
    print render('layout', [
        'content' => $search,
        'title' => 'Страница поиска',
        'categories' => $categories,
        'user_avatar' => $user_avatar,
        'res_search' => &$res_search,
        'search_ft_to_db' => &$search_ft_to_db,
    ]); */
    exit();
}
$search = render('search', $search_page);
print render('layout', [
    'content' => $search,
    'title' => 'Страница поиска',
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
