<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
session_start();

if (!isset($_SESSION["user"])) {
    $page_content = include_template("guest.php", []);
    $layout_content = include_template("layout.php",  ["title" => $title, "page_content" => $page_content]);
    print($layout_content);
    exit();
}
$userData = $_SESSION["user"];

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projectList = [];
$taskList = [];
// Определение фильтра задач по проектам
$projectFilter = "";
$projectFilterError = false;
// Подключение к базе данных
$link = mysqli_connect("localhost", "root", "", "doingsdone");
$active_project = null;

if (!$link) {
    $error = mysqli_connect_error();
    die($error);
} else {
    mysqli_set_charset($link, "utf8");
    // Определение фильтра задач по проектам
    if (isset($_GET['project_id'])) {
        if (empty($_GET['project_id']) OR !is_numeric($_GET['project_id']) OR is_fake($userData["id"], $_GET['project_id'])) {
            header("HTTP/1.1 404 Not Found");
            $projectFilterError = true;
            //print("Not Found");
            //die();
        }
        $projectFilter = " AND id_project = " . $_GET['project_id'];
        $active_project = $_GET['project_id'];
    }

    $sql = "SELECT tasks.id,tasks.id_project,tasks.date_create,tasks.date_completion,tasks.`status`,tasks.task_name,";
    $sql = $sql . "tasks.file_name,tasks.date_deadline ";
    $sql = $sql . "FROM tasks WHERE tasks.id_user = ?" . $projectFilter . " ORDER BY tasks.date_create DESC";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $userData["id"]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $taskList = mysqli_fetch_all($res,MYSQLI_ASSOC);

    $sql = "SELECT projects.id,projects.project_name,Count( tasks.id ) AS task_count FROM projects ";
    $sql = $sql . "LEFT JOIN tasks ON tasks.id_project = projects.id ";
    $sql = $sql . "WHERE projects.id_user = ? GROUP BY projects.id ORDER BY 2";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $userData["id"]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projectList = mysqli_fetch_all($res,MYSQLI_ASSOC);
}

// Конец подключения к БД

//sendLetters();
if ($projectFilterError) {
    $page_content = "<h2>Not Found!</h2>";
} else {
    if (isset($_GET["task_filter"])){
        $filter_task = $_GET["task_filter"];
        $page_content = include_template("index.php", ["taskList" => $taskList,
            "show_complete_tasks" => $show_complete_tasks, "active_project" => $active_project,
            "filter_task" =>$filter_task]);
    } else {
        $page_content = include_template("index.php", ["taskList" => $taskList,
            "show_complete_tasks" => $show_complete_tasks, "active_project" => $active_project]);
    }
}

//print($page_content);
if (isset($_GET["task_filter"])) {
    $layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
        "taskList" => $taskList, "page_content" => $page_content, "active_project" => $active_project,
        "filter_task" =>$filter_task, "userData" => $userData]);
} else {
    $layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
        "taskList" => $taskList, "page_content" => $page_content, "active_project" => $active_project,
        "userData" => $userData]);
}
print($layout_content);
?>
