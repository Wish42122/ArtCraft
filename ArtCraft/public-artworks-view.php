<?php foreach ($approved as $art): ?>
    <?php if ($categoryPage): ?>
        <article class="card" data-styles="" data-created="<?=e($art['created_at'])?>" data-category="<?=e($art['category_id'])?>">
            <div class="image">
                <a href="image.php?id=<?=e($art['id'])?>"><img src="image.php?id=<?=e($art['id'])?>" alt="<?=e($art['title'])?>" loading="lazy"></a>
                <button class="heart" aria-label="Like <?=e($art['title'])?>">♡</button>
            </div>
            <div class="details">
                <h2><?=e($art['title'])?></h2>
                <p>By <?=e($art['fullname'])?></p>
                <p><?=nl2br(e($art['description']))?></p>
            </div>
        </article>
    <?php else: ?>
        <article class="art-card" data-created="<?=e($art['created_at'])?>" data-category="<?=e($art['category_id'])?>">
            <div class="art-image">
                <a href="image.php?id=<?=e($art['id'])?>"><img src="image.php?id=<?=e($art['id'])?>" alt="<?=e($art['title'])?>" loading="lazy"></a>
            </div>
            <div class="art-info">
                <h2><?=e($art['title'])?></h2>
                <p class="artist">By: <?=e($art['fullname'])?> · <?=e($art['category'])?></p>
                <p><?=nl2br(e($art['description']))?></p>
            </div>
        </article>
    <?php endif; ?>
<?php endforeach; ?>
