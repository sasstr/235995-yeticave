<nav class="nav">
      <ul class="nav__list container">
      <?= $page_categories ?>
      </ul>
    </nav>
    <form class="form container <?= isset($errors) ? 'form--invalid' : '' ?>" action="sign-up.php" method="post" enctype="multipart/form-data">
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail*</label>

        <input id="email" type="text" name="email" placeholder="Введите e-mail" value='<?= isset($sign_up['email']) ? htmlspecialchars($sign_up['email']) : '' ?>' required>
        <span class="form__error"><?= isset($errors['email']) ? $errors['email'] : '' ?></span>
      </div>
      <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль*</label>

        <input id="password" type="password" name="password" placeholder="Введите пароль" value='<?= isset($sign_up['password']) ? htmlspecialchars($sign_up['password']) : '' ?>' required>
        <span class="form__error">Введите пароль</span>
      </div>
      <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя*</label>

        <input id="name" type="text" name="name" placeholder="Введите имя" value='<?=  isset($sign_up['name']) ? htmlspecialchars($sign_up['name']) : ''  ?>' required>
        <span class="form__error">Введите имя</span>
      </div>
      <div class="form__item <?= isset($errors['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные*</label>

        <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required><?= isset($message_up) ? htmlspecialchars($message_up) : ''  ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
      </div>
      <div class="form__item form__item--file form__item--last <?= isset($errors['img-avatar']) ? 'form__item--invalid' : '' ?>">
        <label>Аватар</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" name='img-avatar' id="photo2" value="">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
          <span class="form__error"><?= isset($errors['img-avatar']) ? htmlspecialchars($errors['img-avatar']) : '' ?></span>
        </div>
      </div>
      <span class="form__error <?= isset($errors)? 'form__error--bottom' : '' ?>">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
