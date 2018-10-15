<?php
/* Файл общих переменных*/
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y"; //Устанавливаем формат даты
$title = "Дела в порядке"; //Заголовок сайта
// Подключение к базе данных
$connection['host'] = "localhost"; // Адрес сервера
$connection['user'] = "root"; //Пользователь БД
$connection['password'] = ""; //Пароль Пользователя БД
$connection['database'] = "doingsdone"; // Название БД

