<?php
/**
 * Возвращает html контекст заполненный данными из переменных
 * @param $name string Имя шаблона
 * @param $data array Массив переменных для заполнения
 * @return false|string
 */
function include_template($name, $data)
{
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

/**
 * Подсчитывает количество задач в указанном проекте
 * @param $ListTasks array Массив задач
 * @param $projectId integer Идентификатор прпоекта
 * @return int Количество задач
 */
function summTask($ListTasks, $projectId)
{
    $summ_items = 0;

    foreach ($ListTasks as $key => $value) {
        if ((int)$value["id_project"] === (int)$projectId) {
            $summ_items++;
        }
    }

    return $summ_items;
}

// Если разница дат текущей и введенной меньше или равно 24 часам возвращает "task--important"
/**
 * Проверяет срок исполнения задачи, если меньше 24 часов, то она важная
 * @param $checking_date Проверяемая дата
 * @return string Возвращает класс task--important, если дата меньше 24 часов
 */
function isImportant($checking_date)
{
    $marker = "";
    if ($checking_date != "1970-01-01 00:00:00") {
        $checking_timestamp = strtotime($checking_date);
        $now = time();
        $checking_timestamp = floor(($checking_timestamp - $now) / 3600);
        if ($checking_timestamp <= 24) {
            $marker = "task--important";
        }
    }
    return $marker;
}

/**
 * Форматирует дату, если она 1970-01-01 00:00:00 возвращает пустое место
 * @param $dateFormat string Формат
 * @param $DateTime Дата
 * @return DateTime|false|string
 */
function showDate($dateFormat, $DateTime)
{
    if ((string)$DateTime === "1970-01-01 00:00:00") {
        $showDate = "";
        return $showDate;
    }
    $showDate = date_create($DateTime);
    $showDate = date_format($showDate, $dateFormat);
    return $showDate;
}

/*Уведомления о предстоящих задачах [необязательно]*/
/**
 * Возвращает тело письма
 * @param $link mysqli Параметры соединения
 * @param $userId integer Идентификатор пользователя
 * @param $userName string Имя пользователя
 * @return bool|string Текст или false в случае неудачи
 */
function getHotTasks($link, $userId, $userName)
{
    $letter = false;
    $sql = "SELECT tasks.task_name, tasks.date_deadline FROM tasks WHERE ";
    $sql = $sql . "tasks.id_user = ? AND tasks.`status` = 0 AND tasks.date_deadline != \"1970-01-01 00:00:00\" ";
    $sql = $sql . "AND tasks.date_deadline <= ( NOW( ) + INTERVAL 1 HOUR ) ORDER BY date_deadline";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projectListHot = mysqli_fetch_all($res, MYSQLI_ASSOC);
    if (count($projectListHot) > 0) {
        $letter = "Уважаемый/ая " . $userName . " !\n";
        switch (count($projectListHot)) {
            case 1:
                $letter = $letter . "Обращаем внимание на сроки выполнения задания:\n";
                break;
            default:
                $letter = $letter . "Обращаем внимание на сроки выполнения " . count($projectListHot) . " заданий:\n";
                break;
        }
        foreach ($projectListHot as $key => $value) {
            $showDate = date_create($value["date_deadline"]);
            $showDate = date_format($showDate, "d.m.Y H:i:s");
            $letter .= "У вас запланирована задача \"" . $value["task_name"] . "\" на " . $showDate . "\n";
        }
    }
    return $letter;
}

/**
 * Функция отправляет письма пользователям, сроки исполнения который меньше часа
 * @param $link mysqli
 */
function sendLetters($link)
{
    $sql = "SELECT Count( tasks.id ) AS Count,users.id,users.user_name,users.email FROM tasks ";
    $sql = $sql . "LEFT JOIN users ON tasks.id_user = users.id WHERE tasks.`status` = 0 ";
    $sql = $sql . "AND tasks.date_deadline != \"1970-01-01 00:00:00\" AND tasks.date_deadline <= ( NOW( ) + INTERVAL 1 HOUR ) ";
    $sql = $sql . "GROUP BY users.id";
    $res = mysqli_query($link, $sql);
    $res = mysqli_fetch_all($res, MYSQLI_ASSOC);
    foreach ($res as $key => $value) {
        $letter = getHotTasks($link, $value["id"], $value["user_name"]);
        $headers = "Content-type: text/html; charset=windows-1251 \r\n";
        $headers .= "From: От кого письмо <info@doingsdone.com>\r\n";
        $headers .= "Reply-To: info@doingsdone.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $subject = "Уведомление от сервиса \"Дела в порядке\".";
        mail($value["email"], $subject, $letter, $headers);
    }
}

/*Конец Уведомления о предстоящих задачах [необязательно]*/
/*Поиск [необязательно]*/
/**
 * Функция поиска
 * @param $link mysqli Параметры подключения
 * @param $userId integer Идентификатор пользователя
 * @param $textSearch string Строка из слов для поиска
 * @return array|null Возвращает массив найденного
 */
function searchTasks($link, $userId, $textSearch)
{
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
    mysqli_stmt_bind_param($stmt, "is", $userId, $textSearch);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $tasks;
}

/**
 * Функция проверяет дата ли это
 * @param $date
 * @return bool
 */
function is_valid_date($date)
{
    $answer = false;
    $ts_date = strtotime($date);
    $new_date = date("Y-m-d", $ts_date);
    if ($date === $new_date) {
        $answer = true;
    }
    return $answer;
}

/**
 * Функия меняет значение исполнения задачи на противоположный
 * @param $link mysqli параметры подключения
 * @param $task_id integer Идентификатор задачи
 * @param $user_id integer Идентификатор пользователя
 * @return bool
 */
function switch_task_status($link, $task_id, $user_id)
{
    $answer = false;
    $sql = "SELECT tasks.`status` FROM tasks WHERE tasks.id = ? AND tasks.id_user = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $task_id, $user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $res = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $sql_where[0] = "UPDATE tasks SET `status` = 1, tasks.date_completion = NOW() WHERE id = ?";
    $sql_where[1] = "UPDATE tasks SET `status` = 0, tasks.date_completion = '1970-01-01' WHERE id = ?";
    if (count($res) == 1) {
        /*
         if ($res[0]["status"] == 0) {
            $sql = "UPDATE tasks SET `status` = 1, tasks.date_completion = NOW() WHERE id = ?";
        } else {
            $sql = "UPDATE tasks SET `status` = 0, tasks.date_completion = '1970-01-01' WHERE id = ?";
        }
        */
        $sql = $sql_where[$res[0]["status"]];
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "i", $task_id);
        mysqli_stmt_execute($stmt);
        $answer = true;
    }
    return $answer;
}

