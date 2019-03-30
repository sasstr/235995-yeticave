<?php if ($pages_count > 1): ?>
<ul class="pagination-list">
    <?php foreach ($pages as $page): ?>
    <li class="pagination-item pagination-item-prev">
        <a  href="#">Назад</a>
    </li>
    <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
        <a href="#"><?=$page;?></a>
    </li>
    <li class="pagination-item pagination-item-next">
        <a href="#">Вперед</a>
    </li>
    <!-- <li class="pagination-item">
        <a href="#">2</a>
    </li>
    <li class="pagination-item">
        <a href="#">3</a>
    </li>
    <li class="pagination-item">
        <a href="#">4</a>
    </li> -->
    <?php endforeach; ?>
</ul>
<?php endif; ?>
