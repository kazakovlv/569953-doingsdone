<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$clientId = 1;
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
$projectList = [];
//$taskList = [];
// Определение фильтра задач по проектам
$projectFilter = "";
$link = mysqli_connect("localhost", "root", "", "doingsdone");

if (!$link) {
    $error = mysqli_connect_error();
    die($error);
} else {
    mysqli_set_charset($link, "utf8");
    $sql = "SELECT projects.id,projects.project_name,Count( tasks.id ) AS task_count FROM projects ";
    $sql = $sql . "LEFT JOIN tasks ON tasks.id_project = projects.id ";
    $sql = $sql . "WHERE projects.id_user = ? GROUP BY projects.id ORDER BY 2";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $clientId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projectList = mysqli_fetch_all($res,MYSQLI_ASSOC);
}

$page_content = include_template("addproject.php", ["projectList" =>$projectList]);
//print($page_content);

$layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
    "page_content" => $page_content]);
print($layout_content);
?>

