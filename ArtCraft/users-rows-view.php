<?php foreach ($rows as $row): $recordId=(int)$row['id']; ?>
    <tr>
        <td class="idx"><?=$recordId?></td>
        <td><span class="app-avatar" aria-label="<?=e($row['fullname'])?>"><?=e(mb_strtoupper(mb_substr($row['fullname'],0,1)))?></span></td>
        <td class="u-name"><?=e($row['fullname'])?></td>
        <td class="u-id"><?=e($row['studentid'])?></td>
        <td class="u-email"><?=e($row['email'])?></td>
        <td><span class="<?=$row['status']==='active'?'pill-active':'app-inactive'?>"><?=e(ucfirst($row['status']))?></span></td>
        <td>
            <?php if ($row['role']==='admin'): ?>
                <span class="app-muted">Administrator</span>
            <?php else: ?>
                <a class="btn-view" href="manage_users.php?edit=<?=$recordId?>">View / Edit</a>
                <form method="post" class="app-inline" action="manage_users.php">
                    <?=token()?>
                    <input type="hidden" name="id" value="<?=$recordId?>">
                    <button class="app-button danger" name="action" value="delete" onclick="return confirm('Permanently delete this student account?')">Delete</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
