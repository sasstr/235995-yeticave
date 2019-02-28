<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$login = $_POST;

	$required = ['email', 'password'];
	$errors = [];
	foreach ($required as $field) {
	    if (empty($login[$field])) {
	        $errors[$field] = 'Это поле надо заполнить';
        }
    }

	$email = mysqli_real_escape_string($link, $login['email']);
	$sql = "SELECT * FROM users WHERE email = '$email'";
	$res = mysqli_query($link, $sql);

	$user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

	if (!count($errors) && $user) {
		if (password_verify($login['password'], $user['password'])) {
			$_SESSION['user'] = $user;
		}
		else {
			$errors['password'] = 'Вы ввели неверный пароль';
		}
	}
	else {
		$errors['email'] = 'Такой пользователь не найден';
	}

	if (count($errors)) {
        $login = render('login', [
            'categories' => $categories,
            'errors' => $errors,
            'login' => $login,
        ]);
        print render('layout', [
            'content' => $login,
            'title' => 'Вход на сайт под своим логином и паролем',
            'categories' => $categories,
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
    'user_avatar' => $user_avatar
]);
