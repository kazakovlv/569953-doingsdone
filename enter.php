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
                    header("Location: /index.php");
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
    $page_content = include_template("enter.php", ["form" => $form]);
} else {
    $page_content = include_template("enter.php", ["errors" => $errors, "form" => $form]);
}
$layout_content = include_template("layoutregistration.php",  ["title" => $title, "page_content" => $page_content]);
print($layout_content);
?>
