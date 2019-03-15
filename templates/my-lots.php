<nav class="nav">
      <ul class="nav__list container">
        <?= $page_categories ?>
      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
      <? if(isset($my_lots)) : ?>
      <? foreach($my_lots as $lot) : ?>
      <? $my_lots_data = ['rate_winner' => ['rate_msg' => 'Ставка выиграла',
                                    'timer_class_value' => 'timer--win',
                                    'item_class_value' => 'rates__item--win'],
                    'rate_low_time' => ['rate_msg' => show_left_time($lot['finishing_date']),
                                    'timer_class_value' => 'timer--finishing',
                                    'item_class_value' => ''],
                    'rate_end' => ['rate_msg' => 'Торги окончены',
                                    'timer_class_value' => 'timer--end',
                                    'item_class_value' => 'rates__item--end'],
                    'rate_msg' => ['rate_msg' => show_left_time($lot['finishing_date']),
                                    'timer_class_value' => '',
                                    'item_class_value' => '']
            ];
        $end_date = $lot['finishing_date'];
        $winner_id = $lot['winner_id'] ?? null;
        $lot_data = check_my_lots_date ($end_date, $winner_id, $my_lots_data); ?>
        <tr class="rates__item <?= isset($lot_data['item_class_value']) ? $lot_data['item_class_value'] : '' ?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?= htmlspecialchars($lot['img_path']) ?>" width="54" height="40" alt="Сноуборд">
            </div>
            <h3 class="rates__title"><a href="lot.php<?= '?id=' . $_SESSION['user'][0]['id'] ?> "><?= htmlspecialchars($lot['lots_title']) ?></a></h3>
            <p><?= htmlspecialchars($_SESSION['user']['0']['contacts']) ?></p>
          </td>
          <td class="rates__category">
            <?= htmlspecialchars($lot['categories_name']) ?>
          </td>
          <td class="rates__timer <?= isset($lot_data['timer_class_value']) ? $lot_data['timer_class_value'] : '' ?>">
            <div class="timer <?= isset($lot_data['timer_class_value']) ? $lot_data['timer_class_value'] : '' ?>"><!-- 07:13:34  --><?= isset($lot_data['rate_msg']) ? $lot_data['rate_msg'] : ''; ?></div>
          </td>
          <td class="rates__price">
            <!-- 10 999 р  --><?= isset($lot['rate_amount']) ? format_price($lot['rate_amount']) : format_price($lot['starting_price']) ?>
          </td>
          <td class="rates__time">
            <!-- 5 минут назад  --><?= format_time_rate($lot['starting_date']) ?>
          </td>
        </tr>
        <? endforeach ?>
        <? endif ?>


        <!-- <tr class="rates__item">
          <td class="rates__info">
            <div class="rates__img">
              <img src="img/rate2.jpg" width="54" height="40" alt="Сноуборд">
            </div>
            <h3 class="rates__title"><a href="lot.html">DC Ply Mens 2016/2017 Snowboard</a></h3>
          </td>
          <td class="rates__category">
            Доски и лыжи
          </td>
          <td class="rates__timer">
            <div class="timer timer--finishing">07:13:34</div>
          </td>
          <td class="rates__price">
            10 999 р
          </td>
          <td class="rates__time">
            20 минут назад
          </td>
        </tr>
        <tr class="rates__item rates__item--win">
          <td class="rates__info">
            <div class="rates__img">
              <img src="img/rate3.jpg" width="54" height="40" alt="Крепления">
            </div>
            <div>
              <h3 class="rates__title"><a href="lot.html">Крепления Union Contact Pro 2015 года размер L/XL</a></h3>
              <p>Телефон +7 900 667-84-48, Скайп: Vlas92. Звонить с 14 до 20</p>
            </div>
          </td>
          <td class="rates__category">
            Крепления
          </td>
          <td class="rates__timer">
            <div class="timer timer--win">Ставка выиграла</div>
          </td>
          <td class="rates__price">
            10 999 р
          </td>
          <td class="rates__time">
            Час назад
          </td>
        </tr>
        <tr class="rates__item">
          <td class="rates__info">
            <div class="rates__img">
              <img src="img/rate4.jpg" width="54" height="40" alt="Ботинки">
            </div>
            <h3 class="rates__title"><a href="lot.html">Ботинки для сноуборда DC Mutiny Charocal</a></h3>
          </td>
          <td class="rates__category">
            Ботинки
          </td>
          <td class="rates__timer">
            <div class="timer">07:13:34</div>
          </td>
          <td class="rates__price">
            10 999 р
          </td>
          <td class="rates__time">
            Вчера, в 21:30
          </td>
        </tr>
        <tr class="rates__item rates__item--end">
          <td class="rates__info">
            <div class="rates__img">
              <img src="img/rate5.jpg" width="54" height="40" alt="Куртка">
            </div>
            <h3 class="rates__title"><a href="lot.html">Куртка для сноуборда DC Mutiny Charocal</a></h3>
          </td>
          <td class="rates__category">
            Одежда
          </td>
          <td class="rates__timer">
            <div class="timer timer--end">Торги окончены</div>
          </td>
          <td class="rates__price">
            10 999 р
          </td>
          <td class="rates__time">
            Вчера, в 21:30
          </td>
        </tr>
        <tr class="rates__item rates__item--end">
          <td class="rates__info">
            <div class="rates__img">
              <img src="img/rate6.jpg" width="54" height="40" alt="Маска">
            </div>
            <h3 class="rates__title"><a href="lot.html">Маска Oakley Canopy</a></h3>
          </td>
          <td class="rates__category">
            Разное
          </td>
          <td class="rates__timer">
            <div class="timer timer--end">Торги окончены</div>
          </td>
          <td class="rates__price">
            10 999 р
          </td>
          <td class="rates__time">
            19.03.17 в 08:21
          </td>
        </tr>
        <tr class="rates__item rates__item--end">
          <td class="rates__info">
            <div class="rates__img">
              <img src="img/rate7.jpg" width="54" height="40" alt="Сноуборд">
            </div>
            <h3 class="rates__title"><a href="lot.html">DC Ply Mens 2016/2017 Snowboard</a></h3>
          </td>
          <td class="rates__category">
            Доски и лыжи
          </td>
          <td class="rates__timer">
            <div class="timer timer--end">Торги окончены</div>
          </td>
          <td class="rates__price">
            10 999 р
          </td>
          <td class="rates__time">
            19.03.17 в 08:21
          </td>
        </tr> -->
      </table>
    </section>
