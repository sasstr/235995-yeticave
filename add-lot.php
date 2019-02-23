<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

define('IMG_FILE_TYPES', ['jpg' =>'image/jpeg',
                          'jpeg' => 'image/pjpeg',
                          'png' =>'image/png']);

define('ALL_CATEGORIES',
        'SELECT * FROM categories
        ORDER BY id;');
define( 'INSERT_NEW_LOT',
        'INSERT INTO lots (`title`, `description`, `img-file`, `starting_price`, `rate_step`, `finishing_date` `category_id`, `author_id`)
        VALUES (?, ?, ?, ?, ?, ?, ?, 7);'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
    var_dump($new_lot);
    var_dump($_FILES);
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


    if (isset($_FILES['img-file']['name']) && !empty($_FILES['img-file']['name'])) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $_FILES['img-file']['tmp_name']);

        if(!array_search($file_type, IMG_FILE_TYPES)) {
            $errors['img-file'] = 'Необходимо загрузить фото с расширением JPEG, JPG или PNG';
        } else {
            $file_tmp_name = $_FILES['img-file']['tmp_name'];
            $file_name = $_FILES['img-file']['name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $file_tmp_name);
            $file_name_uniq = uniqid() . '.' . pathinfo($file_name , PATHINFO_EXTENSION);
            $file_path = __DIR__ . '/upload/';
            $file_url = '/upload/' . trim($file_name_uniq);
            move_uploaded_file($file_tmp_name, $file_path . $file_name_uniq);
            /* $sql = 'INSERT INTO lots (`title`, `description`, `img-file`, `starting_price`, `rate_step`, `finishing_date` `category_id`, `author_id`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?);'; */
            $author_id = 7;
            /* $stmt = db_get_prepare_stmt($link, $sql, [$_POST['lot-name'],

            $_POST['message'],
                                                    $file_url,
                                                    $_POST['lot-rate'],
                                                    $_POST['lot-step'],
                                                    $_POST['lot-date'],
                                                    $_POST['category'],
                                                    $author_id
                                                    ]); */
            $sql = 'INSERT INTO lots
            (`title`, `description`, `img_path`, `starting_price`, `starting_date`, `rate_step`, `finishing_date`, `user_id`, `winner_id`, `category_id`)
            VALUE ('2019 Rossi Snowboard New', 'настоящий крутой сноуборд', 'img/lot-1.jpg', 8790, '2019-02-01 11:15:10', 700, '2019-05-08 21:15:10', 7, 7, 1);';
            /* $stmt = mysqli_prepare($link, $sql); var_dump($stmt);*/
            /*  mysqli_stmt_bind_param($stmt, 'sssssssi', [$new_lot['lot-name'],
                                                    $new_lot['message'],
                                                    $file_url,
                                                    $new_lot['lot-rate'],
                                                    $new_lot['lot-step'],
                                                    $new_lot['lot-date'],
                                                    $new_lot['category']
                                                    ]); */

            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?id=" . $lot_id);
                exit();
            } else {
                $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';
                $add_lot = render('add', [
                    'categories' => $categories,
                    'errors' => $errors,
                    'file_url' => $file_url
                    ]);
                print render('layout', [
                    'content' => $add_lot,
                    'title' => 'Добавить новый лот',
                    'categories' => $categories,
                    'is_auth' => $is_auth,
                    'user_name' => $user_name,
                    'user_avatar' => $user_avatar
                ]);
            }
        }
    }

    if (count($errors)) {
        $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';
        $add_lot = render('add', [
            'categories' => $categories,
            'errors' => $errors,
            'img_src' => $img_src
        ]);
        print render('layout', [
            'content' => $add_lot,
            'title' => 'Добавить новый лот',
            'categories' => $categories,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar
        ]);
    }
} else {
        $add_lot = render('add', [
            'categories' => $categories
        ]);
        print render('layout', [
            'content' => $add_lot,
            'title' => 'Добавить новый лот',
            'categories' => $categories,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar
        ]);
}
