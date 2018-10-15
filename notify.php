<?php
require_once "vendor/autoload.php";
require_once("ini.php"); //Подключаем общие переменные
require_once("functions.php"); //Подключаем функции
require_once("db_connect.php"); //Подключаем базу данных, при ошибке подключения получаем сообщение

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$sql = "SELECT Count( tasks.id ) AS Count,users.id,users.user_name,users.email FROM tasks ";
$sql = $sql . "LEFT JOIN users ON tasks.id_user = users.id WHERE tasks.`status` = 0 ";
$sql = $sql . "AND tasks.date_deadline != \"1970-01-01 00:00:00\" AND tasks.date_deadline <= ( NOW( ) + INTERVAL 1 HOUR ) ";
$sql = $sql . "GROUP BY users.id";
$res = mysqli_query($link, $sql);
$res = mysqli_fetch_all($res, MYSQLI_ASSOC);

foreach ($res as $key => $value) {
    $letter = getHotTasks($link, $value["id"], $value["user_name"]);
    $message = new Swift_Message();
    $message->setTo($value["email"]);
    $message->setSubject("Уведомление от сервиса Дела в порядке.");
    $message->setFrom("keks@phpdemo.ru", "Дела в порядке");
    $message->setBody($letter, "text/html");
    $result = $mailer->send($message);

    if ($result) {
        print("Рассылка успешно отправлена");
    } else {
        print("Не удалось отправить рассылку: " . $logger->dump());
    }
}

