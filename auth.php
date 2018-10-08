<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";
$link = mysqli_connect("localhost", "root", "", "doingsdone");

$required = ["email", "password"];
foreach ($required as $key) {
    $form[$key] = "";
}

$errors = [];
session_start();

if (!$link) {
    $error = mysqli_connect_error();
    die($error);
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $form = $_POST;
        foreach ($required as $key) {
            if (empty($form[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);
        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if ($user == null) {
            $errors["email"] = "Такой пользователь не найден";
        } else {
            if (!count($errors)) {
                if (password_verify($form["password"], $user["password"])) {
                    $_SESSION["user"] = $user;
                    $headerStr = "Location: /index.php";
                    $getParam = [];
                    if (isset($_COOKIE['project_id'])) {
                        $getParam["project_id"] = $_COOKIE['project_id'];
                        setcookie("project_id", null, -1, "/");
                    }
                    if (isset($_COOKIE['task_filter'])) {
                        $getParam["task_filter"] = $_COOKIE['task_filter'];
                        setcookie("project_id", null, -1, "/");
                    }
                    if (!empty($getParam)) {
                        $headerStr .= "?" . http_build_query($getParam);
                    }
                    header($headerStr);
                    exit();
                }
                else {
                    $errors["password"] = "Неверный пароль";
                }
            }
        }
    }
}

if (empty($errors)) {
    $page_content = include_template("auth.php", ["form" => $form]);
} else {
    $page_content = include_template("auth.php", ["errors" => $errors, "form" => $form]);
}
$layout_content = include_template("layout_auth.php",  ["title" => $title, "page_content" => $page_content]);
print($layout_content);
?>
