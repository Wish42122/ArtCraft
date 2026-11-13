<div class="card">
<h1><?=e($heading)?></h1>
<p class="subtitle"><?=e($subtitle)?></p>
<?php notice($error); ?>
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
<form class="toolbar" method="get" action="<?=e($page)?>">
    <?php if (in_array($kind,['users','artworks'],true)): ?>
        <select name="status" aria-label="Status"><option value="">All statuses</option>
        <?php foreach ($statuses as $status): ?><option value="<?=e($status)?>" <?=$filter===$status?'selected':''?>><?=e(ucfirst($status))?></option><?php endforeach; ?>
        </select>
    <?php endif; ?>
    <?php if ($kind==='artworks'): ?><select name="category" aria-label="Category"><option value="">All categories</option><?php category_options($categoryFilter); ?></select><?php endif; ?>
    <label class="search-wrap"><input name="q" aria-label="Search" placeholder="Search…" maxlength="120" value="<?=e($q)?>"></label>
    <button class="btn-search app-button">Search</button>
</form>
<div class="app-table">
<table>
<thead><tr>
<?php foreach ([
    'users'=>['#','Name','Student ID','Email','Role','Status'],
    'artworks'=>['#','Image','Title','Artist','Category','Status'],
    'categories'=>['#','Name','Artworks'],
    'exhibitions'=>['#','Title','Date','Venue','Registrations'],
][$kind] as $col): ?><th><?=e($col)?></th><?php endforeach; ?>
<th>Actions</th></tr></thead>
<tbody>
<?php foreach ($rows as $row): $rid=(int)$row['id']; ?>
<tr><td class="idx"><?=$rid?></td>
<?php if ($kind==='users'): ?>
    <td><?=e($row['fullname'])?></td><td><?=e($row['studentid'])?></td><td><?=e($row['email'])?></td><td><?=e(ucfirst($row['role']))?></td><td><span class="pill <?=e($row['status'])?>"><?=e(ucfirst($row['status']))?></span></td>
<?php elseif ($kind==='artworks'): ?>
    <td><img class="thumb" src="image.php?id=<?=$rid?>" alt="" loading="lazy"></td><td class="t-title"><?=e($row['title'])?></td><td><?=e($row['fullname'])?></td><td><?=e($row['category'])?></td>
    <td><span class="pill <?=e($row['status'])?>"><?=e(ucfirst($row['status']))?></span><?=$row['featured']?' · Featured':''?></td>
<?php elseif ($kind==='categories'): ?>
    <td><?=e($row['name'])?></td><td><?=e($row['count'])?></td>
<?php else: ?>
    <td><?=e($row['title'])?></td><td><?=e($row['event_date'])?></td><td><?=e($row['venue'])?></td><td><?=e($row['count'])?></td>
<?php endif; ?>
<td>
<?php if ($kind==='users' && $row['role']==='admin'): ?>Protected<?php else: ?>
    <a class="app-button" href="<?=e($page)?>?edit=<?=$rid?>"><?=$kind==='artworks'?'View / Edit':'Edit'?></a>
    <form class="app-inline" method="post" action="<?=e($page)?>">
        <?=token()?><input type="hidden" name="id" value="<?=$rid?>">
        <?php if ($kind==='artworks' && $row['status']!=='approved'): ?><button class="app-button" name="action" value="approve">Approve</button><?php endif; ?>
        <?php if ($kind==='artworks' && $row['status']!=='rejected'): ?><button class="app-button danger" name="action" value="reject">Reject</button><?php endif; ?>
        <button class="app-button danger" name="action" value="delete" onclick="return confirm('Permanently delete this record?')">Delete</button>
    </form>
<?php endif; ?>
</td></tr>
<?php endforeach; ?>
</tbody></table>
<?php if (!$rows): ?><p class="app-empty">No records match your search.</p><?php endif; ?>
</div></div>
