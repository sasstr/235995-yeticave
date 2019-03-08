<? foreach ($categories as $val): ?>
    <li class="nav__item">
        <a href="all-lots.php?id=<?= htmlspecialchars($val['id']); ?>"><?= htmlspecialchars($val['name']); ?></a>
    </li>
<? endforeach ?>
