<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$clientId = 1;
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
$projectList = [];
$taskList = [];
// Определение фильтра задач по проектам
$projectFilter = "";
$link = mysqli_connect("localhost", "root", "", "doingsdone");

mysqli_set_charset($link, "utf8");
if (!$link) {
    $error = mysqli_connect_error();
    $taskList = [
        0 => [
            "task" => $error,//task_name
            "completion" => "",//date_deadline
            "category" => "",//id_project
            "completed" => ""//`status`
        ]
    ];
} else {
    // Определение фильтра задач по проектам
    if (isset($_GET['project_id'])) {
        if (empty($_GET['project_id']) OR !is_numeric($_GET['project_id']) oR is_fake($clientId, $_GET['project_id'])) {
            header("HTTP/1.1 404 Not Found");
            die("HTTP/1.1 404 Not Found");
        }
        $projectFilter = " AND id_project = " . $_GET['project_id'];
    }

    $sql = "SELECT tasks.id,tasks.id_project,tasks.date_create,tasks.date_completion,tasks.`status`,tasks.task_name,";
    $sql = $sql . "tasks.file_name,tasks.date_deadline ";
    $sql = $sql . "FROM tasks WHERE tasks.id_user = ?" . $projectFilter . " ORDER BY tasks.date_deadline ASC";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $clientId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $taskList = mysqli_fetch_all($res,MYSQLI_ASSOC);

    $sql = "SELECT projects.id,projects.project_name,Count( tasks.id ) AS task_count FROM projects ";
    $sql = $sql . "LEFT JOIN tasks ON tasks.id_project = projects.id ";
    $sql = $sql . "WHERE projects.id_user = ? GROUP BY projects.id ORDER BY 2";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $clientId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projectList = mysqli_fetch_all($res,MYSQLI_ASSOC);
}

$taskItem = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskItem = $_POST["taskItem"];
    $fileName = "uploads/". uniqid() . "." . pathinfo($_FILES["taskFile"]["name"]["fileName"],PATHINFO_EXTENSION);
    $sourceFile = $_FILES["taskFile"]["tmp_name"]["fileName"];
    move_uploaded_file($sourceFile, $fileName);
}
$page_content = include_template("add.php", ["projectList" =>$projectList, "taskItem" => $taskItem]);
//print($page_content);

$layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
    "taskList" => $taskList, "page_content" => $page_content]);
print($layout_content);
?>
