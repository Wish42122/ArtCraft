<?php if ($showForm): ?>
    <h2><?= $edit ? ($kind==='artworks'?'Review artwork':'Edit record') : 'Add record' ?></h2>
    <?php if ($edit && $kind==='artworks'): ?>
        <img class="app-figure" src="image.php?id=<?=$key?>" alt="<?=e($edit['title'])?>">
    <?php endif; ?>
    <form class="app-form" method="post" action="<?=e($page.($edit?'?edit='.$key:'?new'))?>">
        <?=token()?><input type="hidden" name="id" value="<?=$edit?$key:0?>"><input type="hidden" name="action" value="save">
        <?php if ($kind==='users'): ?>
            <?php field('fullname','Full name',120); field('studentid','Student ID',32); field('email','Email',254,'email');
            field('password',$edit?'New password (optional)':'Password (at least 8 characters)',72,'password',!$edit);
            select_field('status','Status',['active','inactive']); ?>
        <?php elseif ($kind==='categories'): ?>
            <?php field('name','Category name',80); ?>
        <?php else: ?>
            <?php field('title','Title',150); ?>
            <?php if ($kind==='exhibitions'): ?>
                <?php field('event_date','Date',10,'date'); field('venue','Venue',150); ?>
            <?php else: ?>
                <label>Category<select name="category" required><?php category_options((int)($error ? val('category') : $edit['category_id'])); ?></select></label>
                <?php field('materials','Materials',180,'text',false); select_field('status','Status',['pending','approved','rejected']); ?>
                <label style="display:block"><input type="checkbox" name="featured" value="1" <?=field_value('featured')==='1'?'checked':''?>> Feature on homepage</label>
            <?php endif; ?>
            <label>Description<textarea name="description" rows="4" maxlength="3000" <?=$kind==='artworks'?'required':''?>><?=e(field_value('description'))?></textarea></label>
        <?php endif; ?>
        <div class="app-tools"><button class="app-button">Save</button><a href="<?=e($page)?>">Cancel</a></div>
    </form>
<?php elseif ($kind !== 'artworks'): ?>
    <div class="app-tools"><a href="<?=e($page)?>?new">Add <?=e(['users'=>'student','categories'=>'category','exhibitions'=>'exhibition'][$kind])?></a></div>
<?php endif; ?>
