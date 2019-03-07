<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
unset($_SESSION['post_cost_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user'])) {
        $id = (int) htmlspecialchars($_POST['id']);
        $lot = get_lot_by_id($link, $id);
        $errors = [];
        $history_data = select_data_by_lot_id ($link, HISTORY_DATA, $id);
        $rates_data = select_data_by_lot_id ($link, RATES_DATA, $id);
        if ($rates_data) {
            $starting_price = $rates_data[0]['starting_price'];
            $amount = ($rates_data[0]['rate_amount'] <= 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
            $min_rate = $rates_data[0]['rate_step'] + $amount;
        } else {
            $rates_data = select_data_by_lot_id ($link, STARTING_PRICE, $id);
            $min_rate = $rates_data[0]['starting_price'] + $rates_data[0]['rate_step'];
        }
        $end_time = strtotime($rates_data[0]['finishing_date']);
        $now = time();
        if($end_time <= $now) {
            $errors['time'] = 'Время делать ставки на этот лот закончилось';
        }

        $session_user_id = (int) $_SESSION['user']['id'];
        $post_cost = (int) trim($_POST['cost']);
        $data = [$post_cost, $session_user_id, $id];

        if (!isset($post_cost) && empty($post_cost)) {
            $_POST['post_cost_error'] = 'Это поле необходимо заполнить';
        } elseif ($post_cost <= 0 || !ctype_digit($post_cost)) {
            $_POST['post_cost_error'] = 'Значение должно положительным и целым числом';
        } elseif (($post_cost) < $min_rate) {
            $_POST['post_cost_error'] = 'Значение ставки должно быть не меньше минимальной';
        } elseif (empty($errors['cost'])) {
            $data = [(int) $_POST['cost'], (int) $_SESSION['user']['id'], (int) $id];
            add_new_rate_to_db($link, ADD_NEW_RATE, $data);
        }
    }
}
$lot_id = (int) htmlspecialchars($_GET['id']) ?? $id;
$lot = get_lot_by_id($link, $lot_id);

/*if ($ _SERVER['REQUEST_METHOD'] === 'GET' &&  (isset($lot_id) && isset($lot))) */
if ($lot){
    $rate_limit = false;  // флаг ограничения на добавления ставки на лот
    $history_data = select_data_by_lot_id ($link, HISTORY_DATA, $lot_id);
    $rates_data = select_data_by_lot_id ($link, RATES_DATA, $lot_id);

    if (isset($_SESSION['user'])) {
        if ($rates_data) {
            $amount = ($rates_data[0]['rate_amount'] <= 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
            $min_rate = $rates_data[0]['rate_step'] + $amount;
        } else {
            $rates_data = select_data_by_lot_id ($link, STARTING_PRICE, $lot_id);
            $min_rate = ((int) $rates_data[0]['starting_price']) + ((int) $rates_data[0]['rate_step']);
        }
    $time_to_end_lot = get_end_of_time_lot($rates_data[0]['finishing_date']);
    $end_time = strtotime($rates_data[0]['finishing_date']);
    if (isset($rates_data[0]['lots_user_id']) && isset($rates_data[0]['rates_user_id'])) {
        if ($rates_data[0]['rates_user_id'] !== $_SESSION['user']['id']) { //TODO $history_data надо проверять все ставки из истории ставок!!!
                $rate_limit = true;
            } elseif ($rates_data[0]['lots_user_id'] !== $_SESSION['user']['id']) { // не проверяет ! ToDO
                $rate_limit = true;
            } elseif ($end_time <= time() ) {
                $rate_limit = true;
            }
    }
    foreach ($history_data as $value) {
        if ($value['user_id'] === (int) $_SESSION['user']['id']) {
            $rate_limit = false;
        }
    }
    if (isset($_POST['post_cost_error'])) {
        $errors['cost'] = $_POST['post_cost_error'];
        include_template ('lot', 'Лот', $categories, $user_avatar, ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'rates_data' => &$rates_data,
            'history_data' => &$history_data,
            'min_rate' => &$min_rate,
            'time_to_end_lot' => &$time_to_end_lot,
            'rate_limit' => &$rate_limit,
            'errors' => &$errors], $lot_id);
        exit();
    }

    $tmpl_data = ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'rates_data' => &$rates_data,
            'history_data' => &$history_data,
            'min_rate' => &$min_rate,
            'time_to_end_lot' => &$time_to_end_lot,
            'rate_limit' => &$rate_limit,
            'errors' => &$errors
            ];
        include_template ('lot', 'Лот', $categories, $user_avatar, $tmpl_data , $lot_id);
        exit();
    }

    include_template ('lot', 'Лот', $categories, $user_avatar,
        ['categories' => $categories,
        'lot' => $lot,
        'lot_id' => $lot_id,
        'history_data' => $history_data
], $lot_id);
    } else {
        http_response_code(404);
        include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404);
}
http_response_code(404);
include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404);
