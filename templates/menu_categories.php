<? require_once('data.php'); ?>
<? foreach ($categories as $val): ?>
    <li class="nav__item">
        <a href="all-lots.php?id=<?=  htmlspecialchars($val['id']) . '&&name=' . htmlspecialchars($val['name']) ?>"><?= htmlspecialchars($val['name']); ?></a>
    </li>
<? endforeach ?>
