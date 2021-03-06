<?php
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
    </head>
    <body>
            <h1>Поздравляем с победой</h1>
        <p>Здравствуйте, <?= $winner['user_name'] ?></p>
        <p>Ваша ставка для лота <a href="lot.php?id=<?= $winner['id'] ?>"><?= $winner['title'] ?></a> победила.</p>
        <p>Перейдите по ссылке <a href="my-lots.php">мои ставки</a>,
        чтобы связаться с автором объявления</p>

        <small>Интернет Аукцион "YetiCave"</small>
    </body>
</html>
    /* Установите через Composer библиотеку SwiftMailer
Создайте новый сценарий - getwinner.php и подключите его в index.php
Напишите в этом сценарии всю логику процесса "Определение победителя" из ТЗ
Создайте новый шаблон - email.php и заполните его следующим контентом:

    <h1>Поздравляем с победой</h1>
    <p>Здравствуйте, [имя пользователя]</p>
    <p>Ваша ставка для лота <a href="[ссылка на лот]">[имя лота]</a> победила.</p>
    <p>Перейдите по ссылке <a href="[ссылка на страницу мои ставки]">мои ставки</a>,
    чтобы связаться с автором объявления</p>

    <small>Интернет Аукцион "YetiCave"</small>
Отправьте сообщение победителю. Используйте в качестве содержимого письма результат работы шаблона.

	text/html */