/**
 * Возвращает дополнительные условия отбора
 * @param $task_filter string
 * @return string
 */
function get_task_filter($task_filter)
{
    $filter_array["today"] = " AND tasks.date_deadline = CURDATE()";
    $filter_array["tomorrow"] = " AND tasks.date_deadline = DATE_ADD( CURDATE(),Interval 1 DAY)";
    $filter_array["overdue"] = " AND tasks.date_deadline <= DATE_ADD( CURDATE(), INTERVAL - 1 DAY )";
    $filter_array["all"] = "";
    /*
    $dateFilter = "";
    switch ($task_filter) {
        case "today":
            $dateFilter .= " AND tasks.date_deadline = CURDATE()";
            break;
        case "tomorrow":
            $dateFilter .= " AND tasks.date_deadline = DATE_ADD( CURDATE(),Interval 1 DAY)";
            break;
        case "overdue":
            $dateFilter .= " AND tasks.date_deadline <= DATE_ADD( CURDATE(), INTERVAL - 1 DAY )";
            break;
    }*/
    $dateFilter = $filter_array[$task_filter];
    return $dateFilter;
}


/**
 * Возвращает true если такого Названия проекта нет, в противном случае false
 *
 * @param $link mysqli Ресурс соединения
 * @param $user_id integer Иднтификатор пользователя
 * @param $project_name string Наименование проекта
 *
 * @return bool
 */
function check_project_name($link, $user_id, $project_name)
{
    $answer = false;
    $project_name_upper = strtoupper($project_name);
    $sql = "SELECT id FROM projects WHERE id_user = ?  AND UPPER(project_name) = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $project_name_upper);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $res = mysqli_fetch_all($res, MYSQLI_ASSOC);

    if (count($res) == 0) {
        $answer = true;
    }
    return $answer;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
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

/**
 * Возвращает true если это фильтры по датам задач
 *
 * @param $task_filter string Наименование проекта
 *
 * @return bool
 */
function is_task_filter($task_filter)
{
    $answer = false;
    $tmp = htmlspecialchars($task_filter);
    if (!empty($tmp) && ($tmp === "all" OR $tmp === "today" OR $tmp === "tomorrow" OR $tmp === "overdue")) {
        $answer = true;
    }
    return $answer;
}

/**
 * Возвращает true если задача принадлежит пользователю
 *
 * @param $link mysqli Ресурс соединения
 * @param $user_id integer Идентификатор пользователя
 * @param $task_id integer Идентификатор проекта
 *
 * @return bool
 */
function is_user_task($link, $user_id, $task_id)
{
    $answer = false;
    if (is_numeric($task_id) && is_numeric($user_id)) {
        $sql = "SELECT id FROM tasks WHERE id_user = ? AND id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $task_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $res = mysqli_fetch_all($res, MYSQLI_ASSOC);

        if (count($res) == 1) {
            $answer = true;
        }
    }
    return $answer;
}

/**
 * Возвращает true если проект принадлежит пользователю
 *
 * @param $link mysqli Ресурс соединения
 * @param $user_id integer Идентификатор пользователя
 * @param $project_id integer Идентификатор проекта
 *
 * @return bool
 */
function is_user_project($link, $user_id, $project_id)
{
    $answer = false;
    if (is_numeric($user_id) && is_numeric($project_id)) {
        $sql = "SELECT projects.id FROM projects WHERE projects.id_user = ? AND projects.id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $project_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $res = mysqli_fetch_all($res, MYSQLI_ASSOC);

        if (count($res) === 1) {
            $answer = true;
        }
    }
    return $answer;
}
