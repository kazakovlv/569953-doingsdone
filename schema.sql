-- Дамп структуры базы данных doingsdone
CREATE DATABASE IF NOT EXISTS `doingsdone` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE `doingsdone`;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица doingsdone.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `registration_date` datetime NOT NULL COMMENT 'Дата регистрации',
  `email` varchar(255) NOT NULL COMMENT 'E-Mail',
  `user_name` varchar(255) NOT NULL COMMENT 'Имя пользователя',
  `password` varchar(255) NOT NULL COMMENT 'Хэш пароля',
  `contacts` varchar(255) DEFAULT NULL COMMENT 'Контактная информация',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  FULLTEXT KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица пользователей';

-- Дамп структуры для таблица doingsdone.projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `id_user` int(10) unsigned NOT NULL COMMENT 'Идентификатор Пользователя',
  `project_name` varchar(255) NOT NULL COMMENT 'Наименование Проекта',
  PRIMARY KEY (`id`),
  KEY `FK_projects_users` (`id_user`),
  FULLTEXT KEY `project_name` (`project_name`),
  CONSTRAINT `FK_projects_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Проекты';

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица doingsdone.tasks
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `id_user` int(10) unsigned NOT NULL COMMENT 'Идентификатор Пользователя',
  `id_project` int(10) unsigned NOT NULL COMMENT 'Идентификатор Проекта',
  `date_create` datetime NOT NULL COMMENT 'Дата создания Задачи',
  `date_completion` datetime NOT NULL COMMENT 'Дата и время, когда задача была выполнена',
  `status` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Число (1 или 0), означающее, была ли выполнена задача. По умолчанию ноль.',
  `task_name` varchar(255) NOT NULL COMMENT 'Название задачи',
  `file_name` varchar(255) NOT NULL COMMENT 'Ссылка на файл',
  `date_deadline` datetime NOT NULL COMMENT 'Дата и время, до которого задача должна быть выполнена',
  PRIMARY KEY (`id`),
  KEY `FK_tasks_users` (`id_user`),
  KEY `FK_tasks_projects` (`id_project`),
  FULLTEXT KEY `task_name` (`task_name`),
  CONSTRAINT `FK_tasks_projects` FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`),
  CONSTRAINT `FK_tasks_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Задачи';
