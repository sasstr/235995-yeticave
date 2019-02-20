<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

define('IMG_FILE_TYPES', ['image/jpeg', 'image/png']);

define( 'INSERT_NEW_LOT',
        'INSERT INTO lots (`title`, `description`, `img_path`, `starting_price`, `rate_step`, `finishing_date` `category_id`, `author_id`)
        VALUES (?, ?, ?, ?, ?, ?, ?, 7);'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
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

    if( ($new_lot['lot-date']) !== date('Y-m-d' , strtotime($new_lot['lot-date'])) || strtotime($new_lot['lot-date']) < strtotime('tomorrow')) {
        $errors['lot-date'] = 'Введите корректную дату завершения торгов, которая позже текущей даты хотя бы на один день';
    }


    if (isset($_FILES['img_path'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $_FILES['img_path']['tmp_name'];
        $file_type = finfo_file($finfo, $file_name);

        if(!array_search($file_type, IMG_FILE_TYPES)) {
            $errors['img_path'] = 'Необходимо загрузить фото с расширением PNG или JPEG';
        }
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
