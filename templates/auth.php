<h2 class="content__main-heading">Вход на сайт</h2>

<form class="form" action="auth.php" method="post">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>
        <?php if (isset($errors["email"])) {?>
            <input class="form__input form__input--error" type="text" name="email" id="email" value="<?=$form["email"];?>" placeholder="Введите e-mail">
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
            <input class="form__input" type="password" name="password" id="password" value="" placeholder="Введите пароль">
        <?php }?>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Войти">
    </div>
</form>
