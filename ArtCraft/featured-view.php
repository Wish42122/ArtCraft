<?php foreach ($featured as $art): ?>
    <a class="artwork-card" href="artgallery.php?art=<?=e($art['id'])?>">
        <img src="image.php?id=<?=e($art['id'])?>" alt="<?=e($art['title'])?>">
    </a>
<?php endforeach; ?>
