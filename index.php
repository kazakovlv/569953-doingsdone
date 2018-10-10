<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
$filter_task = [];
$all_filter_param = []; // Коллекция параметров фильтрации задач

session_start();

if (!isset($_SESSION["user"])) {
    $is_get = 0;
    if (isset($_GET['task_filter'])) {
        $cookie_expire = strtotime("+1 days");
        setcookie("task_filter", $_GET['task_filter'], $cookie_expire, "/");
        $is_get++;
    }
    if (isset($_GET['project_id'])) {
        $cookie_expire = strtotime("+1 days");
        setcookie("project_id", $_GET['project_id'], $cookie_expire, "/");
        $is_get++;
    }
    if (isset($_GET['show_completed'])) {
        $cookie_expire = strtotime("+1 days");
        setcookie("show_completed", $_GET['show_completed'], $cookie_expire, "/");
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
//$show_complete_tasks = rand(0, 1);
$show_complete_tasks = 0;
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Вылидация формы
        $search_text = $_POST["search_text"];
        $search_text = htmlspecialchars($search_text);
        $projectFilter .= " AND MATCH ( task_name ) AGAINST ('$search_text' IN BOOLEAN MODE)";
    }
    if ($_SERVER["REQUEST_METHOD"] == "GET" AND empty($_GET)) {
        setcookie("project_id", null, -1, "/");
        setcookie("task_filter", null, -1, "/");
        if (isset($_COOKIE['show_completed'])) {
            $show_complete_tasks = $_COOKIE['show_completed'];
        }
    } else {
        // Определение фильтра задач по проектам
        if (isset($_GET['project_id'])) {
            if (empty($_GET['project_id']) OR !is_numeric($_GET['project_id']) OR is_fake($userData["id"], $_GET['project_id'])) {
                header("HTTP/1.1 404 Not Found");
                $projectFilterError = true;
            }
            $all_filter_param['project_id'] =  $_GET['project_id']; //Добавляем фильтрацию по проекту
            $projectFilter = " AND id_project = " . $_GET['project_id'];
            $cookie_expire = strtotime("+1 days");
            setcookie("project_id", $_GET['project_id'], $cookie_expire, "/");
            $active_project = $_GET['project_id'];
        }

        if (isset($_GET['task_filter'])) {
            $all_filter_param['task_filter'] =  $_GET['task_filter']; //Добавляем фильтрацию датам выполнения задач
            $projectFilter .= get_task_filter($_GET['task_filter']);
            $cookie_expire = strtotime("+1 days");
            setcookie("task_filter", $_GET['task_filter'], $cookie_expire, "/");
        }

        if (isset($_GET['show_completed'])) {
            $show_complete_tasks = $_GET['show_completed'];
            $cookie_expire = strtotime("+1 days");
            setcookie("show_completed", $_GET['show_completed'], $cookie_expire, "/");

            if (isset($_COOKIE['project_id'])) {
                $projectFilter = " AND id_project = " . $_COOKIE['project_id'];
                $all_filter_param['project_id'] =  $_COOKIE['project_id']; //Добавляем фильтрацию по проекту
                $active_project = $_COOKIE['project_id'];
            }

            if (isset($_COOKIE['task_filter'])) {
                $all_filter_param['task_filter'] =  $_COOKIE['task_filter']; //Добавляем фильтрацию датам выполнения задач
                $projectFilter .= get_task_filter($_COOKIE['task_filter']);
            }
        }

        if (isset($_GET['task_id']) && isset($_GET['check'])) {
            switch_task_status($link, $_GET['task_id']);
            if (isset($_COOKIE['project_id'])) {
                $projectFilter = " AND id_project = " . $_COOKIE['project_id'];
                $all_filter_param['project_id'] =  $_COOKIE['project_id']; //Добавляем фильтрацию по проекту
                $active_project = $_COOKIE['project_id'];
            }

            if (isset($_COOKIE['task_filter'])) {
                $all_filter_param['task_filter'] =  $_COOKIE['task_filter']; //Добавляем фильтрацию датам выполнения задач
                $projectFilter .= get_task_filter($_COOKIE['task_filter']);
            }

            if (isset($_COOKIE['show_completed'])) {
                $show_complete_tasks = $_COOKIE['show_completed'];
                if ($show_complete_tasks == 0) {
                    $projectFilter .= "AND tasks.`status` = 0";
                }
            }
        }
    }

    $all_filter_param['show_completed'] = $show_complete_tasks;//Добавляем фильтрацию показа выполненных задач

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
    if (isset($all_filter_param["task_filter"])){
        $filter_task = $all_filter_param["task_filter"];
        $page_content = include_template("index.php", ["taskList" => $taskList,
            "show_complete_tasks" => $show_complete_tasks, "active_project" => $active_project,
            "filter_task" => $filter_task]);
    } else {
        $page_content = include_template("index.php", ["taskList" => $taskList,
            "show_complete_tasks" => $show_complete_tasks, "active_project" => $active_project]);
    }
}

//print($page_content);
if (isset($all_filter_param["task_filter"])) {
    $layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
        "taskList" => $taskList, "page_content" => $page_content, "active_project" => $active_project,
        "filter_task" => $filter_task, "userData" => $userData]);
} else {
    $layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
        "taskList" => $taskList, "page_content" => $page_content, "active_project" => $active_project,
        "userData" => $userData]);
}
print($layout_content);
?>
