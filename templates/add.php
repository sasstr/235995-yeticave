    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
            </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <form class="form form--add-lot container <?php if($errors): ?>form--invalid<?php endif;?>" action="add-lot.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item  <?php if($errors['lot-name']): ?>form__item--invalid<?php endif;?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование</label>
          <?php $lot_name = $_POST['lot-name'] ?? ''; ?>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value='<?= htmlspecialchars($lot_name) ?>' required>
          <span class="form__error"><? isset($errors['lot-name']) ? print $errors['lot-name'] : print '' ?></span>
        </div>
        <div class="form__item  <?php if($errors['category']): ?>form__item--invalid<?php endif;?>">
          <label for="category">Категория</label>
          <? $value = $_POST['category'] ?? ''; ?>
          <select id="category" name="category" required>
            <option value="-1">Выберите категорию</option>
            <?php foreach ($categories as $key => $val): ?>
                <option value="<?= htmlspecialchars($val['id']); ?>"
                    <?php if ($val['id'] === $value): print ' selected'; endif; ?> >
                    <?=htmlspecialchars($val['name']); ?>
                </option>
            <?php endforeach; ?>
          </select>
          <span class="form__error"><?php isset($errors['category']) ? print $errors['category'] : print '' ?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?php if($errors['message']): ?>form__item--invalid<?php endif;?>">
        <label for="message">Описание</label>
        <?php $message = $_POST['message'] ?? '';  ?>
        <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= htmlspecialchars($message) ?></textarea>
        <span class="form__error"><?php isset($errors['message']) ? print $errors['message'] : print '' ?></span>
      </div>
      <div class="form__item form__item--file  <?php if($errors['img-file']): ?>form__item--invalid<?php endif;?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="src="<? print $file_url ?>" width="113" height="113" alt="Изображение лота"> <!--  -->
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="photo2" name='img-file' value="">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
            <span class="form__error"><?php isset($errors['img-file']) ? print $errors['img-file'] : print '' ?></span>
        </div>
      </div>
      <div class="form__container-three <?php if($errors): ?>form--invalid<?php endif;?>">
        <div class="form__item form__item--small <?php if($errors['lot-rate']): ?>form__item--invalid<?php endif;?>">
          <label for="lot-rate">Начальная цена</label>
          <?php $lot_rate = $_POST['lot-rate'] ?? '';  ?>
          <input id="lot-rate" type="number" name="lot-rate" placeholder="0" value='<?= htmlspecialchars($lot_rate) ?>' required>
          <span class="form__error"><?php isset($errors['lot-rate']) ? print $errors['lot-rate'] : print '' ?></span>
        </div>
        <div class="form__item form__item--small <?php if($errors['lot-step']): ?>form__item--invalid<?php endif;?>">
          <label for="lot-step">Шаг ставки</label>
          <?php $lot_step = $_POST['lot-step'] ?? '';  ?>
          <input id="lot-step" type="number" name="lot-step" placeholder="0" value='<?= htmlspecialchars($lot_step) ?>' required>
          <span class="form__error"><?php isset($errors['lot-step']) ? print $errors['lot-step'] : print '' ?></span>
        </div>
        <div class="form__item <?php if($errors['lot-date']): ?>form__item--invalid<?php endif;?>">
          <label for="lot-date">Дата окончания торгов</label>
          <?php $lot_date = $_POST['lot-date'] ?? '';  ?>
          <input class="form__input-date" id="lot-date" type="date" name="lot-date" value='<?= htmlspecialchars($lot_date) ?>' required>
          <span class="form__error"><?php isset($errors['lot-date']) ? print $errors['lot-date'] : print '' ?></span>
        </div>
      </div>
        <span class="form__error <?php if($errors): ?>form__error--bottom<?php endif;?>"><?php isset($errors['form']) ? print $errors['form'] : print '' ?></span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
