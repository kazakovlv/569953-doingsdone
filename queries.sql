/*Заполнение таблиц users*/
/*password*/
INSERT INTO users ( users.registration_date, users.email, users.user_name, users.`password` )
VALUES
	( NOW( ), 'konstantin@gmail.com', 'Константин', '$2y$10$0wKoJOJNeJDed99x48pLHurUZOIM11zrdWdi1P6W4p/1bY8dyC0ee' );
/*password1*/
INSERT INTO users ( users.registration_date, users.email, users.user_name, users.`password` )
VALUES
	( NOW( ), 'valera@gmail.com', 'Валера', '$2y$10$FU3a6ea6HB1sBw5tO8MF1OOeEeEt1Jr2T03Yy1NHDWkDWUV0tzIZO' );
/*password3*/
INSERT INTO users ( users.registration_date, users.email, users.user_name, users.`password` )
VALUES
	( NOW( ), 'sergey@gmail.com', 'Сергей', '$2y$10$m7Y.XV5wL6gQaoNWb2yLdecR/77WXKw/o1EQikt.59OkpVgyM1jUe' );

/*Заполнение таблицы projects*/
INSERT INTO projects (id_user, project_name) VALUES (1, "Входящие");
INSERT INTO projects (id_user, project_name) VALUES (1, "Учеба");
INSERT INTO projects (id_user, project_name) VALUES (1, "Работа");
INSERT INTO projects (id_user, project_name) VALUES (1, "Домашние дела");
INSERT INTO projects (id_user, project_name) VALUES (1, "Авто");

INSERT INTO projects (id_user, project_name) VALUES (2, "Сайты");
INSERT INTO projects (id_user, project_name) VALUES (2, "Программы PHP");
INSERT INTO projects (id_user, project_name) VALUES (2, "Работа");
INSERT INTO projects (id_user, project_name) VALUES (2, "Домашние дела");
INSERT INTO projects (id_user, project_name) VALUES (2, "Мото");

INSERT INTO projects (id_user, project_name) VALUES (3, "Серверы");
INSERT INTO projects (id_user, project_name) VALUES (3, "Виртуализаци");
INSERT INTO projects (id_user, project_name) VALUES (3, "Ноутбуки");
INSERT INTO projects (id_user, project_name) VALUES (3, "Ремонт");
INSERT INTO projects (id_user, project_name) VALUES (3, "Роботизация");

