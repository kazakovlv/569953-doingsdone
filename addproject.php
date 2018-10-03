<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$clientId = 1;
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
$projectList = [];
$projectItem = null;
$link = mysqli_connect("localhost", "root", "", "doingsdone");

if (!$link) {
    $error = mysqli_connect_error();
    die($error);
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $projectItem = $_POST["name_project"];
        if (empty($projectItem)) {
            $errors = 'Это поле надо заполнить';
            $page_content = include_template("addproject.php", ["errors" => $errors]);
        } else {
            $sql = "INSERT INTO projects (id_user, project_name) VALUES (?, ?);";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt,"is",$clientId,$projectItem );
            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                $project_id = mysqli_insert_id($link);
                header("Location: /");
            }
        }
    } else {
        $page_content = include_template("addproject.php", []);
    }

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
$layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
    "page_content" => $page_content]);
print($layout_content);
?>

