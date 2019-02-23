<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

define('IMG_FILE_TYPES', ['jpg' =>'image/jpeg',
                          'jpeg' => 'image/pjpeg',
                          'png' =>'image/png']);
// Получаем данные из формы создания нового лота и валидируем все поля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;
    $required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
// Валидация на заполнение обязательных полей
    foreach ($required_fields as $field) {
        if (empty($new_lot[$field])) {
            $errors[$field] = 'Заполните это поле оно не может быть пустым.';
        }
// Валидация на выбор категории
        if($field === 'category' && $new_lot[$field] === 'Выберите категорию') {
            $errors[$field] = 'Выберите категорию из списка';
        }
    }
// Валидация на заполнение числовых значений цены и мин ставки
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
// Валидация на заполнение верной даты
    if( ($new_lot['lot-date']) !== date('Y-m-d' , strtotime($new_lot['lot-date'])) || strtotime($new_lot['lot-date']) < strtotime('tomorrow')) {
        $errors['lot-date'] = 'Введите корректную дату завершения торгов, которая позже текущей даты хотя бы на один день';
    }

// Валидация на загрузку файла с картинкой лота
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
            // Перемещение загруженного файла в папку сайта
            move_uploaded_file($file_tmp_name, $file_path . $file_name_uniq);

            $link = db_connect();
            // Создание подготовленного выражения
            $sql = 'INSERT INTO lots (`title`,
                                      `description`,
                                      `img_path`,
                                      `starting_price`,
                                      `starting_date`,
                                      `rate_step`,
                                      `finishing_date`,
                                      `user_id`,
                                      `category_id`,
                                      `author_id`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
            $author_id = 7;
            $user_id = 4;
            $lot_data = [$new_lot['lot-name'],
                        $new_lot['message'],
                        $file_url,
                        $new_lot['lot-rate'],
                        $starting_date,
                        $new_lot['lot-step'],
                        $new_lot['lot-date'],
                        $user_id,
                        $new_lot['category'],
                        $author_id
                        ];
            $starting_date = date_format(date_create('now'), 'Y-m-d H:i:s');
            $stmt = mysqli_prepare($link, $sql);
            if (!$link) {
                printf("Не удалось подключиться: %s\n", mysqli_connect_error());
                exit();
            }
            var_dump($link);
            var_dump($new_lot);

            /*mysqli_stmt_bind_param($stmt, 'sssssssisi', $new_lot['lot-name'],
                                                    $new_lot['message'],
                                                    $file_url,
                                                    $new_lot['lot-rate'],
                                                    $starting_date,
                                                    $new_lot['lot-step'],
                                                    $new_lot['lot-date'],
                                                    $user_id,
                                                    $new_lot['category'],
                                                    $author_id);*/
            $stmt = db_get_prepare_stmt($link, $sql, $lot_data); 
            var_dump($stmt);
            mysqli_stmt_execute($stmt);
            /*$res = mysqli_stmt_execute($stmt);
// Функция добавляет новый лот в БД и в случае успеха перенаправляет пользователя на страницу нового лота.
                function db_insert_data($link, $sql, $data = []) {
                    $stmt = db_get_prepare_stmt($link, $sql, $data);
                    mysqli_stmt_execute($stmt);
                    $res = mysqli_stmt_get_result($stmt); 
                    if ($res) {
                    $lot_id = mysqli_insert_id($link);
                    header("Location: lot.php?id=" . $lot_id);
                    exit();
                    }
            */
             $res = mysqli_stmt_get_result($stmt); 
            var_dump($res);

            // Проверка на добавление в БД записи о новом лоте и перенаправление на страницу с новым лотом.
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
