<nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
            </li>
        <?php endforeach ?>
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
        <?php if (isset($_SESSION['user'])) : ?>
          <div class="lot-item__state">
            <div class="lot-item__timer timer">
            <?php isset($rates_data['finishing_date']) ?  ($rates_data['finishing_date']) : print '' ?>
              10:54
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=  isset($rates_data['rate_amount']) ?  $rates_data['rate_amount'] : print '' ?></span><!-- 10 999 -->
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?= isset($rates_data['rate_step']) ? $min_rate : print '' ?></span><!-- 12 000 р -->
              </div>
            </div>
            <form class="lot-item__form" action="lot.php" method="post" enctype="application/x-www-form-urlencoded">
              <p class="lot-item__form-item form__item form__item--invalid">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?= $min_rate ?>"><!-- 12 000 -->
                <span class="form__error">Введите наименование лота</span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <?php endif ?>
          <div class="history">
            <h3>История ставок (<span>10</span>)</h3>

            <table class="history__list">
            <?php foreach ($rates_data as $rate => $val) : ?>
                <tr class="history__item">
                    <td class="history__name"><?php if(isset($val['name'])): print $val['name'] ?><?php endif;?></td>
                    <td class="history__price"><?php if(isset($val['rate_amount'])): print $val['rate_amount'] ?><?php endif;?></td>
                    <td class="history__time"><?php if(isset($val['date'])): print $val['date'] ?><?php endif;?><!-- 5 минут назад --></td>
                </tr>
              <?php endforeach ?>
            </table>
          </div>
        </div>
      </div>
    </section>
