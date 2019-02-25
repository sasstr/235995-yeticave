<nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
            </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <form class="form container  <?php if(isset($errors)): ?>form--invalid<?php endif;?>" action="login.php" method="post" enctype="application/x-www-form-urlencoded"> <!-- form--invalid -->
      <h2>Вход</h2>
      <div class="form__item <?php if(isset($errors['email'])): ?>form__item--invalid<?php endif;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <?php $email = $login['email'] ?? ''; ?>
        <input id="email" type="text" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Введите e-mail" required>
        <span class="form__error"><?php isset($errors['email']) ? print $errors['email'] : print '' ?></span> <!-- Введите e-mail -->
      </div>
      <div class="form__item form__item--last <?php if(isset($errors['password'])): ?> form__item--invalid <?php endif; ?>">
        <label for="password">Пароль*</label>
        <?php $password = $login['password'] ?? ''; ?>
        <input id="password" type="text" name="password" value="<?= htmlspecialchars($password) ?>" placeholder="Введите пароль" required>
        <span class="form__error">Введите пароль</span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
