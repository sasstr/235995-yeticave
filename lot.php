<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$lot_id = (int) $_GET['id'];
var_dump($lot_id);
$lot = get_lot_by_id($link, $lot_id);
 if(isset($lot_id) && isset($lot)){
    if (isset($_SESSION['user'])) {
        var_dump(isset($_SESSION['user']));
        $rates_data = select_data_by_lot_id ($link, RATES_DATA, $lot_id);
        var_dump($rates_data);
        $starting_price = $rates_data[0]['starting_price'];
        var_dump($starting_price);
        $amount = ($rates_data[0]['rate_amount'] === 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
        var_dump($amount);
        $min_rate = $rates_data[0]['rate_step'] + $amount;
        var_dump($min_rate);
        $r_d = select_data_by_lot_id ($link, HISTORY_DATA, $lot_id);
        $history_data = select_data_by_lot_id ($link, HISTORY_DATA, $lot_id);
        var_dump($history_data[0]);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = [];
            $session_user = $_SESSION['user'];
            $post_cost = POST['cost'];
            if (empty($post_cost)) {
                $errors['cost'] = 'Это поле необходимо заполнить';
            }elseif ($post_cost <= 0 || is_int($errors['cost'])) {
                $errors['cost'] = 'Значение должно положительным целым числом';
            }elseif (is_int(isset($_POST['cost'])) > 0
            && ($post_cost) >= $min_rate
            && empty($errors['cost'])) {
            $data = [$post_cost, $session_user['id'], $lot_id];
            add_new_rate_to_db($link, ADD_NEW_RATE, $data, $lot_id);

            include_template ('lot', 'Лот', $categories, $user_avatar,  [
                'categories' => $categories,
                'lot' => $lot,
                'rates_data' => $rates_data,
                'history_data' => &$history_data,
                'min_rate' => &$min_rate,
                'lot_id' => $lot_id
                ]);
        header('Location: lot.php?id=' . $_POST['lot_id']);
        exit();

            /* add_new_rate($_SESSION['user'], $link, $lot_id, $_POST['cost'], RATES_DATA, ADD_NEW_RATE); */
        }
    }
    include_template ('lot', 'Лот', $categories, $user_avatar,
            ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'history_data' => &$history_data,
            'min_rate' => &$min_rate
            ]);
    } else {
        include_template ('lot', 'Лот', $categories, $user_avatar,
            ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id
            ]);
    }
}else {
    http_response_code(404);
    include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404);
}
/* header('Location: lot.php?id=' . $_POST['lot_id']); */
include_template ('lot', 'Лот', $categories, $user_avatar,
['categories' => $categories,
'lot' => $lot,
'lot_id' => $lot_id
]);
