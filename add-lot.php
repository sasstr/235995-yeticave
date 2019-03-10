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
    $category_value = $_POST['category'] ?? '';
// Получаем данные из формы создания нового лота и валидируем все поля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $lot_name = $_POST['lot-name'] ?? '';
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
        if($field === 'category' && $new_lot[$field] === CATEGORY_SELECTOR) {
            $errors[$field] = 'Выберите категорию из списка';
        }
    }
    $errors['category'] = check_category_value ($categories, $_POST['category']);

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
    // $file_url = MOCK_IMG_LOT;
    $file_to_upload = move_file_to_upload('lot-',
                        $_FILES['img-file']['name'],
                        $_FILES['img-file']['tmp_name'],
                        UPLOAD_DIR,
                        UPLOAD_LOCAL_DIR,
                        IMG_FILE_TYPES
                        );

    if ($file_to_upload === 'Файл не загружен, необходимо загрузить фото' || $file_to_upload === 'Необходимо загрузить фото с расширением JPEG, JPG или PNG') {
        $errors['img-file'] = $file_to_upload;
    } else {
        $file_url = $file_to_upload;
    }

    if (count($errors) >1 || $errors['category'] !== null /* && $errors['img-file'] !== null */) {
        $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';

        include_template('add', 'Добавить новый лот', $categories, $user_avatar, [
        'categories' => $categories,
        'page_categories' => $page_categories,
        'errors' => $errors,
        'file_url' => &$file_url,
        'message' => &$message,
        'lot_name' => &$lot_name,
        'category_value' => $category_value,
        'lot_rate' => &$lot_rate,
        'lot_step' => &$lot_step,
        'lot_date' => &$lot_date
        ], $page_categories);
        exit();
    } else {
        if (!$link) {
            printf("Не удалось подключиться: %s\n", mysqli_connect_error());
            exit();
        }
        // Создание подготовленного выражения
        $user_id = $_SESSION['user']['0']['id'] ?? '';
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
        $lot_id = add_new_lot($link, $lot_data);
            header('Location: lot.php?id=' . $lot_id);
            exit();
    }
} else {
    include_template ('add', 'Добавить новый лот', $categories, $user_avatar, [
        'categories' => $categories,
        'page_categories' => $page_categories,
        'message' => &$message,
        'lot_name' => &$lot_name,
        'category_value' => &$category_value,
        'lot_rate' => &$lot_rate,
        'lot_step' => &$lot_step,
        'lot_date' => &$lot_date
        ], $page_categories);
    exit();
}

include_template ('add', 'Добавить новый лот', $categories, $user_avatar, [
    'categories' => $categories,
    'page_categories' => $page_categories
    ], $page_categories);