/*Заполнение таблицы tasks*/
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (1, 1, 1, '2018-09-23 14:38:04', '1970-01-01 00:00:00', b'0', 'Техническое задание на забор', 'File1.txt', '1970-01-01 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (2, 1, 2, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Изучение оператора INSERT', 'File2.txt', '2018-10-17 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (3, 1, 2, '2018-09-23 22:41:30', '2018-09-24 11:10:33', b'1', 'Изучение оператора UPDATE', 'File3.txt', '2018-09-17 23:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (4, 1, 3, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Погрузка дивана и стиральной машины', 'File4.txt', '2018-09-25 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (5, 1, 3, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Разгрузка мебели', 'File5.txt', '2018-09-18 02:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (6, 1, 4, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Помыть окна', '', '2018-10-20 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (7, 1, 4, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Пропылесосить', '', '2018-10-21 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (8, 1, 4, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Выбросить мусор', 'File6.txt', '2018-10-22 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (9, 2, 6, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Разметка сайта', 'File7.txt', '2018-10-10 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (10, 2, 7, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Изучение работы с MySQL', 'File8.txt', '2018-10-12 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (11, 2, 7, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Изучение циклов', 'File9.txt', '2018-10-17 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (12, 2, 9, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Покормить кота', '', '1970-01-01 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (13, 2, 9, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Убрать за котом', '', '1970-01-01 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (14, 2, 10, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Сменить цепь', '', '2018-10-20 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (15, 2, 10, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Починить тормоза', 'File10.txt', '2018-10-21 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (16, 3, 12, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Установка VMWare', 'File11.txt', '2018-10-10 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (17, 3, 12, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Клонирование Windows 2012', 'File12.txt', '2018-10-12 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (18, 3, 14, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Поклейка обоев', '', '2018-09-28 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (19, 3, 14, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Облицовка плиткой', '', '2018-09-27 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (20, 3, 14, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Выравнивание потолка', '', '2018-09-27 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (21, 3, 15, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Настройка робота', '', '1970-01-01 00:00:00');
INSERT INTO `tasks`(`id`, `id_user`, `id_project`, `date_create`, `date_completion`, `status`, `task_name`, `file_name`, `date_deadline`) VALUES (22, 3, 15, '2018-09-23 22:41:30', '1970-01-01 00:00:00', b'0', 'Роботизация выноса мусора', '', '1970-01-01 00:00:00');


/*Получить список из всех проектов для одного пользователя */
SELECT
	projects.id,
	projects.project_name 
FROM
	projects 
WHERE
	projects.id_user = 1;

/*Получить список из всех задач для одного проекта */
SELECT
	tasks.id,
	tasks.id_project,
	tasks.date_create,
	tasks.date_completion,
	tasks.`status`,
	tasks.task_name,
	tasks.file_name,
	tasks.date_deadline 
FROM
	tasks 
WHERE
	tasks.id_user = 1 
	AND tasks.id_project = 2;

/*Пометить задачу как выполненную */
UPDATE tasks 
SET tasks.`status` = 1,
tasks.date_completion = NOW( ) 
WHERE
	tasks.id = 3;
	
/*Пометить задачу как невыполненную */
UPDATE tasks 
SET tasks.`status` = 0,
tasks.date_completion = '1970-01-01' 
WHERE
	tasks.id = 3;
	
/*Получить все задачи для завтрашнего дня;*/
/* Подготовка*/
UPDATE tasks 
SET tasks.date_deadline = CURRENT_DATE ( ) + INTERVAL 1 DAY 
WHERE
	tasks.id = 4;
/*Выполнение*/
SELECT
	tasks.id,
	tasks.id_project,
	tasks.date_create,
	tasks.date_completion,
	tasks.`status`,
	tasks.task_name,
	tasks.file_name,
	tasks.date_deadline 
FROM
	tasks 
WHERE
	tasks.id_user = 1 
	AND tasks.date_deadline <=  CURRENT_DATE() + INTERVAL 1 DAY
ORDER BY tasks.date_deadline ASC;

/*обновить название задачи по её идентификатору*/
UPDATE tasks 
SET tasks.task_name = 'Погрузка дивана и стиральной машины' 
WHERE
	tasks.id = 4;

/*Уведомления о предстоящих задачах [необязательно]*/
/*
1. Выполнить SQL запрос на получение всех невыполненных задач у которых
дата выполнения больше или равна текущей дате/времени минус один час
2. Если у одного пользователя задач больше, чем одна, то объединить их названия
и даты в один список, который будет вставлен в уведомление
3. Сформировать для каждого найденного пользователя письмо, указав тему и текст сообщения
4. Отправить каждому найденному пользователю это письмо
*/
SELECT
	tasks.id,
	tasks.id_project,
	tasks.date_create,
	tasks.date_completion,
	tasks.`status`,
	tasks.task_name,
	tasks.file_name,
	tasks.date_deadline
FROM
	tasks
WHERE
	tasks.id_user = 1
	AND tasks.`status` = 0
	AND tasks.date_deadline != "1970-01-01 00:00:00"
	AND tasks.date_deadline <= ( NOW( ) + INTERVAL 1 HOUR )
ORDER BY date_deadline;
/*Список для рассылки*/
SELECT
	Count( tasks.id ) AS Count,
	users.id,
	users.user_name,
	users.email
FROM
	tasks
	LEFT JOIN users ON tasks.id_user = users.id
WHERE
	tasks.`status` = 0
	AND tasks.date_deadline != "1970-01-01 00:00:00"
	AND tasks.date_deadline <= ( NOW( ) + INTERVAL 1 HOUR )
GROUP BY
	users.id;

/*Поиск [необязательно]*/
SELECT
	tasks.id,
	tasks.id_project,
	tasks.date_create,
	tasks.date_completion,
	tasks.`status`,
	tasks.task_name,
	tasks.file_name,
	tasks.date_deadline
FROM
	tasks
WHERE
	tasks.id_user = 1
	AND MATCH ( task_name ) AGAINST ( 'оператора' IN BOOLEAN MODE)
ORDER BY
	tasks.date_deadline ASC;

/*Запрос проектов с подсчетом задач, входящих в него*/
SELECT
	projects.id,
	projects.project_name,
	Count( tasks.id ) AS task_count
FROM
	projects
	LEFT JOIN tasks ON tasks.id_project = projects.id
WHERE
	projects.id_user = 1
GROUP BY
	projects.id
ORDER BY 2;

/*Проверка есть ли такой projects.id у такого пользователя с id_user*/
SELECT
	projects.id
FROM
	projects
WHERE
	projects.id_user = 1
	AND projects.id = 1;

INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 1, NOW( ), '1970-01-01', 0, 'Техническое задание на забор', '', '2018-10-01' );
