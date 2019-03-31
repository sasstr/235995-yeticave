<?php
require_once('vendor/autoload.php');
require_once('constants.php');
require_once('config.php');
require_once('functions.php');
require_once('init.php');
require_once('data.php');

$winners = db_get_lots_not_winners($link);

if(isset($winners) && count($winners) > 0) {

    foreach ($winners as $winner) {
        $rate_data = select_rates_data_by_id($link, $winner['id']);
        // Записать в лот победителем автора последней ставки
        insert_winner_to_db ($link, $winner['user_id']);
    }
        $mail_content = render('email', [
            'winner' => $winner,
            'rate_data' => $rate_data
        ]);

        // Формирование сообщения
        $message = new Swift_Message("Ваша ставка победила");
        // Content-type тела письма	text/html
        $content_type = $message->getHeaders()->get('Content-Type');
        $content_type->setValue('text/html');
        $content_type->setParameter('charset', 'utf-8');

        $message->setTo(["sasstr@gmail.com" => "sasstr"]);
        $message->setBody($mail_content);
        $message->setFrom("a.stra21@yandex.ru", "Yeticave Ваша ставка победила");
        // Отправка сообщения
        $mailer = new Swift_Mailer($mail_config['0']);
        // Чтобы иметь максимально подробную информацию о процессе отправки сообщений
        // мы попросим SwiftMailer журналировать все происходящее внутри массива.
        $logger = new Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

        try {
            $result = $mailer->send($message);
            if ($result) {
                print("Победителям письма успешно отправлены");
            } else {
                print("Не удалось отправить письма: " . $logger->dump());
            }
        } catch (Swift_TransportException $ex) {
            print($ex->getMessage() . '<br>');
        }

}
