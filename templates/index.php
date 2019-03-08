    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <? foreach ($categories as $val): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.php&id=<?= htmlspecialchars($val['id']) ?>"><?= htmlspecialchars($val['name']); ?></a>
            </li>
            <? endforeach ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <? foreach ($lots as $val): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($val['img_path']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($val['categories_name']); ?></span>
                    <h3 class="lot__title"><a class="text-link" <?= 'href="lot.php?id=' . htmlspecialchars($val['id']) .'"'; ?> ><?= htmlspecialchars($val['lots_title']); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?= htmlspecialchars($val['starting_price']); ?></span>
                            <span class="lot__cost"><?= format_price($val['starting_price']) ?><!-- <b class="rub">р</b> --></span>
                        </div>
                        <div class="lot__timer timer">
                            <?= $time_until_midnight ?>
                        </div>
                    </div>
                </div>
            </li>
            <? endforeach ?>
        </ul>
    </section>

