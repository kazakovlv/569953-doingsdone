<?php
require_once("ini.php"); //Подключаем общие переменные
require_once("functions.php");
require_once("db_connect.php"); //Подключаем базу данных, при ошибке подключения получаем сообщение

$projectList = [];

session_start();
if (!isset($_SESSION["user"])) {
    $page_content = include_template("guest.php", []);
    $layout_content = include_template("layout.php", ["title" => $title, "page_content" => $page_content]);
    print($layout_content);
    exit();
}
$userData = $_SESSION["user"];

// Определение фильтра задач по проектам
$projectFilter = "";
$active_project = null;

$sql = "SELECT projects.id,projects.project_name,Count( tasks.id ) AS task_count FROM projects ";
$sql = $sql . "LEFT JOIN tasks ON tasks.id_project = projects.id ";
$sql = $sql . "WHERE projects.id_user = ? GROUP BY projects.id ORDER BY 2";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $userData["id"]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$projectList = mysqli_fetch_all($res, MYSQLI_ASSOC);
$taskItem = [];

if ((string)$_SERVER["REQUEST_METHOD"] !== "POST") {
    $page_content = include_template("add.php", ["projectList" => $projectList, "taskItem" => $taskItem]);
    goto output;
}
//Вылидация формы
$taskItem = $_POST["taskItem"];
$required = ["project", "name", "date"];
$dict = ["project" => "Проект задачи", "name" => "Название задачи", "date" => "Срок выполнения"];
$errors = [];
if (!is_valid_date($taskItem["date"])) {
    $errors["date"] = "Ошибка даты";
}

foreach ($required as $key) {
    if (empty($taskItem[$key])) {
        $errors[$key] = 'Это поле надо заполнить';
    }
}

if (!is_user_project($link, $userData["id"], $taskItem["project"])) {
    $errors["project"] = "Ошибка выбора проекта";
}
//Конец валидации
$fileName = "";
$filePath = "";
$sourceFile = "";

if (count($errors)) {
    $page_content = include_template("add.php", ["projectList" => $projectList, "taskItem" => $taskItem,
        "errors" => $errors]);
    goto output;
}

if (isset($_FILES["taskFile"]["tmp_name"]) && (int)$_FILES["taskFile"]["error"] === 0) {
    $fileName = uniqid() . "." . pathinfo($_FILES["taskFile"]["name"], PATHINFO_EXTENSION);
    if (!file_exists("uploads/")) {
        mkdir("uploads/");
    }
    $filePath = "uploads/" . $fileName;
    $sourceFile = $_FILES["taskFile"]["tmp_name"];
    move_uploaded_file($sourceFile, $filePath);
}

$sql = "INSERT INTO `tasks` ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline ) ";
$sql = $sql . "VALUES ( ?, ?, NOW( ), '1970-01-01', 0, ?, ?, ? )";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "iisss", $userData["id"], $taskItem["project"], $taskItem["name"], $fileName, $taskItem["date"]);
$res = mysqli_stmt_execute($stmt);
if ($res) {
    $task_id = mysqli_insert_id($link);
    header("Location: /index.php");
}

output:
$layout_content = include_template("layout.php", ["title" => $title, "projectList" => $projectList,
    "page_content" => $page_content, "active_project" => $active_project, "userData" => $userData]);
print($layout_content);

