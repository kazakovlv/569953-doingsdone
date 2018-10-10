<?php
function include_template($name, $data) {
    $name = "templates/" . $name;
    $result = "";

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require_once $name;

    $result = ob_get_clean();

    return $result;
}

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

/*Уведомления о предстоящих задачах [необязательно]*/
function getHotTasks($userId, $userName) {
    global $link;
    $letter = false;
    $sql = "SELECT tasks.task_name, tasks.date_deadline FROM tasks WHERE ";
    $sql = $sql . "tasks.id_user = ? AND tasks.`status` = 0 AND tasks.date_deadline != \"1970-01-01 00:00:00\" ";
    $sql = $sql . "AND tasks.date_deadline <= ( NOW( ) + INTERVAL 1 HOUR ) ORDER BY date_deadline";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projectListHot = mysqli_fetch_all($res,MYSQLI_ASSOC);
    if (count($projectListHot) > 0) {
        $letter = "Уважаемый/ая " . $userName . " !\n";
        switch (count($projectListHot)) {
            case 1:
                $letter = $letter .  "Обращаем внимание на сроки выполнения задания:\n";
                break;
            default:
                $letter = $letter .  "Обращаем внимание на сроки выполнения ". count($projectListHot) ." заданий:\n";
                break;
        }
        foreach ($projectListHot as $key => $value) {
            $showDate = date_create($value["date_deadline"]);
            $showDate = date_format($showDate, "d.m.Y H:i:s");
            $letter .= "У вас запланирована задача \"" . $value["task_name"] . "\" на " . $showDate  . "\n";
        }
    }
    return $letter;
}

function sendLetters() {
    global $link;
    $sql = "SELECT Count( tasks.id ) AS Count,users.id,users.user_name,users.email FROM tasks ";
    $sql = $sql . "LEFT JOIN users ON tasks.id_user = users.id WHERE tasks.`status` = 0 ";
    $sql = $sql . "AND tasks.date_deadline != \"1970-01-01 00:00:00\" AND tasks.date_deadline <= ( NOW( ) + INTERVAL 1 HOUR ) ";
    $sql = $sql . "GROUP BY users.id";
    $res = mysqli_query($link, $sql);
    $res = mysqli_fetch_all($res,MYSQLI_ASSOC);
    foreach ($res as $key => $value) {
        $letter = getHotTasks($value["id"], $value["user_name"]);
        $headers  = "Content-type: text/html; charset=windows-1251 \r\n";
        $headers .= "From: От кого письмо <info@doingsdone.com>\r\n";
        $headers .= "Reply-To: info@doingsdone.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $subject = "Уведомление от сервиса \"Дела в порядке\".";
        mail($value["email"], $subject, $letter, $headers);
    }
}
/*Конец Уведомления о предстоящих задачах [необязательно]*/
/*Поиск [необязательно]*/
function searchTasks($userId, $textSearch) {
    global $link;
    $tasks = [];
    $textSearch = trim($textSearch);
    if (empty($textSearch)) {
        return $tasks;
    }
    $sql = "SELECT tasks.id,tasks.id_project,tasks.date_create,tasks.date_completion,tasks.`status`,tasks.task_name,";
    $sql = $sql . "tasks.file_name,tasks.date_deadline ";
    $sql = $sql . "FROM tasks WHERE tasks.id_user = ? AND MATCH ( task_name ) AGAINST ( ? ";
    $sql = $sql . " IN BOOLEAN MODE) ORDER BY tasks.date_deadline ASC";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"is", $userId, $textSearch);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $tasks = mysqli_fetch_all($res,MYSQLI_ASSOC);
    return $tasks;
}

function is_fake($userId, $projectId) {
    global $link;
    $answer = false;
    $sql = "SELECT projects.id FROM projects WHERE projects.id_user = " . $userId . " AND projects.id = " . $projectId;
    $res = mysqli_query($link, $sql);
    $res = mysqli_fetch_all($res,MYSQLI_ASSOC);
    if (count($res) == 0){
        $answer = true;
    }
    return $answer;
}

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

function is_valid_date($date) {
    $ts_date = strtotime($date);
    $new_date = date("Y-m-d", $ts_date);
    if ($date == $new_date) {
        return true;
    } else {
        return false;
    }
}

function switch_task_status($link, $task_id) {
    $answer = false;
    $sql = "SELECT tasks.`status` FROM tasks WHERE tasks.id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt,"i", $task_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $res = mysqli_fetch_all($res,MYSQLI_ASSOC);

    if (count($res) == 1){
        if ($res[0]["status"] == 0) {
            $sql = "UPDATE tasks SET `status` = 1, tasks.date_completion = NOW() WHERE id = ?";
        } else {
            $sql = "UPDATE tasks SET `status` = 0, tasks.date_completion = '1970-01-01' WHERE id = ?";
        }
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt,"i", $task_id);
        mysqli_stmt_execute($stmt);
        $answer = true;
    }
    return $answer;
}

?>
