<?php
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$login_page = [
    'categories' => $categories,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($login[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $email = $login['email'];
    $sql = 'SELECT * FROM users WHERE email = ?';

    $res = db_select ($link, $sql, [$email]);
    $user = $res ?? null;

    if (!count($errors) && $user && password_verify($login['password'], $user['0']['password'])) {
            $_SESSION['user'] = $user;
    } else {
        $errors['password'] = 'Вы ввели неверный пароль';
    }
    if (!count($errors) && $user) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $login = render('login', [
            'categories' => $categories,
            'errors' => $errors,
            'page_categories' => &$page_categories,
            'login' => $login,
        ]);
        print render('layout', [
            'content' => $login,
            'title' => 'Вход на сайт под своим логином и паролем',
            'categories' => $categories,
            'page_categories' => &$page_categories,
            'user_avatar' => $user_avatar
        ]);
    }
    else {
        header("Location: /index.php");
        exit();
    }
}

$login = render('login', $login_page);
print render('layout', [
    'content' => $login,
    'title' => 'Вход на сайт под своим логином и паролем',
    'categories' => $categories,
    'page_categories' => &$page_categories,
    'user_avatar' => $user_avatar
]);
