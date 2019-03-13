<nav class="nav">
      <ul class="nav__list container">
        <!-- <li class="nav__item nav__item--current">
          <a href="all-lots.html">Доски и лыжи</a>
        </li> -->
        <?= $page_categories ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span> "<?= htmlspecialchars($page_name) ?>"</span></h2>
        <ul class="lots__list">
          <? foreach($category_lots as $lot) : ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?= htmlspecialchars($lot['img_path']); ?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?= htmlspecialchars($lot['categories_name']) ?></span>
              <h3 class="lot__title"><a class="text-link" href="<?= 'lot.php?id=' .  $lot['id'] ?>"><?= htmlspecialchars($lot['lots_title']) ?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost"><?= format_price($lot['starting_price']) ?></span>
                </div>
                <div class="lot__timer timer">
                <?= show_left_time($lot['finishing_date']) ?>
                </div>
              </div>
            </div>
          </li>
            <? endforeach ?>
        </ul>
      </section>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
      </ul>
    </div>
