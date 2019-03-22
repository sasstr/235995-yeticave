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
      <? $lot_data = get_lot_data($lot) ?>
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
      </table>
    </section>
