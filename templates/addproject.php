<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="" method="post" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>
        <?php if (isset($errors)) { ?>
            <input class="form__input form__input--error" type="text" name="name_project" id="project_name" value="" placeholder="Введите название проекта">
            <span class="error-message"><?=$errors;?></span>
        <?php } else {?>
            <input class="form__input" type="text" name="name_project" id="project_name" value="" placeholder="Введите название проекта">
        <?php } ?>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
