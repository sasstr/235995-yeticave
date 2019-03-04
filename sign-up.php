<?php
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    $file_url = MOCK_IMG;
    // Валидация на загрузку файла с картинкой лота
    // Проверяем есть ли каталог для загрузки картинок на сервере
    if(!file_exists(UPLOAD_LOCAL_DIR)){
        mkdir(UPLOAD_LOCAL_DIR);
    }
    if (isset($_FILES['img-avatar']['name']) && !empty($_FILES['img-avatar']['name'])) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $_FILES['img-avatar']['tmp_name']);

        if(!array_search($file_type, IMG_FILE_TYPES)) {
            $errors['img-avatar'] = 'Необходимо загрузить фото с расширением JPEG, JPG или PNG';
        } else {
            $file_tmp_name = $_FILES['img-avatar']['tmp_name'];
            $file_name = $_FILES['img-avatar']['name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $file_tmp_name);
            $file_name_uniq = uniqid('avatar-') . '.' . pathinfo($file_name , PATHINFO_EXTENSION);
            $file_url = UPLOAD_LOCAL_DIR . trim($file_name_uniq);

            // Перемещение загруженного файла в папку сайта
            move_uploaded_file($file_tmp_name, UPLOAD_DIR . $file_name_uniq);
        }
    }
    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $sign_up['email']);
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$email]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $rows = mysqli_num_rows($res);

        /* $sql = "SELECT id FROM users WHERE email = '$email'"; */

        if ($rows > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
            $sign_up_tpl = render('sign-up', [
                'categories' => $categories,
                'errors' => $errors,
                'sign_up' => $sign_up
            ]);
            print render('layout', [
                'content' => $sign_up_tpl,
                'title' => 'Страница регистрации нового пользователя',
                'categories' => $categories,
                'user_avatar' => $user_avatar
            ]);
            exit();
        }
        else {
            $password = password_hash($sign_up['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (registration_date, email, name, password, contacts, avatar) VALUES (NOW(), ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$sign_up['email'], $sign_up['name'], $password, $sign_up['message'], $file_url]);
            $res = mysqli_stmt_execute($stmt);

            if ($res && empty($errors)) {
                header("Location: /login.php");
                exit();
            }
        }
    } else {
        $sign_up_tpl = render('sign-up', [
                'categories' => $categories,
                'errors' => &$errors,
                'sign_up' => $sign_up
            ]);
        print render('layout', [
            'content' => $sign_up_tpl,
            'title' => 'Страница регистрации нового пользователя',
            'categories' => $categories,
            'user_avatar' => $user_avatar
        ]);
    }
}
$sign_up_tpl = render('sign-up', [
                        'categories' => $categories,
                        'sign_up' => &$sign_up
]);
print render('layout', [
    'content' => $sign_up_tpl,
    'title' => 'Страница регистрации нового пользователя',
    'categories' => $categories,
    'user_avatar' => $user_avatar
]);
