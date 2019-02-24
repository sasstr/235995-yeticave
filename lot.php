<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$lot_id = (int) $_GET['id'];
$lot = get_lot_by_id($link, $lot_id);

 if(isset($lot_id) && isset($lot)){
     $lot_page = render('lot', [
        'categories' => $categories,
        'lot' => $lot
        ]);
        print render('layout', [
            'content' => $lot_page,
            'title' => 'Лот',
            'categories' => $categories,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar
        ]);
    } else {
        http_response_code(404);
        $page_404 = render('404', $p_404);
        print render('layout', [
            'content' => $page_404,
            'title' => '404 страница не найдена',
            'categories' => $categories,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar
        ]);
    }

