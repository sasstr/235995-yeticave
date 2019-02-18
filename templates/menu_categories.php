<?php foreach ($categories as $val): ?>
    <li class="nav__item">
        <a href="all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
    </li>
<?php endforeach ?>