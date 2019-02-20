<nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
            </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <form class="form form--add-lot container form--invalid" action="add.php" method="post"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?php if(isset($errors['lot-name'])):?>form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование</label>
          <?php $lot_name = $_POST['lot-name'] ?? '';  ?>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value='<?= htmlspecialchars($lot_name) ?>' required>
          <span class="form__error"><? isset($errors['lot-name']) ? print $errors['lot-name'] : print '' ?></span>
        </div>
        <div class="form__item <?php if(isset($errors['category'])):?>form__item--invalid<?php endif; ?>">
          <label for="category">Категория</label>
          <select id="category" name="category" required>
          <?php $category = $_POST['category'] ?? 'Выберите категорию';  ?>
            <option><?= htmlspecialchars($category) ?></option>
            <?php foreach ($categories as $val): ?>
                <option><?= htmlspecialchars($val['name']); ?></option>
            <?php endforeach ?>
            <? if ($category !== 'Выберите категорию') :
                print '<option> Выберите категорию </option>';
            endif ?>
          </select>
          <span class="form__error"><? isset($errors['category']) ? print $errors['category'] : print '' ?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?php if(isset($errors['message'])):?>form__item--invalid<?php endif; ?>">
        <label for="message">Описание</label>
        <?php $message = $_POST['message'] ?? '';  ?>
        <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= htmlspecialchars($message) ?></textarea>
        <span class="form__error"><? isset($errors['message']) ? print $errors['message'] : print '' ?></span>
      </div>
      <div class="form__item form__item--file"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="photo2" name='img_path' value="">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
            <span class="form__error"><? isset($errors['img_path']) ? print $errors['img_path'] : print '' ?></span>
        </div>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small  ">
          <label for="lot-rate">Начальная цена</label>
          <?php $lot_rate = $_POST['lot-rate'] ?? '';  ?>
          <input id="lot-rate" type="number" name="lot-rate" placeholder="0" value='<?= htmlspecialchars($lot_rate) ?>' required>
          <span class="form__error"><? isset($errors['lot-rate']) ? print $errors['lot-rate'] : print '' ?></span>
        </div>
        <div class="form__item form__item--small">
          <label for="lot-step">Шаг ставки</label>
          <?php $lot_step = $_POST['lot-step'] ?? '';  ?>
          <input id="lot-step" type="number" name="lot-step" placeholder="0" value='<?= htmlspecialchars($lot_step) ?>' required>
          <span class="form__error"><? isset($errors['lot-step']) ? print $errors['lot-step'] : print '' ?></span>
        </div>
        <div class="form__item">
          <label for="lot-date">Дата окончания торгов</label>
          <?php $lot_date = $_POST['lot-date'] ?? '';  ?>
          <input class="form__input-date" id="lot-date" type="date" name="lot-date" value='<?= htmlspecialchars($lot_date) ?>' required>
          <span class="form__error"><? isset($errors['lot-date']) ? print $errors['lot-date'] : print '' ?></span>
        </div>
      </div>
        <span class="form__error form__error--bottom"><?php isset($errors['form']) ? print $errors['form'] : print '' ?></span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
