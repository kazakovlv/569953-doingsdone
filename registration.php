<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$clientId = 1;
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";

$page_content = include_template("adduser.php", []);
$layout_content = include_template("layoutregister.php",  ["title" => $title, "page_content" => $page_content]);

print($layout_content);
?>
