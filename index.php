<?php
date_default_timezone_set('Europe/Moscow');
require_once("functions.php");
$title = "Дела в порядке";
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projectList = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$projectList_count = count($projectList); // количество страниц
$projectList_curPage = 0; // номер текущей страницы
$taskList = [
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
];

$taskCurrent = 0; //Порядковый номер выбранного чекбокса

function summTask($ListTasks, $taskName) {
    $summItems = 0;

    foreach ($ListTasks as $key => $value) {
        if($value['category'] == $taskName) {
            $summItems ++;
        }
    }

    return $summItems;
}

// Если разница дат текущей и введенной меньше или равно 24 часам возвращает "task--important"
function isImportant($checking_date) {
    $marker = "";
    if (is_numeric(strtotime($checking_date))) {
        $checking_timestamp = strtotime($checking_date);
        $now = time();
        $checking_timestamp = floor(($checking_timestamp - $now)/3600);
        if ($checking_timestamp <= 24) {
            $marker = "task--important";
        }
    }
    return $marker;
}

$page_content = include_template("index.php", ["taskList" =>$taskList,
    "show_complete_tasks" => $show_complete_tasks, "taskCurrent" => $taskCurrent]);

$layout_content = include_template("layout.php",  ["title" => $title, "projectList" => $projectList,
    "projectList_curPage" => $projectList_curPage, "projectList_count" => $projectList_count,
    "taskList" => $taskList, "page_content" => $page_content]);

print($layout_content);
?>
