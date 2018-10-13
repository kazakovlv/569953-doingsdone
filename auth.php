<?php
require_once("ini.php"); //Подключаем общие переменные
require_once("functions.php");
require_once("db_connect.php"); //Подключаем базу данных, при ошибке подключения получаем сообщение

$required = ["email", "password"];
foreach ($required as $key) {
    $form[$key] = "";
}

$errors = [];
session_start();

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
                //Проверка принадлежат ли COOKIE пользователю
                if (isset($_COOKIE['user_id'])) {
                    if ($_COOKIE['user_id'] == $user['id']) {
                        if (isset($_COOKIE['project_id'])) {
                            $getParam["project_id"] = $_COOKIE['project_id'];
                        }
                        if (isset($_COOKIE['task_filter'])) {
                            $getParam["task_filter"] = $_COOKIE['task_filter'];
                        }
                        if (isset($_COOKIE['show_completed'])) {
                            $getParam["show_completed"] = $_COOKIE['show_completed'];
                        }
                    }
                }

                setcookie("project_id", null, -1, "/");
                setcookie("task_filter", null, -1, "/");
                setcookie("show_completed", null, -1, "/");
                //Конец Проверки принадлежат ли COOKIE пользователю

                $cookie_expire = strtotime("+1 days");
                setcookie("user_id", $user['id'], $cookie_expire, "/");

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

if (empty($errors)) {
    $page_content = include_template("auth.php", ["form" => $form]);
} else {
    $page_content = include_template("auth.php", ["errors" => $errors, "form" => $form]);
}
$layout_content = include_template("layout_auth.php",  ["title" => $title, "page_content" => $page_content]);
print($layout_content);
?>
