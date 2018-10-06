<?php
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU');
$dateFormat = "d.m.Y";
require_once("functions.php");
$title = "Дела в порядке";

$link = mysqli_connect("localhost", "root", "", "doingsdone");
$required = ["email", "password", "name"];
foreach ($required as $key) {
    $form[$key] = "";
}

$errors = [];

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

        $email = mysqli_real_escape_string($link, $form["email"]);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors["email"] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            if (empty($errors)) {
                $password = password_hash($form["password"], PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (registration_date, email, user_name, `password` ) VALUES ( NOW( ), ?, ?, ? )";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt,"sss",$form["email"],$form["name"], $password);
                $res = mysqli_stmt_execute($stmt);

                if ($res && empty($errors)) {
                    header("Location: /enter.php");
                    exit();
                }
            }
        }
    }
}
if (empty($errors)) {
    $page_content = include_template("adduser.php", ["form" => $form]);
} else {
    $page_content = include_template("adduser.php", ["errors" => $errors, "form" => $form]);
}

$layout_content = include_template("layoutregistration.php",  ["title" => $title, "page_content" => $page_content]);

print($layout_content);
?>
