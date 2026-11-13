<?php
require __DIR__.'/bootstrap.php';
$art = sql('SELECT user_id,status,mime,image FROM artworks WHERE id=?',[id($_GET['id'] ?? null)])->fetch();
$user = $art && $art['status'] !== 'approved' ? current_account() : null;
if (!$art || ($art['status'] !== 'approved' && (!$user || ($user['role'] !== 'admin' && (int)$user['id'] !== (int)$art['user_id'])))) {
    http_response_code(404);
    exit;
}
header('Cache-Control: no-store');
header('Content-Type: '.$art['mime']);
echo $art['image'];
