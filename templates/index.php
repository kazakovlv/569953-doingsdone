<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
    <input class="search-form__input" type="text" name="search_text" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <?php if (isset($active_project)) {?>
             <?php if (isset($filter_task)) {?>
                <?php if ($filter_task == "all") {?>
                    <a href="index.php?task_filter=all&project_id=<?=$active_project?>" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <?php } else {?>
                     <a href="index.php?task_filter=all&project_id=<?=$active_project?>" class="tasks-switch__item">Все задачи</a>
                <?php }?>
                <?php if ($filter_task == "today") {?>
                    <a href="index.php?task_filter=today&project_id=<?=$active_project?>" class="tasks-switch__item tasks-switch__item--active">Повестка дня</a>
                <?php } else {?>
                    <a href="index.php?task_filter=today&project_id=<?=$active_project?>" class="tasks-switch__item">Повестка дня</a>
                <?php }?>
                <?php if ($filter_task == "tomorrow") {?>
                     <a href="index.php?task_filter=tomorrow&project_id=<?=$active_project?>" class="tasks-switch__item tasks-switch__item--active">Завтра</a>
                <?php } else {?>
                    <a href="index.php?task_filter=tomorrow&project_id=<?=$active_project?>" class="tasks-switch__item">Завтра</a>
                <?php }?>
                <?php if ($filter_task == "overdue") {?>
                    <a href="index.php?task_filter=overdue&project_id=<?=$active_project?>" class="tasks-switch__item tasks-switch__item--active">Просроченные</a>
                <?php } else {?>
                    <a href="index.php?task_filter=overdue&project_id=<?=$active_project?>" class="tasks-switch__item">Просроченные</a>
                <?php }?>
            <?php } else {?>
                <a href="index.php?task_filter=all&project_id=<?=$active_project?>" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <a href="index.php?task_filter=today&project_id=<?=$active_project?>" class="tasks-switch__item">Повестка дня</a>
                <a href="index.php?task_filter=tomorrow&project_id=<?=$active_project?>" class="tasks-switch__item">Завтра</a>
                <a href="index.php?task_filter=overdue&project_id=<?=$active_project?>" class="tasks-switch__item">Просроченные</a>
            <?php }?>
        <?php } else {?>
            <?php if (isset($filter_task)) {?>
                <?php if ($filter_task == "all") {?>
                    <a href="index.php?task_filter=all" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <?php } else {?>
                    <a href="index.php?task_filter=all" class="tasks-switch__item">Все задачи</a>
                <?php }?>
                <?php if ($filter_task == "today") {?>
                    <a href="index.php?task_filter=today" class="tasks-switch__item tasks-switch__item--active">Повестка дня</a>
                <?php } else {?>
                    <a href="index.php?task_filter=today" class="tasks-switch__item">Повестка дня</a>
                <?php }?>
                <?php if ($filter_task == "tomorrow") {?>
                    <a href="index.php?task_filter=tomorrow" class="tasks-switch__item tasks-switch__item--active">Завтра</a>
                <?php } else {?>
                    <a href="index.php?task_filter=tomorrow" class="tasks-switch__item">Завтра</a>
                <?php }?>
                <?php if ($filter_task == "overdue") {?>
                    <a href="index.php?task_filter=overdue" class="tasks-switch__item tasks-switch__item--active">Просроченные</a>
                <?php } else {?>
                    <a href="index.php?task_filter=overdue" class="tasks-switch__item">Просроченные</a>
                <?php }?>
            <?php } else {?>
                <a href="index.php?task_filter=all" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <a href="index.php?task_filter=today" class="tasks-switch__item">Повестка дня</a>
                <a href="index.php?task_filter=tomorrow" class="tasks-switch__item">Завтра</a>
                <a href="index.php?task_filter=overdue" class="tasks-switch__item">Просроченные</a>
            <?php }?>
        <?php }?>
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
    <?php if (isset($search_error)) {?>
        <p style="color: red"><?=$search_error?></p>
    <?php } else {?>
    <?php foreach ($taskList as $key => $val) { ?>
        <?php if($val["status"] == 1) { ?>
            <!-- Завершенное задачи-->
            <?php if ($show_complete_tasks) { ?>
                <tr class="tasks__item task task--completed">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value=<?=$val["id"];?> checked>
                            <span class="checkbox__text"><?=htmlspecialchars($val["task_name"]);?></span>
                        </label>
                    </td>
                    <td class="task__file">
                        <?php if ($val["file_name"] != "") { ?>
                            <a class="download-link" href="uploads/<?=$val["file_name"]?>" download><?=htmlspecialchars($val["file_name"]);?></a>
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
                        <a class="download-link" href="uploads/<?=$val["file_name"]?>" download><?=htmlspecialchars($val["file_name"]);?></a>
                    <?php } ?>
                </td>
                <td class="task__date"><?=showDate($val["date_deadline"]);?></td>
            </tr>
        <?php } ?>
        <?php } ?>
    <?php } ?>
    <!-- Конец вставки -->
</table>
