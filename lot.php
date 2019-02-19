<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');


   $lot = get_lot_by_id($link, $_GET['id']);
    var_dump($lot);

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



/*$lot_id = (int) $_GET['id'];
var_dump($lot_id);
$lot = get_lot_by_id($link, $lot_id);
var_dump($lot);
 if(isset($lot_id)){ */

   /*  $lot_page = render('lot', [
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
        ]); */
    /* } else {
        http_response_code(404);
    } */

