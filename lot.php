<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

unset($_SESSION['post_cost_error']);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $id = (int) $_POST['id'];
    $lot = get_lot_by_id($link, $id);
    $errors = [];
    $history_data = select_history_data_by_id ($link, $id);
    $rates_data = select_rates_data_by_id ($link, $id);
    if ($rates_data) {
        $starting_price = $rates_data[0]['starting_price'];
        $amount = ($rates_data[0]['rate_amount'] <= 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
        $min_rate = $rates_data[0]['rate_step'] + $amount;
        $diff_time = $rates_data[0]['finishing_date'];
    } else {
        $rates_data = select_starting_price_data_by_id ($link, $id);
        $min_rate = $rates_data[0]['starting_price'] + $rates_data[0]['rate_step'];
        $diff_time = $rates_data[0]['finishing_date'];
    }
    $end_time = strtotime($rates_data[0]['finishing_date']);
    $now = time();
    if($end_time <= $now) {
        $errors['time'] = 'Время делать ставки на этот лот закончилось';
    }

    $session_user_id = (int) $_SESSION['user']['0']['id'];
    $post_cost = (int) trim($_POST['cost']);
    $data = [$post_cost, $session_user_id, $id];

    $errors['cost'] = validate_rate_cost ($post_cost, $min_rate, $data, $link);
}
$lot_id = (int) $_GET['id'] ?? $id;
$lot = get_lot_by_id($link, $lot_id);

if ($lot){
    $rate_limit = false;  // флаг ограничения на добавления ставки на лот
    $history_data = select_history_data_by_id ($link, $lot_id);
    $rates_data = select_rates_data_by_id ($link, $lot_id);
    $starting_price = select_starting_price_data_by_id ($link, $lot_id);

    if (isset($_SESSION['user'])) {
        if ($rates_data) {
            $time_to_end_lot = get_end_of_time_lot($rates_data[0]['finishing_date']);
            $end_time = $rates_data[0]['finishing_date'];
            $amount = ($rates_data[0]['rate_amount'] <= 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
            $min_rate = $rates_data[0]['rate_step'] + $amount;
            $diff_time = $end_time;
        } else {
            $time_to_end_lot = get_end_of_time_lot($starting_price[0]['finishing_date']);
            $end_time = $starting_price[0]['finishing_date'];
            $amount = ((int) $starting_price[0]['starting_price']);
            $min_rate = ((int) $starting_price[0]['starting_price']) + ((int) $starting_price[0]['rate_step']);
            $diff_time = $end_time;
        }
        if(count($rates_data) > 0 && count($history_data) > 0) {
            $rate_limit = is_show_rate_form($rates_data[0]['lots_user_id'], $rates_data[0]['rates_user_id'], $history_data, $_SESSION['user']['0']['id'], $end_time);
        } else {
            $rate_limit = true;
        }

    if (isset($errors['cost'])) {
        include_template ('lot', 'Лот', $categories, $user_avatar, [
            'categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'rates_data' => $rates_data,
            'diff_time' => &$diff_time,
            'amount' => &$amount,
            'history_data' => $history_data,
            'min_rate' => &$min_rate,
            'time_to_end_lot' => $time_to_end_lot,
            'rate_limit' => $rate_limit,
            'page_categories' => $page_categories,
            'errors' => $errors], $page_categories);
        exit();
    }

    $tmpl_data = ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'rates_data' => $rates_data,
            'diff_time' => &$diff_time,
            'amount' => &$amount,
            'history_data' => $history_data,
            'min_rate' => &$min_rate,
            'time_to_end_lot' => $time_to_end_lot,
            'rate_limit' => $rate_limit,
            'page_categories' => $page_categories,
            'errors' => &$errors
            ];
        include_template ('lot', 'Лот', $categories, $user_avatar, $tmpl_data , $page_categories);
        exit();
    }
    include_template ('lot', 'Лот', $categories, $user_avatar,
        ['categories' => $categories,
        'page_categories' => $page_categories,
        'amount' => &$amount,
        'diff_time' => &$diff_time,
        'lot' => $lot,
        'lot_id' => $lot_id,
        'history_data' => $history_data
        ], $page_categories);
} else {
    http_response_code(404);
    include_template ('404', '404 страница не найдена', $categories, $user_avatar, ['categories' => $categories,
    'page_categories' => $page_categories
    ], $page_categories);
}
