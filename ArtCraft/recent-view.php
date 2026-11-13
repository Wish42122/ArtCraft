<?php foreach ($recent as $art): ?>
    <div class="sub-row">
        <img src="image.php?id=<?=e($art['id'])?>" alt="<?=e($art['title'])?>">
        <div class="sub-info">
            <a class="sub-title" href="manage_artworks.php?edit=<?=e($art['id'])?>"><?=e($art['title'])?></a>
            <div class="sub-by">By: <?=e($art['fullname'])?></div>
        </div>
        <span class="pill <?=e($art['status'])?>"><?=e(ucfirst($art['status']))?></span>
    </div>
<?php endforeach; ?>
<?php if (!$recent): ?><p class="app-empty">No artwork submissions yet.</p><?php endif; ?>
