<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user'])) {
        $id = (int) htmlspecialchars($_POST['id']);

        $errors = [];
        $history_data = select_data_by_lot_id ($link, HISTORY_DATA, $id);
        $rates_data = select_data_by_lot_id ($link, RATES_DATA, $id);
        if ($rates_data) {
            $starting_price = $rates_data[0]['starting_price'];
            $amount = ($rates_data[0]['rate_amount'] === 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
            $min_rate = $rates_data[0]['rate_step'] + $amount;
        } else {
            $rates_data = select_data_by_lot_id ($link, STARTING_PRICE, $id);
            $min_rate = $rates_data[0]['starting_price'] + $amount;
        }
        $end_time = strtotime($rates_data[0]['finishing_date']);
        $now = time();
        if(check_finished_lot($end_time)) {
            $errors['time'] = 'Время делать ставки закончилось';
        }

        $session_user_id = (int) $_SESSION['user']['id'];
        $post_cost = (int) trim($_POST['cost']);
        $data = [$post_cost, $session_user_id, $id];

        if (isset($post_cost) && empty($post_cost)) {
            $errors['cost'] = 'Это поле необходимо заполнить';
            header('Location: lot.php?id=' . $id);
            exit();
        } elseif ($post_cost <= 0 || !ctype_digit($post_cost)) {
            $errors['cost'] = 'Значение должно положительным и целым числом';
            header('Location: lot.php?id=' . $id);
            exit();
        } elseif (($post_cost) < $min_rate) {
            $errors['cost'] = 'Значение ставки должно быть не меньше минимальной';
            header('Location: lot.php?id=' . $id);
            exit();
        } elseif (empty($errors['cost'])) {
            $data = [(int) $_POST['cost'], (int) $_SESSION['user']['id'], (int) $id];
            add_new_rate_to_db($link, ADD_NEW_RATE, $data, $id);
            header('Location: lot.php?id=' . $id);
            exit();
        }
    }
}
$lot_id = (int) htmlspecialchars($_GET['id']) ?? $id;
$lot = get_lot_by_id($link, $lot_id);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($lot_id) && isset($lot))) {
    $history_data = select_data_by_lot_id ($link, HISTORY_DATA, $lot_id);
    if (isset($_SESSION['user'])) {

        $rates_data = select_data_by_lot_id ($link, RATES_DATA, $lot_id);
        if ($rates_data) {
            $starting_price = $rates_data[0]['starting_price'];
            $amount = ($rates_data[0]['rate_amount'] === 0 ) ? $starting_price : $rates_data[0]['rate_amount'];
            $min_rate = $rates_data[0]['rate_step'] + $amount;
        } else {
            $rates_data = select_data_by_lot_id ($link, STARTING_PRICE, $lot_id);
            $min_rate = $rates_data[0]['starting_price'] + $amount;
        }

        include_template ('lot', 'Лот', $categories, $user_avatar,
            ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'rates_data' => &$rates_data,
            'history_data' => &$history_data,
            'min_rate' => &$min_rate,
            'errors' => &$errors
            ]);
        exit();
    }
    include_template ('lot', 'Лот', $categories, $user_avatar,
        ['categories' => $categories,
        'lot' => $lot,
        'lot_id' => $lot_id,
        'history_data' => $history_data
        ]);
    } else {
        http_response_code(404);
        include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404);
}
http_response_code(404);
include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404);

/* if (empty($post_cost)) {
        $errors['cost'] = 'Это поле необходимо заполнить';
    }elseif ($post_cost <= 0 || is_int($errors['cost'])) {
        $errors['cost'] = 'Значение должно положительным целым числом';
    }elseif (is_int(isset($_POST['cost'])) > 0
    && ($post_cost) >= $min_rate
    && empty($errors['cost'])) {
    $data = [$post_cost, $session_user['id'], $lot_id];
    add_new_rate_to_db($link, ADD_NEW_RATE, $data, $lot_id);
<?= isset($lot_id) ? print $lot_id : print ''; ?>
    include_template ('lot', 'Лот', $categories, $user_avatar,  [
        'categories' => $categories,
        'lot' => $lot,
        'rates_data' => $rates_data,
        'history_data' => &$history_data,
        'min_rate' => &$min_rate,
        'lot_id' => $lot_id
        ]);
    if (is_int($_POST['id'])) {
        header('Location: lot.php?id=' . $_POST['id']);
        exit();
    } else {
        http_response_code(404);
        include_template ('404', '404 страница не найдена', $categories, $user_avatar, $p_404);
    }
}
         include_template ('lot', 'Лот', $categories, $user_avatar,
            ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id,
            'history_data' => &$history_data,
            'min_rate' => &$min_rate
            ]);
            exit();
        } else {
        include_template ('lot', 'Лот', $categories, $user_avatar,
            ['categories' => $categories,
            'lot' => $lot,
            'lot_id' => $lot_id
            ]);
        } */
