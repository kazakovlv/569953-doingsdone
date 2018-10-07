<h2 class="content__main-heading">Регистрация аккаунта</h2>

<form class="form" action="registration.php" method="post">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>
        <?php if (isset($errors["email"])) {?>
            <input class="form__input form__input--error" type="text" name="email" id="email" value="" placeholder="Введите e-mail">
            <p class="form__message"><?=$errors["email"];?></p>
        <?php } else {?>
            <input class="form__input" type="text" name="email" id="email" value="<?=$form["email"];?>" placeholder="Введите e-mail">
        <?php }?>
    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>
        <?php if (isset($errors["password"])) {?>
            <input class="form__input form__input--error" type="password" name="password" id="password" value="" placeholder="Введите пароль">
            <p class="form__message"><?=$errors["password"];?></p>
        <?php } else {?>
            <input class="form__input" type="password" name="password" id="password" value="<?=$form["password"];?>" placeholder="Введите пароль">
        <?php }?>
    </div>

    <div class="form__row">
        <label class="form__label" for="name">Имя <sup>*</sup></label>
        <?php if (isset($errors["name"])) {?>
            <input class="form__input form__input--error" type="text" name="name" id="name" value="" placeholder="Введите Имя">
            <p class="form__message"><?=$errors["name"];?></p>
        <?php } else {?>
            <input class="form__input" type="text" name="name" id="name" value="<?=$form["name"];?>" placeholder="Введите Имя">
        <?php }?>
    </div>

    <div class="form__row form__row--controls">
        <?php if (isset($errors)) {?>
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php }?>
        <input class="button" type="submit" name="" value="Зарегистрироваться">
    </div>
</form>
