<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="" method="post" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>
        <?php if (isset($errors)) { ?>
            <?php if (isset($errors["name"])) {?>
            <input class="form__input form__input--error" type="text" name="taskItem[name]" id="name" value="" placeholder="Введите название">
            <span class="error-message"><?=$errors["name"];?></span>
            <?php } else {?>
                <input class="form__input" type="text" name="taskItem[name]" id="name" value="<?=$taskItem["name"];?>" placeholder="Введите название">
            <?php } ?>
        <?php } else {?>
            <input class="form__input" type="text" name="taskItem[name]" id="name" value="" placeholder="Введите название">
        <?php }?>
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>
        <?php if (isset($errors)) { ?>
            <?php if (isset($errors["project"])) { ?>
                <select class="form__input form__input--select form__input--error" name="taskItem[project]" id="project">
                    <?php foreach ($projectList as $key => $val) { ?>
                        <option value="<?=$val["id"]; ?>"><?=$val["project_name"]; ?></option>
                    <?php } ?>
                </select>
                <span class="error-message"><?=$errors["project"];?></span>
            <?php } else {?>
                <select class="form__input form__input--select" name="taskItem[project]" id="project">
                    <?php foreach ($projectList as $key => $val) { ?>
                        <?php if ((int)$val["id"] === (int)$taskItem["project"]) {?>
                        <option value="<?=$val["id"]; ?>" selected="selected"><?=$val["project_name"]; ?></option>
                        <?php } else {?>
                        <option value="<?=$val["id"]; ?>"><?=$val["project_name"]; ?></option>
                        <?php }?>
                    <?php } ?>
                </select>
            <?php }?>
        <?php } else {?>
        <select class="form__input form__input--select" name="taskItem[project]" id="project">
            <?php foreach ($projectList as $key => $val) { ?>
                <option value="<?=$val["id"]; ?>"><?=$val["project_name"]; ?></option>
            <?php } ?>
        </select>
        <?php }?>
    </div>

    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>
        <?php if (isset($errors)) { ?>
            <?php if (isset($errors["date"])) { ?>
                <input class="form__input form__input--date form__input--error" type="date" name="taskItem[date]" id="date" value="" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
                <span class="error-message"><?=$errors["date"];?></span>
            <?php } else {?>
                <input class="form__input form__input--date" type="date" name="taskItem[date]" id="date" value="<?=$taskItem["date"]?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
            <?php }?>
        <?php } else {?>
            <input class="form__input form__input--date" type="date" name="taskItem[date]" id="date" value="" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
        <?php }?>
    </div>

    <div class="form__row">
        <label class="form__label" for="preview">Файл</label>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="taskFile" id="preview" value="">
            <label class="button button--transparent" for="preview">
                <span>Выберите файл</span>
            </label>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
