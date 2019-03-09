<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');
unset($_SESSION['post_cost_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user'])) {
        $id = (int) $_POST['id'];
        $lot = get_lot_by_id($link, $id);
        $errors = [];
        $history_data = select_history_data_by_id ($link, $id);
        $rates_data = select_rates_data_by_id ($link, $id);
        if ($rates_data) {
            $starting_price = $rates_data[0]['starting_price'];
            $amount = ($rates_data[0]['rate_amount'] <= 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
            $min_rate = $rates_data[0]['rate_step'] + $amount;
        } else {
            $rates_data = select_starting_price_data_by_id ($link, $id);
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
            add_new_rate_to_db($link, $data);
        }
    }
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
        } else {

            $time_to_end_lot = get_end_of_time_lot($starting_price[0]['finishing_date']);
            $end_time = $starting_price[0]['finishing_date'];
            $min_rate = ((int) $starting_price[0]['starting_price']) + ((int) $starting_price[0]['rate_step']);
        }
    if (isset($rates_data[0]['lots_user_id']) && isset($rates_data[0]['rates_user_id'])) {
        // если пользователь уже сделал ставку не показывать блок добавления ставки
        if ($rates_data[0]['rates_user_id'] !== $_SESSION['user']['id']) {
                $rate_limit = true;
        // если пользователь создал этот лот не показывать блок добавления ставки
            } elseif ($rates_data[0]['lots_user_id'] !== $_SESSION['user']['id']) {
                $rate_limit = true;
        // если время вышло делать ставки по этому лоту
            } elseif ($end_time <= time() ) {
                $rate_limit = true;
            }
    }
    // если пользователь уже сделал ставку не показывать блок добавления ставки
    foreach ($history_data as $value) {
        if ($value['user_id'] === (int) $_SESSION['user']['id']) {
            $rate_limit = false;
        }
    }
    if (isset($_POST['post_cost_error'])) {
        $errors['cost'] = $_POST['post_cost_error'];
        include_template ('lot', 'Лот', $categories, $user_avatar, [
            'categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'rates_data' => $rates_data,
            'history_data' => $history_data,
            'min_rate' => $min_rate,
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
            'history_data' => $history_data,
            'min_rate' => $min_rate,
            'time_to_end_lot' => $time_to_end_lot,
            'rate_limit' => $rate_limit,
            'page_categories' => $page_categories,
            'errors' => $errors
            ];
        include_template ('lot', 'Лот', $categories, $user_avatar, $tmpl_data , $page_categories);
        exit();
    }

    include_template ('lot', 'Лот', $categories, $user_avatar,
        ['categories' => $categories,
        'page_categories' => $page_categories,
        'lot' => $lot,
        'lot_id' => $lot_id,
        'history_data' => $history_data
], $page_categories);
    } else {
        http_response_code(404);
        include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404, $page_categories);
}
http_response_code(404);
include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404, $page_categories);
