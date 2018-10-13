<?php
/* Файл общих переменных*/
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y"; //Устанавливаем формат даты
$title = "Дела в порядке"; //Заголовок сайта
$filter_task = []; /*Массив фильтрации задач по датам
 all - Все задачи
 today - Повестка дня
 tomorrow - Завтра
 overdue - Просроченные*/
$all_filter_param = []; // Коллекция параметров фильтрации задач

?>
