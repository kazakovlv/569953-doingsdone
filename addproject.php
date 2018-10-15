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

$projectItem = null;
$active_project = null;

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $page_content = include_template("addproject.php", []);
    goto end_of_if;
}

/*Проверка на заполнение поля*/
$projectItem = $_POST["name_project"];
$projectItem = htmlspecialchars($projectItem);
if (empty($projectItem)) {
    $errors = 'Это поле надо заполнить';
    $page_content = include_template("addproject.php", ["errors" => $errors]);
    goto end_of_if;
}

/*Проверка на существующий проект*/
if (!check_project_name($link, $userData["id"], $projectItem)) {
    $errors = 'Проект с таким названием уже существует';
    $page_content = include_template("addproject.php", ["errors" => $errors]);
    goto end_of_if;
}
$sql = "INSERT INTO projects (id_user, project_name) VALUES (?, ?);";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "is", $userData["id"], $projectItem);
$res = mysqli_stmt_execute($stmt);
if ($res) {
    $project_id = mysqli_insert_id($link);
    header("Location: /index.php");
}

end_of_if :

mysqli_set_charset($link, "utf8");
$sql = "SELECT projects.id,projects.project_name,Count( tasks.id ) AS task_count FROM projects ";
$sql = $sql . "LEFT JOIN tasks ON tasks.id_project = projects.id ";
$sql = $sql . "WHERE projects.id_user = ? GROUP BY projects.id ORDER BY 2";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $userData["id"]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$projectList = mysqli_fetch_all($res, MYSQLI_ASSOC);

$layout_content = include_template("layout.php", ["title" => $title, "projectList" => $projectList,
    "page_content" => $page_content, "active_project" => $active_project, "userData" => $userData]);
print($layout_content);

