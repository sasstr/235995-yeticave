<nav class="nav">
      <ul class="nav__list container">
      <? foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
            </li>
        <? endforeach ?>
      </ul>
    </nav>
    <form class="form container  <? if(isset($errors)): ?>form--invalid<? endif;?>" action="login.php" method="post" enctype="application/x-www-form-urlencoded"> <!-- form--invalid -->
      <h2>Вход</h2>
      <div class="form__item <? if(isset($errors['email'])): ?>form__item--invalid<? endif;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <? $email = htmlspecialchars($login['email']) ?? ''; ?>
        <input id="email" type="text" name="email" value="<?= $email ?>" placeholder="Введите e-mail" required>
        <span class="form__error"><? isset($errors['email']) ? print $errors['email'] : print '' ?></span> <!-- Введите e-mail -->
      </div>
      <div class="form__item form__item--last <? if(isset($errors['password'])): ?> form__item--invalid <? endif; ?>">
        <label for="password">Пароль*</label>
        <? $password = htmlspecialchars($login['password']) ?? ''; ?>
        <input id="password" type="text" name="password" value="<?= $password ?>" placeholder="Введите пароль" required>
        <span class="form__error"><? print isset($errors['password']) ? $errors['password'] : '' ?></span> <!-- Введите пароль -->
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
