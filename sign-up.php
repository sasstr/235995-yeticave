<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_up = $_POST['message'] ?? '';
    $sign_up = $_POST;
    $errors = [];

    $req_fields = ['email', 'password', 'name'];

    foreach ($req_fields as $field) {
        if (empty($sign_up[$field])) {
            $errors[] = "Не заполнено поле " . $field;
        }
        if (!empty($sign_up[$field]) && $field === "email") {
            if (!filter_var($sign_up['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[$field] = 'E-mail должен быть корректным';
            }
        }
    }
    $file_url = move_file_to_upload ('avatar-',
                        $_FILES['img-avatar']['name'],
                        $_FILES['img-avatar']['tmp_name'],
                        UPLOAD_DIR,
                        UPLOAD_LOCAL_DIR,
                        IMG_FILE_TYPES
                        ) ?? MOCK_IMG;

    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $sign_up['email']);
        $rows = check_email_in_db ($link, $email);

        if ($rows > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
            include_template ('sign-up', 'Страница регистрации нового пользователя', $categories, $user_avatar, [
                'categories' => $categories,
                'page_categories' => $page_categories,
                'errors' => $errors,
                'sign_up' =>$sign_up
            ], $page_categories);
            exit();
        }
        else {
            $password = password_hash($sign_up['password'], PASSWORD_DEFAULT);
            // 62. sign-up.php. Стр 50. Запрос выносим в функцию.
            $sql = 'INSERT INTO users (registration_date, email, name, password, contacts, avatar) VALUES (NOW(), ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$sign_up['email'], $sign_up['name'], $password, $sign_up['message'], $file_url]);
            $res = mysqli_stmt_execute($stmt);

            if ($res && empty($errors)) {
                header("Location: /login.php");
                exit();
            }
        }
    } else {
        include_template ('sign-up', 'Страница регистрации нового пользователя', $categories, $user_avatar, [
            'categories' => $categories,
            'page_categories' => $page_categories,
            'sign_up' =>$sign_up,
            'message_up' => &$message_up,
            'errors' => $errors
        ], $page_categories);
    }
}
include_template ('sign-up', 'Страница регистрации нового пользователя', $categories, $user_avatar, [
    'categories' => $categories,
    'page_categories' => $page_categories,
    'sign_up' => &$sign_up
], $page_categories);
