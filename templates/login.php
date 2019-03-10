<nav class="nav">
      <ul class="nav__list container">
        <?= $page_categories ?>
      </ul>
    </nav>
    <form class="form container  <?= isset($errors) ? 'form--invalid' : '' ?>" action="login.php" method="post" enctype="application/x-www-form-urlencoded"> <!-- form--invalid -->
      <h2>Вход</h2>
      <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail*</label>

        <input id="email" type="text" name="email" value="<?= isset($login['email']) ? htmlspecialchars($login['email']) : '' ?>" placeholder="Введите e-mail" required>
        <span class="form__error"><?= isset($errors['email']) ? $errors['email'] : '' ?></span>
      </div>
      <div class="form__item form__item--last <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль*</label>

        <input id="password" type="text" name="password" value="<?= isset($login['password']) ? htmlspecialchars($login['password']) : '' ?>" placeholder="Введите пароль" required>
        <span class="form__error"><?= isset($errors['password']) ? $errors['password'] : '' ?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
