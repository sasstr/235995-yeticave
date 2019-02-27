<nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
            </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <form class="form container <?php if(isset($errors)): ?>form--invalid<?php endif;?>" action="sign-up.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?php if(isset($errors['email'])): ?>form__item--invalid<?php endif;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <?php $email = $sign_up['email'] ?? ''; ?>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value='<?= htmlspecialchars($email) ?>' required>
        <span class="form__error"><?php isset($errors['email']) ? print $errors['email'] : print '' ?></span><!-- Введите e-mail -->
      </div>
      <div class="form__item <?php if(isset($errors['password'])): ?> form__item--invalid <?php endif; ?>">
        <label for="password">Пароль*</label>
        <?php $password = $sign_up['password'] ?? ''; ?>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value='<?= htmlspecialchars($password) ?>' required>
        <span class="form__error">Введите пароль</span>
      </div>
      <div class="form__item <?php if(isset($errors['name'])): ?>form__item--invalid<?php endif;?>">
        <label for="name">Имя*</label>
        <?php $name = $sign_up['name'] ?? ''; ?>
        <input id="name" type="text" name="name" placeholder="Введите имя" value='<?= htmlspecialchars($name) ?>' required>
        <span class="form__error">Введите имя</span>
      </div>
      <div class="form__item <?php if(isset($errors['message'])): ?>form__item--invalid<?php endif;?>">
        <label for="message">Контактные данные*</label>
        <?php $message_up = $_POST['message'] ?? ''; ?>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required><?= htmlspecialchars($message_up) ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
      </div>
      <div class="form__item form__item--file form__item--last <?php if(isset($errors['img-avatar'])): ?>form__item--invalid<?php endif;?>">
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
          <span class="form__error"><?php isset($errors['img-avatar']) ? print $errors['img-avatar'] : print '' ?></span>
        </div>
      </div>
      <span class="form__error <?php if(isset($errors)): ?>form__error--bottom<?php endif;?>">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
