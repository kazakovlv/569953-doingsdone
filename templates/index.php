<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed"
            <?php if ($show_complete_tasks) { ?>
                checked
            <?php } ?>
               type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <!--  Вставка таблицы -->
    <?php foreach ($taskList as $key => $val) { ?>
        <?php if($val["status"] == 1) { ?>
            <!-- Завершенное задачи-->
            <?php if ($show_complete_tasks) { ?>
                <tr class="tasks__item task task--completed">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox" checked>
                            <span class="checkbox__text"><?=htmlspecialchars($val["task_name"]);?></span>
                        </label>
                    </td>
                    <td class="task__file">
                        <?php if ($val["file_name"] != "") { ?>
                            <a class="download-link" href="#"><?=htmlspecialchars($val["file_name"]);?></a>
                        <?php } ?>
                    </td>
                    <td class="task__date"><?=showDate($val["date_deadline"]);?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <!-- Незавершенные задачи-->
            <tr class="tasks__item task <?=isImportant($val["date_deadline"]);?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value=<?=$val["id"];?>>
                        <span class="checkbox__text"><?=htmlspecialchars($val['task_name']);?></span>
                    </label>
                </td>
                <td class="task__file">
                    <?php if ($val["file_name"] != "") { ?>
                        <a class="download-link" href="#"><?=htmlspecialchars($val["file_name"]);?></a>
                    <?php } ?>
                </td>
                <td class="task__date"><?=showDate($val["date_deadline"]);?></td>
            </tr>
        <?php } ?>
        <?php
    } ?>
    <!-- Конец вставки -->
</table>
