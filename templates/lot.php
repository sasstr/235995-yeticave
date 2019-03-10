<nav class="nav">
      <ul class="nav__list container">
       <?= $page_categories ?>
      </ul>
    </nav>
    <section class="lot-item container">
      <h2><?= htmlspecialchars($lot['lots_title']); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img <?= 'src="' . htmlspecialchars($lot['img_path']) . '"' ?> width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['categories_name']); ?></span></p>
          <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
        </div>
        <div class="lot-item__right">
        <? if (isset($_SESSION['user']) && $rate_limit) : ?>
          <div class="lot-item__state">
            <div class="lot-item__timer timer">
              <!-- 10:54 --> <?= isset($diff_time) ? show_left_time($diff_time) : ''; ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=  format_price($amount) ?>
                </span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?= isset($min_rate) ? format_price($min_rate) : '' ?></span>
              </div>
            </div>
            <form class="lot-item__form" action=""  method="post" enctype="application/x-www-form-urlencoded">
              <p class="lot-item__form-item form__item <?= isset($errors['cost']) ? 'form__item--invalid' : '' ?>">
                <label for="cost">Ваша ставка</label>
                <input type="hidden" name="id" value='<?= isset($lot_id) ? htmlspecialchars($lot_id) : ''; ?>'>
                <input id="cost" type="text" name="cost" placeholder="<?= isset($min_rate) ? htmlspecialchars($min_rate) : ''; ?>">
                <span class="form__error"><?= isset($errors['cost']) ? htmlspecialchars($errors['cost']) : '' ?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <? endif ?>
          <div class="history">
            <h3>История ставок
                <span>(<?= count($history_data) ?>)</span>
            </h3>
            <table class="history__list">
            <? foreach ($history_data as $rate => $val) : ?>
                <tr class="history__item">
                    <td class="history__name"><?= isset($val['name']) ? $val['name'] : '' ?></td>
                    <td class="history__price"><?= isset($val['rate_amount']) ? format_price($val['rate_amount']) : '' ?></td>
                    <td class="history__time"><?= isset($val['date']) ? format_time_rate($val['date']): '' ?></td>
                </tr>
              <? endforeach ?>
            </table>
          </div>
        </div>
      </div>
    </section>
