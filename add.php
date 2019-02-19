<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

define( 'INSERT_NEW_LOT',
        'INSERT INTO lots (`title`, `description`, `img_path`, `starting_price`, `rate_step`, `finishing_date` `category_id`)
        VALUES (?, ?, ?, ?, ?, ?, ?);'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
    $errors = [];


    $required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($new_lot[$field])) {
            $errors[$field] = 'Заполните это поле оно не может быть пустым.';
        }

        if($field === 'category' && $new_lot[$field] === 'Выберите категорию') {
            $errors[$field] = 'Выберите категорию из списка';
        }
    }

    foreach($new_lot as $key => $value) {
        if($key === 'lot-rate' || $key === 'lot-step') {
            if(!filter_var($value, FILTER_VALIDATE_INT)) {
                $errors[$key] = 'Введите в это поле положительное, целое число.';
            } else {
                if($value <= 0) {
                    $errors[$key] = 'Введите в это поле положительное, целое число.';
                }
            }
        }
    }

    if(!checkdate($new_lot['lot-date']) || strtotime($new_lot['lot-date']) < strtotime('tomorrow')) {
        $errors['lot-date'] = 'Введите корректную дату завершения торгов, которая позже текущей даты хотя бы на один день';
    }

}

$add_lot = render('add', $add_lot_page);
print render('layout', [
    'content' => $add_lot,
    'title' => 'Добавить новый лот',
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
