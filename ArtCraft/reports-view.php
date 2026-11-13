<div class="card">
<h1>Reports</h1>
<p class="subtitle">Current submissions and exhibition registrations</p>
<div class="app-tools"><a href="reports.php?csv=submissions">Download submissions CSV</a><a href="reports.php?csv=participation">Download participation CSV</a></div>
<h2>Artwork submissions by month</h2>
<div class="app-table"><table><thead><tr><th>Month</th><th>Total</th><th>Pending</th><th>Approved</th><th>Rejected</th></tr></thead><tbody>
<?php foreach ($months as $row): ?><tr><?php foreach ($row as $cell): ?><td><?=e($cell)?></td><?php endforeach; ?></tr><?php endforeach; ?>
</tbody></table><?php if (!$months): ?><p class="app-empty">No submissions yet.</p><?php endif; ?></div>
<h2>Exhibition participation</h2>
<div class="app-table"><table><thead><tr><th>Exhibition</th><th>Date</th><th>Registrations</th></tr></thead><tbody>
<?php foreach ($events as $row): ?><tr><td><?=e($row['title'])?></td><td><?=e($row['event_date'])?></td><td><?=e($row['registrations'])?></td></tr><?php endforeach; ?>
</tbody></table><?php if (!$events): ?><p class="app-empty">No exhibitions yet.</p><?php endif; ?></div>
</div>
