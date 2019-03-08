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
        <?php if (isset($_SESSION['user']) && $rate_limit) : ?>
          <div class="lot-item__state">
            <div class="lot-item__timer timer">
            <?php $now = date_create('now');
            $finishing_date = date_create($rates_data[0]['finishing_date']);
            $diff = date_diff($now, $finishing_date);
            $time_diff = date_interval_format($diff,'%D:%H:%I');
                print isset($time_diff) ? $time_diff : '' ?>
              <!-- 10:54 -->
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=  isset($rates_data[0]['rate_amount']) ?
                                                        format_price($rates_data[0]['rate_amount']) :
                                                        format_price((int) $rates_data[0]['starting_price']); ?>
                </span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?= isset($rates_data[0]['rate_step']) ? format_price($min_rate) : '' ?></span><!-- 12 000 р -->
              </div>
            </div>
            <form class="lot-item__form" action=""  method="post" enctype="application/x-www-form-urlencoded"> <!-- lot.php -->
              <p class="lot-item__form-item form__item <?php if(isset($errors['cost'])): ?>form__item--invalid<?php endif;?>">
                <label for="cost">Ваша ставка</label>
                <input type="hidden" name="id" value='<? isset($lot_id) ? print $lot_id : print ''; ?>'>
                <input id="cost" type="text" name="cost" placeholder="<?php isset($min_rate) ? print $min_rate : print ''; ?>">
                <span class="form__error"><?php isset($errors['cost']) ? print $errors['cost'] : print '' ?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <?php endif ?>
          <div class="history">
            <h3>История ставок <? $amount_rates = count($history_data) ?? ''; ?>
                <span>(<? isset($amount_rates) ? print $amount_rates : print ''; ?>)</span><!-- 10 -->
            </h3>
            <table class="history__list">
            <?php foreach ($history_data as $rate => $val) : ?>
                <tr class="history__item">
                    <td class="history__name"><?php if(isset($val['name'])): print $val['name'] ?><?php endif;?></td>
                    <td class="history__price"><?php if(isset($val['rate_amount'])): print $val['rate_amount'] ?><?php endif;?></td>
                    <td class="history__time"><?php if(isset($val['date'])): print format_time_rate($val['date']); ?><?php endif;?><!-- 5 минут назад --></td>
                </tr>
              <?php endforeach ?>
            </table>
          </div>
        </div>
      </div>
    </section>
