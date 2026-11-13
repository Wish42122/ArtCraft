<div class="card">
<h1>Exhibitions</h1>
<p class="subtitle">Explore ArtCraft showcases and register to take part.</p>
<?php notice($error); ?>
<?php foreach ($events as $event): ?>
    <article class="app-notice">
        <h2><?=e($event['title'])?></h2>
        <p><?=e($event['event_date'])?> · <?=e($event['venue'])?></p>
        <p><?=nl2br(e($event['description']))?></p>
        <?php if ($event['joined']): ?><p>Registered</p><?php endif; ?>
        <?php if ($user['role']==='student' && $event['event_date']>=date('Y-m-d')): ?>
            <form method="post" class="app-inline"><?=token()?><input type="hidden" name="id" value="<?=e($event['id'])?>">
            <button class="app-button" name="action" value="<?=$event['joined']?'leave':'join'?>"><?=$event['joined']?'Cancel registration':'Register'?></button></form>
        <?php endif; ?>
    </article>
<?php endforeach; ?>
<?php if (!$events): ?><p class="app-empty">No exhibitions have been scheduled.</p><?php endif; ?>
</div>
