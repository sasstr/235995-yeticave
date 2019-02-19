<?php
require_once('config.php');
require_once('functions.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_lot = $_POST;
    $errors = [];


    $required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($new_lot[$field])) {
            $errors[$field] = 'Заполните это поле он не может быть пустым.';
        }

        if($field === 'category' && $new_lot[$field] === 'Выберите категорию') {
            $errors[$field] = 'Выберите категорию';
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


}

$add_lot = render('add', $add_lot_page);
print render('layout', [
    'content' => $add_lot,
    'title' => 'Добавить новый лот',
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);

/* if ($_SERVER['REQUEST_METHOD'] == 'POST') {
header("Location: /index.php?success=true");
}

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <p>Спасибо за ваше сообщение!
        </p>
    </div>
<?php endif; ?>
*/
