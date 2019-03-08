<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
    }

// Получаем данные из формы создания нового лота и валидируем все поля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $lot_name = $_POST['lot-name'] ?? '';
    $value = $_POST['category'] ?? '';
    $lot_rate = $_POST['lot-rate'] ?? '';
    $lot_step = $_POST['lot-step'] ?? '';
    $lot_date = $_POST['lot-date'] ?? '';
    $new_lot = $_POST;
    $required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
// Валидация на заполнение обязательных полей
    foreach ($required_fields as $field) {
        if (empty($new_lot[$field])) {
            $errors[$field] = 'Заполните это поле оно не может быть пустым.';
        }
// Валидация на выбор категории
        if($field === 'category' && $new_lot[$field] === "CATEGORY_SELECTOR") {
            $errors[$field] = 'Выберите категорию из списка';
        }
    }
// Валидация на заполнение числовых значений цены и мин ставки
    foreach($new_lot as $key => $value) {
        if($key === 'lot-rate' || $key === 'lot-step') {
            if(!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
                $errors[$key] = 'Введите в это поле положительное и целое число.';
            } 
                
                    
                
            
        }
    }
// Валидация на заполнение верной даты
    if( ($new_lot['lot-date']) !== date('Y-m-d' , strtotime($new_lot['lot-date'])) || strtotime($new_lot['lot-date']) < strtotime('tomorrow')) {
        $errors['lot-date'] = 'Введите корректную дату завершения торгов, которая позже текущей даты хотя бы на один день';
    }
    $file_url = MOCK_IMG_LOT;
    move_file_to_upload('lot-', 
                        $_FILES['img-file']['name'], 
                        $_FILES['img-file']['tmp_name'],
                        UPLOAD_DIR,
                        UPLOAD_LOCAL_DIR,
                        IMG_FILE_TYPES
                        );
// Валидация на загрузку файла с картинкой лота
    // Проверяем есть ли каталог для загрузки картинок на сервере
    /* if(!file_exists(UPLOAD_LOCAL_DIR)) {
        mkdir(UPLOAD_LOCAL_DIR);
        if (!file_exists(UPLOAD_LOCAL_DIR)) {
            $errors['img-file'] = 'Не удалось найти или создать папку для загрузки файла';

        }
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
            $file_name_uniq = uniqid('lot-') . '.' . pathinfo($file_name , PATHINFO_EXTENSION);
            $file_url = UPLOAD_LOCAL_DIR . trim($file_name_uniq);
            // Перемещение загруженного файла в папку сайта
            move_uploaded_file($file_tmp_name, UPLOAD_DIR . $file_name_uniq);
        }
    } */
    if (!$link) {
        printf("Не удалось подключиться: %s\n", mysqli_connect_error());
        exit();
    }
    // Создание подготовленного выражения
    $user_id = 4;
    $starting_date = date_format(date_create('now'), 'Y-m-d H:i:s');
    $lot_data = [$new_lot['lot-name'],
                $new_lot['message'],
                &$file_url,
                $new_lot['lot-rate'],
                $starting_date,
                $new_lot['lot-step'],
                $new_lot['lot-date'],
                $user_id,
                $new_lot['category']
                ];
        $res_add_new_lot = db_insert($link, ADD_NEW_LOT, $lot_data);
        if ($res_add_new_lot) {
            $lot_id = mysqli_insert_id($link);
            header('Location: lot.php?id=' . $lot_id);
            exit();
        }
        if(count($errors)){
            $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';
        $add_lot = render('add', [
            'categories' => $categories,
            'errors' => $errors,
            'file_url' => $file_url,
            'message' => $message,
            'lot_name' => $lot_name,
            'category_value' => $category_value,
            'lot_rate' => $lot_rate,
            'lot_step' => $lot_step,
            'lot_date' => $lot_date
            ]);
        print render('layout', [
            'content' => $add_lot,
            'title' => 'Добавить новый лот',
            'categories' => $categories,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar
        ]);
        exit();
    }

} else {
    $add_lot = render('add', [
        'categories' => $categories,
        'message' => &$message,
        'lot_name' => &$lot_name,
        'category_value' => &$category_value,
        'lot_rate' => &$lot_rate,
        'lot_step' => &$lot_step,
        'lot_date' => &$lot_date
    ]);
    print render('layout', [
        'content' => $add_lot,
        'title' => 'Добавить новый лот',
        'categories' => $categories,
        'user_avatar' => $user_avatar
    ]);
    exit();
}
$add_lot = render('add', [
    'categories' => $categories
]);
print render('layout', [
    'content' => $add_lot,
    'title' => 'Добавить новый лот',
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
