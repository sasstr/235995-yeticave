    <nav class="nav">
      <ul class="nav__list container">
      <? foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
            </li>
        <? endforeach ?>
      </ul>
    </nav>
    <form class="form form--add-lot container <? print isset($errors) ? 'form--invalid' : ''; ?>" action="add-lot.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item  <? print isset($errors['lot-name']) ? 'form__item--invalid' : '' ?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование</label>

          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value='<?= htmlspecialchars($lot_name) ?>' required>
          <span class="form__error"><? print isset($errors['lot-name']) ? $errors['lot-name'] : '' ?></span>
        </div>
        <div class="form__item  <? print isset($errors['category']) ?  'form__item--invalid' : '' ?>">
          <label for="category">Категория</label>

          <select id="category" name="category" required>
            <option value="-1">Выберите категорию</option>
            <? foreach ($categories as $key => $val): ?>
                <option value="<?= htmlspecialchars($val['id']); ?>"
                <? if ($val['id'] === $category_value): print ' selected'; endif; ?> >
                    <?=htmlspecialchars($val['name']); ?>
                </option>
            <? endforeach; ?>
          </select>
          <span class="form__error"><? print isset($errors['category']) ? $errors['category'] : '' ?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <? print isset($errors['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание</label>

        <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= htmlspecialchars($message) ?></textarea>
        <span class="form__error"><? print isset($errors['message']) ? $errors['message'] : '' ?></span>
      </div><? $img_file =  isset($errors['img-file']) ? 'form__item--invalid' : '' ?>
      <div class="form__item form__item--file  <?= htmlspecialchars($img_file) ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="<?= htmlspecialchars($file_url) ?>" width="113" height="113" alt="Изображение лота"> <!--  -->
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="photo2" name='img-file' value="">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
            <span class="form__error"><? print isset($errors['img-file']) ? $errors['img-file'] : '' ?></span>
        </div>
      </div>
      <div class="form__container-three <? print isset($errors) ? 'form--invalid' : '' ?>">
        <div class="form__item form__item--small <? print isset($errors['lot-rate']) ? 'form__item--invalid' : '' ?>">
          <label for="lot-rate">Начальная цена</label>

          <input id="lot-rate" type="number" name="lot-rate" placeholder="0" value='<?= htmlspecialchars($lot_rate) ?>' required>
          <span class="form__error"><? isset($errors['lot-rate']) ? print $errors['lot-rate'] : print '' ?></span>
        </div>
        <div class="form__item form__item--small <? print isset($errors['lot-step']) ? 'form__item--invalid' : '' ?>">
          <label for="lot-step">Шаг ставки</label>

          <input id="lot-step" type="number" name="lot-step" placeholder="0" value='<?= htmlspecialchars($lot_step) ?>' required>
          <span class="form__error"><? print isset($errors['lot-step']) ? $errors['lot-step'] : '' ?></span>
        </div>
        <div class="form__item <? print isset($errors['lot-date']) ? 'form__item--invalid' : '' ?>">
          <label for="lot-date">Дата окончания торгов</label>
          
          <input class="form__input-date" id="lot-date" type="date" name="lot-date" value='<?= htmlspecialchars($lot_date) ?>' required>
          <span class="form__error"><? print isset($errors['lot-date']) ? $errors['lot-date'] : '' ?></span>
        </div>
      </div>
        <span class="form__error <? print ($errors) ? 'form__error--bottom' : '' ?>"><? print isset($errors['form']) ? $errors['form'] : '' ?></span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
