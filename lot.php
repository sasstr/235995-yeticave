<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$lot_id = (int) $_GET['id'];
$lot = get_lot_by_id($link, $lot_id);
 if(isset($lot_id) && isset($lot)){

    if (isset($_SESSION['user'])){
        $rates_data = select_data_by_lot_id ($link, RATES_DATA, $lot_id);
        $rates_history = $rates_data[0];
        $min_rate = $rates_data[0]['rate_step'] + $rates_data[0]['rate_amount'];

         if (empty($_POST['cost'])) {
            $errors['cost'] = 'Это поле необходимо заполнить';
        } elseif ($_POST['cost'] <= 0 || is_int($errors['cost'])) {
            $errors['cost'] = 'Значение должно положительным целым числом';
        } elseif (is_int(isset($_POST['cost'])) > 0
            && ($_POST['cost']) >= $min_rate
            && empty($errors['cost'])) {
            $data = [$_POST['cost'], $_SESSION['user']['id'], $lot_id];
            add_new_rate_to_db($link, ADD_NEW_RATE, $data, $_GET['id']);
        $lot_page = render('lot', [
            'categories' => $categories,
            'lot' => $lot,
            'rates_data' => $rates_data,
            'min_rate' => $min_rate,
            'rates_history' => $rates_history
            ]);
            print render('layout', [
                'content' => $lot_page,
                'title' => 'Лот',
                'categories' => $categories,
                'user_avatar' => $user_avatar
            ]);
        exit();
        }
        } else {
            $lot_page = render('lot', [
            'categories' => $categories,
            'lot' => $lot
            ]);
            print render('layout', [
                'content' => $lot_page,
                'title' => 'Лот',
                'categories' => $categories,
                'user_avatar' => $user_avatar
            ]);
            exit();
        }
        $lot_page = render('lot', [
            'categories' => $categories,
            'lot' => $lot
            ]);
            print render('layout', [
                'content' => $lot_page,
                'title' => 'Лот',
                'categories' => $categories,
                'user_avatar' => $user_avatar
            ]);
            exit();
    } else {
        http_response_code(404);
        $page_404 = render('404', $p_404);
        print render('layout', [
            'content' => $page_404,
            'title' => '404 страница не найдена',
            'categories' => $categories,
            'user_avatar' => $user_avatar
        ]);
    }

