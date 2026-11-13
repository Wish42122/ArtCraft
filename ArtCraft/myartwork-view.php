<div class="card">
    <h1>My Artworks</h1><p class="subtitle">Manage your submitted artworks</p>
    <?php notice($error); ?>
    <?php if ($edit): ?>
        <h2>Edit artwork</h2><p class="app-muted">Saving changes sends the artwork back for review.</p>
        <form class="app-form" method="post"><?=token()?>
            <input type="hidden" name="id" value="<?=$key?>"><input type="hidden" name="action" value="edit">
            <label>Title<input name="title" required maxlength="150" value="<?=e($error ? val('title') : $edit['title'])?>"></label>
            <label>Category<select name="category" required><?php category_options((int)($error ? val('category') : $edit['category_id'])); ?></select></label>
            <label>Description<textarea name="description" required maxlength="3000"><?=e($error ? val('description') : $edit['description'])?></textarea></label>
            <label>Materials<input name="materials" maxlength="180" value="<?=e($error ? val('materials') : $edit['materials'])?>"></label>
            <div class="app-tools"><button class="app-button">Save</button><a href="myartwork.php">Cancel</a></div>
        </form>
    <?php endif; ?>
    <div class="tabs" id="tabs">
        <button class="tab active" data-filter="all">All (<?=count($artworks)?>)</button>
        <?php foreach (['approved','pending','rejected'] as $status): ?>
            <button class="tab <?=$status==='rejected'?'rejected-tab':''?>" data-filter="<?=$status?>"><?=ucfirst($status)?> (<?=$counts[$status]??0?>)</button>
        <?php endforeach; ?>
    </div>
    <div id="rows">
        <?php foreach ($artworks as $art): ?>
            <div class="art-row" data-status="<?=e($art['status'])?>">
                <img class="thumb" src="image.php?id=<?=e($art['id'])?>" alt="">
                <div class="art-info"><div class="art-title"><?=e($art['title'])?></div><div class="art-cat"><?=e($art['category'])?></div>
                    <div class="art-date">Submitted on <?=e(substr($art['created_at'],0,10))?></div></div>
                <span class="badge <?=e($art['status'])?>"><?=e(ucfirst($art['status']))?></span>
                <div class="actions">
                    <a href="image.php?id=<?=e($art['id'])?>">View</a>
                    <a href="myartwork.php?edit=<?=e($art['id'])?>">Edit</a>
                    <form method="post" class="app-inline" action="myartwork.php"><?=token()?><input type="hidden" name="id" value="<?=e($art['id'])?>">
                    <button name="action" value="delete" class="app-button danger" onclick="return confirm('Permanently delete this artwork?')">Delete</button></form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <p class="app-empty" id="emptyMsg" <?=$artworks?'hidden':''?>>No artworks in this category.</p>
</div>
<script>
document.querySelectorAll('#tabs button').forEach(tab => tab.addEventListener('click', () => {
    document.querySelectorAll('#tabs button').forEach(other => other.classList.toggle('active',other===tab));
    let visible=0;
    document.querySelectorAll('#rows .art-row').forEach(row => {
        const match=tab.dataset.filter==='all' || tab.dataset.filter===row.dataset.status;
        row.style.display=match?'':'none';
        if(match) visible++;
    });
    document.getElementById('emptyMsg').hidden=visible>0;
}));
</script>
