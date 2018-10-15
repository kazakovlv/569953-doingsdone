<?php
$link = mysqli_connect($connection['host'],  $connection['user'], $connection['password'], $connection['database']);
if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template("db_error.php", ["error" => $error]);
    $layout_content = include_template("layout.php",  ["title" => $title, "page_content" => $page_content]);
    print($layout_content);
    exit();
} else {
    mysqli_set_charset($link, "utf8");
}

