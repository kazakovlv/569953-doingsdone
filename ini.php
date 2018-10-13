<?php
/* Файл общих переменных*/
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y"; //Устанавливаем формат даты
$title = "Дела в порядке"; //Заголовок сайта


$link = mysqli_connect("localhost", "root", "", "doingsdone");
// Подключение к базе данных
?>
