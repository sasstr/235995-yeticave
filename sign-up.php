<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

$tpl_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sign_up = $_POST;
    $errors = [];

    $req_fields = ['email', 'password', 'name', 'message'];

    foreach ($req_fields as $field) {
        if (empty($sign_up[$field])) {
            $errors[] = "Не заполнено поле " . $field;
        }
        if ($field === "email") {
            if (!filter_var($sign_up['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[$field] = 'Email должен быть корректным';
            }
        }
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $sign_up['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors[] = 'Пользователь с этим email уже зарегистрирован';
        }
        else {
            $password = password_hash($sign_up['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (registration_date, email, name, password) VALUES (NOW(), ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$sign_up['email'], $sign_up['name'], $password]);
            $res = mysqli_stmt_execute($stmt);

            if ($res && empty($errors)) {
                header("Location: /login.php");
                exit();
            }
        }
        $tpl_data['errors'] = $errors;
        $tpl_data['values'] = $sign_up;
        if(count($errors)){
            $sign_up_tpl = render('sign-up', $sign_up_page);
            print render('layout', [
                'content' => $sign_up_tpl,
                'title' => 'Страница регистрации нового пользователя',
                'categories' => $categories,
                'is_auth' => $is_auth,
                'user_name' => $user_name,
                'user_avatar' => $user_avatar,
                'errors' => $tpl_data['errors'],
                'values' => $tpl_data['values']
            ]);
        }
    }
}
$sign_up_tpl = render('sign-up', $sign_up_page);
print render('layout', [
    'content' => $sign_up_tpl,
    'title' => 'Страница регистрации нового пользователя',
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
