<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
$filter_task = [];
session_start();

if (!isset($_SESSION["user"])) {
    $is_get = 0;
    if (isset($_GET['task_filter'])) {
        $cookie_name = "task_filter";
        $cookie_value = $_GET['task_filter'];
        $cookie_expire = strtotime("+1 days");
        $cookie_path = "/";
        setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path);
        $is_get++;
    }
    if (isset($_GET['project_id'])) {
        $cookie_name = "project_id";
        $cookie_value = $_GET['project_id'];
        $cookie_expire = strtotime("+1 days");
        $cookie_path = "/";
        setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path);
        $is_get++;
    }
    if ($is_get > 0) {
        header("Location: /auth.php");
        exit();
    }
    $page_content = include_template("guest.php", []);
    $layout_content = include_template("layout.php",  ["title" => $title, "page_content" => $page_content]);
    print($layout_content);
    exit();
}
$userData = $_SESSION["user"];

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$show_complete_tasks = 1;
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
        if (isset($_GET['task_filter'])) {

            switch ($_GET['task_filter']) {
                case "today":
                    $projectFilter .= " AND tasks.date_deadline = CURDATE()";
                    break;
                case "tomorrow":
                    $projectFilter .= " AND tasks.date_deadline = DATE_ADD( CURDATE(),Interval 1 DAY)";
                    break;
                case "overdue":
                    $projectFilter .= " AND tasks.date_deadline <= DATE_ADD( CURDATE( ), INTERVAL - 1 DAY ) AND tasks.`status` = 0";
                    break;
            }
        }

        $active_project = $_GET['project_id'];

    }

    if (isset($_GET['task_id']) && isset($_GET['check'])) {
        switch_task_status($link, $_GET['task_id']);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Вылидация формы
        $search_text = $_POST["search_text"];
        $search_text = htmlspecialchars($search_text);
        $projectFilter .= " AND MATCH ( task_name ) AGAINST ('$search_text' IN BOOLEAN MODE)";
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
