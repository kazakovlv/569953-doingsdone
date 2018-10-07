<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
$projectList = [];

session_start();
if (!isset($_SESSION["user"])) {
    $page_content = include_template("guest.php", []);
    $layout_content = include_template("layout.php",  ["title" => $title, "page_content" => $page_content]);
    print($layout_content);
    exit();
}
$userData = $_SESSION["user"];

// Определение фильтра задач по проектам
$projectFilter = "";
$active_project = null;
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
    mysqli_stmt_bind_param($stmt,"i", $userData["id"]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projectList = mysqli_fetch_all($res,MYSQLI_ASSOC);
}

$taskItem = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (is_fake($userData["id"], $taskItem["project"])) {
        $errors["project"] = "Ошибка выбора проекта";
    }
    //Конец валидации
    $fileName = "";
    $filePath = "";
    $sourceFile = "";

    if (count($errors)) {
        $page_content = include_template("add.php", ["projectList" => $projectList, "taskItem" => $taskItem,
            "errors" => $errors]);
    } else {
        if (isset($_FILES["taskFile"]["tmp_name"]) & $_FILES["taskFile"]["error"] == 0) {
            $fileName = uniqid() . "." . pathinfo($_FILES["taskFile"]["name"],PATHINFO_EXTENSION);
            $filePath = "uploads/". uniqid() . "." . pathinfo($_FILES["taskFile"]["name"],PATHINFO_EXTENSION);
            $sourceFile = $_FILES["taskFile"]["tmp_name"];
            move_uploaded_file($sourceFile, $filePath);
        }

        $sql = "INSERT INTO `tasks` ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline ) ";
        $sql = $sql . "VALUES ( ?, ?, NOW( ), '1970-01-01', 0, ?, ?, ? )";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt,"iisss",$userData["id"], $taskItem["project"], $taskItem["name"], $fileName, $taskItem["date"]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $task_id = mysqli_insert_id($link);
            //header("Location: /?project_id=" . $taskItem["project"]);
            header("Location: /");
        }

    }
} else {
    $page_content = include_template("add.php", ["projectList" =>$projectList, "taskItem" => $taskItem]);
//print($page_content);
}

$layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
    "page_content" => $page_content, "active_project" => $active_project, "userData" => $userData]);
print($layout_content);
?>
