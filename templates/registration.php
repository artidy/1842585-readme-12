<?php

/**
 * Шаблон регистрации
 * @var $errors - ошибки отправки формы
 * @var $errors_template - шаблон всех ошибок
 */

?>

<div class="container">
    <h1 class="page__title page__title--registration">Регистрация</h1>
</div>
<section class="registration container">
    <h2 class="visually-hidden">Форма регистрации</h2>
    <form class="registration__form form" action="registration.php" method="post" enctype="multipart/form-data">
        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">
                <div class="registration__input-wrapper form__input-wrapper">
                    <label class="registration__label form__label" for="registration-email">Электронная почта <span
                            class="form__input-required">*</span></label>
                    <div class="form__input-section <?= isset($errors["email"]) ? "form__input-section--error" : "" ?>">
                        <input class="registration__input form__input" id="registration-email" type="email"
                               name="email" placeholder="Укажите эл.почту">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                        </button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибки заполнения электронной почты</h3>
                            <?php foreach ($errors["email"] as $error): ?>
                                <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="registration__input-wrapper form__input-wrapper">
                    <label class="registration__label form__label" for="registration-login">Логин <span
                            class="form__input-required">*</span></label>
                    <div class="form__input-section <?= isset($errors["login"]) ? "form__input-section--error" : "" ?>">
                        <input class="registration__input form__input" id="registration-login" type="text"
                               name="login" placeholder="Укажите логин">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                        </button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибки заполнения логина</h3>
                            <?php foreach ($errors["login"] as $error): ?>
                                <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="registration__input-wrapper form__input-wrapper">
                    <label class="registration__label form__label" for="registration-password">Пароль<span
                            class="form__input-required">*</span></label>
                    <div class="form__input-section <?= isset($errors["password"]) ? "form__input-section--error" : "" ?>">
                        <input class="registration__input form__input" id="registration-password" type="password"
                               name="password" placeholder="Придумайте пароль">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                        </button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибки заполнения пароля</h3>
                            <?php foreach ($errors["password"] as $error): ?>
                                <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="registration__input-wrapper form__input-wrapper">
                    <label class="registration__label form__label" for="registration-password-repeat">Повтор
                        пароля<span class="form__input-required">*</span></label>
                    <div class="form__input-section  <?= isset($errors["password-repeat"]) ? "form__input-section--error" : "" ?>">
                        <input class="registration__input form__input" id="registration-password-repeat"
                               type="password" name="password-repeat" placeholder="Повторите пароль">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span>
                        </button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибки заполнения повтора пароля</h3>
                            <?php foreach ($errors["password-repeat"] as $error): ?>
                                <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php print($errors_template); ?>
        </div>
        <div class="registration__input-file-container form__input-container form__input-container--file">
            <div class="registration__input-file-wrapper form__input-file-wrapper">
                <div class="registration__file-zone form__file-zone dropzone">
                    <input class="registration__input-file form__input-file" id="userpic-file" type="file"
                           name="userpic-file" title=" ">
                    <div class="form__file-zone-text">
                        <span>Перетащите фото сюда</span>
                    </div>
                </div>
                <button class="registration__input-file-button form__input-file-button button" type="button">
                    <span>Выбрать фото</span>
                    <svg class="registration__attach-icon form__attach-icon" width="10" height="20">
                        <use xlink:href="#icon-attach"></use>
                    </svg>
                </button>
            </div>
            <div class="registration__file form__file dropzone-previews">
                <img class="preview" height="250" width="250" src="" alt="Превью аватарки">
            </div>
        </div>
        <button class="registration__submit button button--main" type="submit">Отправить</button>
    </form>
</section>
