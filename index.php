<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
/*$projectList = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];*/
$projectList = [];
$projectList_current = 0;
/*$projectList_count = count($projectList); // количество страниц
$projectList_curPage = 0; // номер текущей страницы*/
$taskList = [];

/*$taskList = [
    0 => [
        "task" => "Собеседование в IT компании",
        //"completion" => "01.12.2018",
        "completion" => "20.09.2018",
        "category" => "Работа",
        "completed" => "Нет"
    ],
    1 => [
        "task" => "Выполнить тестовое задание",
        "completion" => "25.12.2018",
        "category" => "Работа",
        "completed" => "Нет"
    ],
    2 => [
        "task" => "Сделать задание первого раздела",
        "completion" => "21.12.2018",
        "category" => "Учеба",
        "completed" => "Да"
    ],
    3 => [
        "task" => "Встреча с другом",
        "completion" => "22.12.2018",
        "category" => "Входящие",
        "completed" => "Нет"
    ],
    4 => [
        "task" => "Купить корм для кота",
        "completion" => "Нет",
        "category" => "Домашние дела",
        "completed" => "Нет"
    ],
    5 => [
        "task" => "Заказать пиццу",
        "completion" => "Нет",
        "category" => "Домашние дела",
        "completed" => "Нет"
    ]
];*/

$taskCurrent = 0; //Порядковый номер выбранного чекбокса

function summTask($ListTasks, $projectId) {
    $summItems = 0;

    foreach ($ListTasks as $key => $value) {
        if($value["id_project"] == $projectId) {
            $summItems ++;
        }
    }

    return $summItems;
}

// Если разница дат текущей и введенной меньше или равно 24 часам возвращает "task--important"
function isImportant($checking_date) {
    $marker = "";
    //if (is_numeric(strtotime($checking_date))) {
    if ($checking_date !="1970-01-01 00:00:00") {
        $checking_timestamp = strtotime($checking_date);
        $now = time();
        $checking_timestamp = floor(($checking_timestamp - $now)/3600);
        if ($checking_timestamp <= 24) {
            $marker = "task--important";
        }
    }
    return $marker;
}

// Подключение к базе данных
$link = mysqli_connect("localhost", "root", "", "doingsdone");
$clientId = 1;
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
    $sql = "SELECT tasks.id,tasks.id_project,tasks.date_create,tasks.date_completion,tasks.`status`,tasks.task_name,";
    $sql = $sql . "tasks.file_name,tasks.date_deadline ";
    $sql = $sql . "FROM tasks WHERE tasks.id_user = ? ";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $clientId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $taskList = mysqli_fetch_all($res,MYSQLI_ASSOC);

    /*$res = mysqli_prepare($link, $sql);
    $stmt = db_get_prepare_stmt($link, $sql, [$clientId]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $taskList = mysqli_fetch_all($res, MYSQLI_ASSOC);*/

    $sql = "SELECT projects.id,projects.project_name FROM projects WHERE projects.id_user = ? ";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $clientId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projectList = mysqli_fetch_all($res,MYSQLI_ASSOC);
}

function showDate($DateTime) {
    global $dateFormat;
    if($DateTime == "1970-01-01 00:00:00") {
        $showDate = "";
    } else {
        $showDate = date_create($DateTime);
        $showDate = date_format($showDate, $dateFormat);
    }
    return $showDate;
}

// Конец подключения к БД
$page_content = include_template("index.php", ["taskList" =>$taskList,
    "show_complete_tasks" => $show_complete_tasks]);
//print($page_content);
$layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
    "projectList_current" => $projectList_current, "taskList" => $taskList, "page_content" => $page_content]);

print($layout_content);
?>
