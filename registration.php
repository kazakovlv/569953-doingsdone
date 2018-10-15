<?php
require_once("ini.php"); //Подключаем общие переменные
require_once("functions.php");
require_once("db_connect.php"); //Подключаем базу данных, при ошибке подключения получаем сообщение

$required = ["email", "password", "name"];
foreach ($required as $key) {
    $form[$key] = "";
}

$errors = []; //Ошибки регистрации

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    goto output;
}

$form = $_POST;
foreach ($required as $key) {
    if (empty($form[$key])) {
        $errors[$key] = 'Это поле надо заполнить !';
    }
}
$form["contacts"] = htmlspecialchars($form["contacts"]);
if (!filter_var($form["email"], FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = 'Ошибка заполнения поля email';
}
//$form["email"] = mysqli_real_escape_string($link, $form["email"]);
$email = $form["email"];
$sql = "SELECT id FROM users WHERE email = '$email'";
$res = mysqli_query($link, $sql);
if (mysqli_num_rows($res) > 0) {
    $errors["email"] = 'Пользователь с этим email уже зарегистрирован';
    goto output;
}

if (empty($errors)) {
    $password = password_hash($form["password"], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (registration_date, email, user_name, `password`, contacts) VALUES ( NOW( ), ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $form["email"], $form["name"], $password, $form["contacts"]);
    $res = mysqli_stmt_execute($stmt);

    if ($res && empty($errors)) {
        header("Location: /auth.php");
        exit();
    }
}

output:
if (empty($errors)) {
    $page_content = include_template("adduser.php", ["form" => $form]);
} else {
    $page_content = include_template("adduser.php", ["errors" => $errors, "form" => $form]);
}

$layout_content = include_template("layout_auth.php", ["title" => $title, "page_content" => $page_content]);

print($layout_content);

