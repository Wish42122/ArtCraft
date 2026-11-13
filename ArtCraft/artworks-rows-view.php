<?php foreach ($rows as $row): $recordId=(int)$row['id']; ?>
    <tr>
        <td class="idx"><?=$recordId?></td>
        <td><img class="thumb" src="image.php?id=<?=$recordId?>" alt="<?=e($row['title'])?>" loading="lazy"></td>
        <td class="t-title"><?=e($row['title'])?><?=$row['featured']?' · Featured':''?></td>
        <td class="t-artist"><?=e($row['fullname'])?></td>
        <td class="t-cat"><?=e($row['category'])?></td>
        <td><span class="pill <?=e($row['status'])?>"><?=e(ucfirst($row['status']))?></span></td>
        <td>
            <a class="btn-view" href="manage_artworks.php?edit=<?=$recordId?>">View / Edit</a>
            <form method="post" class="app-inline" action="manage_artworks.php">
                <?=token()?>
                <input type="hidden" name="id" value="<?=$recordId?>">
                <?php if ($row['status']!=='approved'): ?>
                    <button class="btn-approve" name="action" value="approve">Approve</button>
                <?php endif; ?>
                <?php if ($row['status']!=='rejected'): ?>
                    <button class="btn-reject" name="action" value="reject">Reject</button>
                <?php endif; ?>
                <button class="app-button danger" name="action" value="delete" onclick="return confirm('Permanently delete this artwork?')">Delete</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
