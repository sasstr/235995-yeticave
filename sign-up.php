<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sign_up = $_POST;
    $errors = [];

    $req_fields = ['email', 'password', 'name', 'message'];

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
    // Валидация на загрузку файла с картинкой лота
    // Проверяем есть ли каталог для загрузки картинок на сервере
    if(!file_exists('/upload/')){
        mkdir('/upload/');
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
            $file_url = '/upload/' . trim($file_name_uniq);
            // Перемещение загруженного файла в папку сайта
            move_uploaded_file($file_tmp_name, UPLOAD_DIR . $file_name_uniq);
        }
    }
    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $sign_up['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if (mysqli_num_rows($res) > 0) {
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
                'is_auth' => $is_auth,
                'user_name' => $user_name,
                'user_avatar' => $user_avatar
            ]);
            exit();
        }
        else {
            $password = password_hash($sign_up['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (registration_date, email, name, password, avatar) VALUES (NOW(), ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$sign_up['email'], $sign_up['name'], $password, $file_url]);
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
            'is_auth' => $is_auth,
            'user_name' => $user_name,
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
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);
