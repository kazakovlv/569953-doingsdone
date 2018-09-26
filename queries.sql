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
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 1, NOW( ), '1970-01-01', 0, 'Техническое задание на забор', '', '2018-10-01' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 2, NOW( ), '1970-01-01', 0, 'Изучение оператора INSERT', '', '2018-10-17' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 2, NOW( ), '1970-01-01', 0, 'Изучение оператора UPDATE', '', '2018-10-18' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 3, NOW( ), '1970-01-01', 0, 'Погрузка мебели', '', '2018-10-17' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 3, NOW( ), '1970-01-01', 0, 'Разгрузка мебели', '', '2018-10-18' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 4, NOW( ), '1970-01-01', 0, 'Помыть окна', '', '2018-10-20' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 4, NOW( ), '1970-01-01', 0, 'Пропылесосить', '', '2018-10-21' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 1, 4, NOW( ), '1970-01-01', 0, 'Выбросить мусор', '', '2018-10-22' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 2, 6, NOW( ), '1970-01-01', 0, 'Разметка сайта', '', '2018-10-10' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 2, 7, NOW( ), '1970-01-01', 0, 'Изучение работы с MySQL', '', '2018-10-12' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 2, 7, NOW( ), '1970-01-01', 0, 'Изучение циклов', '', '2018-10-17' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 2, 9, NOW( ), '1970-01-01', 0, 'Покормить кота', '', '2018-10-18' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 2, 9, NOW( ), '1970-01-01', 0, 'Убрать за котом', '', '2018-10-19' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 2, 10, NOW( ), '1970-01-01', 0, 'Сменить цепь', '', '2018-10-20' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 2, 10, NOW( ), '1970-01-01', 0, 'Починить тормоза', '', '2018-10-21' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 3, 12, NOW( ), '1970-01-01', 0, 'Установка VMWare', '', '2018-10-10' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 3, 12, NOW( ), '1970-01-01', 0, 'Клонирование Windows 2012', '', '2018-10-12' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 3, 14, NOW( ), '1970-01-01', 0, 'Поклейка обоев', '', '2018-10-17' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 3, 14, NOW( ), '1970-01-01', 0, 'Облицовка плиткой', '', '2018-10-18' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 3, 14, NOW( ), '1970-01-01', 0, 'Выравнивание потолка', '', '2018-10-19' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 3, 15, NOW( ), '1970-01-01', 0, 'Настройка робота', '', '2018-10-20' );
INSERT INTO tasks ( id_user, id_project, date_create, date_completion, `status`, task_name, file_name, date_deadline )
VALUES
	( 3, 15, NOW( ), '1970-01-01', 0, 'Роботизация выноса мусора', '', '2018-10-21' );

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
	AND tasks.date_deadline <=  CURRENT_DATE() + INTERVAL 1 DAY;

/*обновить название задачи по её идентификатору*/
UPDATE tasks 
SET tasks.task_name = 'Погрузка дивана и стиральной машины' 
WHERE
	tasks.id = 4;
