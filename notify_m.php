<?php
require_once("ini.php"); //Подключаем общие переменные
require_once("functions.php"); //Подключаем функции
require_once("db_connect.php"); //Подключаем базу данных, при ошибке подключения получаем сообщение

sendLetters($link);
