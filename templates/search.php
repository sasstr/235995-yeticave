<nav class="nav">
      <ul class="nav__list container">
        <?= $page_categories ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $search_ft_to_db['0'] ?></span>»</h2><!-- Union -->
        <ul class="lots__list">
        <? foreach($res_search as $val): ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?= isset($val['img_path']) ? htmlspecialchars($val['img_path']) : ''; ?>" width="350" height="260" alt="Сноуборд"><!-- img/lot-1.jpg -->
            </div>
            <div class="lot__info">
              <span class="lot__category"><?= isset($val['name']) ? htmlspecialchars($val['name']) : ''; ?></span>
              <h3 class="lot__title"><a class="text-link" href="<?= isset($val['id']) ? 'lot.php?id=' . htmlspecialchars($val['id']) : ''; ?>"><? isset($val['title']) ? print $val['title'] : print ''; ?></a></h3><!-- 2014 Rossignol District Snowboard --><!-- lot.html -->
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost"><?= isset($val['starting_price']) ? htmlspecialchars($val['starting_price']) : ''; ?><b class="rub"><?= RUBLE_SYMBOL ?></b></span><!-- р --><!-- 10 999 -->
                </div>
                <div class="lot__timer timer">
                <?= isset($val['finishing_date']) ? format_time_rate($val['finishing_date']) : ''; ?>
                  16:54:12
                </div>
              </div>
            </div>
          </li>
          <? endforeach ?>
          <!-- <li class="lots__item lot">
            <div class="lot__image">
              <img src="img/lot-2.jpg" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category">Доски и лыжи</span>
              <h3 class="lot__title"><a class="text-link" href="lot.html">DC Ply Mens 2016/2017 Snowboard</a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">12 ставок</span>
                  <span class="lot__cost">15 999<b class="rub">р</b></span>
                </div>
                <div class="lot__timer timer timer--finishing">
                  00:54:12
                </div>
              </div>
            </div>
          </li> -->
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
